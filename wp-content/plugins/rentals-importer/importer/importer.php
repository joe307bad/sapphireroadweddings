<?php
require_once('../../../../wp-load.php');

require_once('../../../../wp-admin/includes/image.php');
require_once('../../../../wp-admin/includes/file.php');
require_once('../../../../wp-admin/includes/media.php');

require_once
    plugin_dir_path(__FILE__) . '../bottomline/bottomline.php';

class RentalImporter
{
    public $categories;
    public $rentals;

    private $rentalData;
    private $newPhotoId;

    public function __construct($categories, $rentals)
    {
        $this->categories = $categories;
        $this->rentals = $rentals;
    }

    public function run()
    {

        $insertedCategories = get_terms("rental_category", array("hide_empty" => 0));

        $rentals = $this->rentals;

        if (count($insertedCategories) <= count($this->categories)) {
            foreach ($this->categories as $category) {
                wp_insert_term(
                    $category,
                    'rental_category'
                );
            }
            $insertedCategories = get_terms("rental_category", array("hide_empty" => 0));
        }

        foreach ($rentals as $rental) {
            $newPostId = $this->insertRental($rental, $insertedCategories);

            if ($newPostId > 0 && $rental['photos']) {
                foreach ($rental['photos'] as $rentalPhotoPath) {
                    $this->uploadAndAssociateRentalPhoto($newPostId, $rentalPhotoPath);
                }
            }
        }

        echo true;
    }

    private function insertRental($rental, $categories)
    {
        $this->rentalData = $rental; //$this->rentals[1];
        $newPostId = 0;

        $rentalCategoryExists = __::filter($categories, function ($category) {
            return $category->name === $this->rentalData['categoryName'];
        });

        $existingRental = $this->rentalExists($this->rentalData['name']);

        if (count($existingRental) === 0) {

            $rentalCategory = __::first($rentalCategoryExists);

            $rental = array(
                'post_title' => $this->rentalData['name'],
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'rentals'
            );

            $newPostId = wp_insert_post($rental);

            $addPostToCategory = wp_set_object_terms($newPostId, array($rentalCategory->term_id), 'rental_category');
        }

        return $newPostId;
    }

    private function uploadAndAssociateRentalPhoto($postId, $rentalPhotoPath)
    {
        $uploadedPhoto = $this->uploadFile($rentalPhotoPath);

        $photoId = $uploadedPhoto["attachment_id"];//"958";
        $this->newPhotoId = $photoId;
        $photoName = $uploadedPhoto["fileName"];

        $encodedAttachments = get_post_meta($postId, 'attachments', true);
        $attachments = json_decode($encodedAttachments);
        $attachmentsExist = $attachments->my_attachments !== null;
        $newAttachments = new stdClass();

        if ($attachmentsExist) {
            $newAttachments->my_attachments = $attachments->my_attachments;
        } else {
            $newAttachments->my_attachments = array();
        }

        $newPhoto = new stdClass();
        $newPhoto->id = $photoId;
        $newPhoto->fields->title = $photoName;
        $newPhoto->fields->caption = '';

        $newPhotoExists = __::filter($newAttachments->my_attachments, function ($attachment) {
            return $attachment->id === $this->newPhotoId;
        });

        if (count($newPhotoExists) === 0) {
            $newAttachments->my_attachments[] = $newPhoto;

            if ($attachmentsExist) {
                $updatedMeta = update_post_meta($postId, 'attachments', json_encode($newAttachments));
            } else {
                $addMeta = add_post_meta($postId, 'attachments', json_encode($newAttachments));
            }
        }
    }

    private function rentalExists($title)
    {
        global $wpdb;
        $query = $wpdb->prepare(
            'SELECT ID FROM ' . $wpdb->posts . '
        WHERE post_title = %s
        AND post_type = \'rentals\'',
            $title
        );
        //$wpdb->query($query);
        return $wpdb->get_results($query);
    }

    private function uploadFile($path, $add_to_media = true)
    {
        if (!file_exists($path)) {
            return array('error' => 'File does not exist.');
        }
        $filename = basename($path);
        $filename_no_ext = pathinfo($path, PATHINFO_FILENAME);
        $mediaExists = $this->mediaExists($filename_no_ext);

//        if ($mediaExists) {
//            return array('error' => 'File exists in DB');
//        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        // Simulate uploading a file through $_FILES. We need a temporary file for this.
        $tmp = tmpfile();
        $tmp_path = stream_get_meta_data($tmp)['uri'];
        fwrite($tmp, file_get_contents($path));
        fseek($tmp, 0); // If we don't do this, WordPress thinks the file is empty
        $fake_FILE = array(
            'name' => $filename,
            'type' => 'image/' . $extension,
            'tmp_name' => $tmp_path,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($path),
        );
        // Trick is_uploaded_file() by adding it to the superglobal
        $_FILES[basename($tmp_path)] = $fake_FILE;
        $result = wp_handle_upload($fake_FILE, array('test_form' => false, 'action' => 'local'));
        fclose($tmp); // Close tmp file
        @unlink($tmp_path); // Delete the tmp file. Closing it should also delete it, so hide any warnings with @
        unset($_FILES[basename($tmp_path)]); // Clean up our $_FILES mess.
        $result['attachment_id'] = 0;
        if (empty($result['error']) && $add_to_media) {
            $args = array(
                'post_title' => $filename_no_ext,
                'post_content' => '',
                'post_status' => 'publish',
                'post_mime_type' => $result['type'],
            );
            $result['attachment_id'] = wp_insert_attachment($args, $result['file']);
            if (is_wp_error($result['attachment_id'])) {
                $result['attachment_id'] = 0;
            } else {
                $attach_data = wp_generate_attachment_metadata($result['attachment_id'], $result['file']);
                wp_update_attachment_metadata($result['attachment_id'], $attach_data);
            }
        }
        $result['fileName'] = $filename_no_ext;
        return $result;
    }

    private function mediaExists($filename)
    {
        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE post_title LIKE '$filename'";
        return ($wpdb->get_var($query) > 0);
    }
}



$rentalImporter = new RentalImporter($_POST['categories'], $_POST['rentals']);
$rentalImporter->run();