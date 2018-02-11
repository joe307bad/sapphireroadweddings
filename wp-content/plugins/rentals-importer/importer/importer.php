<?php
require_once('../../../../wp-load.php');
$categories = $_POST['categories'];
$rentals = $_POST['rentals'];

foreach($categories as $category){
    wp_insert_term(
        $category,
        'rental_categories'
    );
}


echo $id;