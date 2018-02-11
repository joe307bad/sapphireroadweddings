<?php

require_once
    plugin_dir_path(__FILE__) . '../bottomline/bottomline.php';
require_once
    plugin_dir_path(__FILE__) . '../models/category.php';
require_once
    plugin_dir_path(__FILE__) . '../models/rental.php';

class RentalsParser
{
    public $rentalsDirectory;
    public $categories;
    public $files = [];

    private $rentalModel;

    public function __construct($dir)
    {
        $this->rentalsDirectory = $dir;

        $categoryClass = new ReflectionClass('Category');
        $this->categories = $categoryClass->getConstants();
    }

    public function getRentalsInfo()
    {
        $photos = $this->getAllRentalPhotos($this->rentalsDirectory);
        return $this->getListOfRentalModels($photos);
    }

    function getAllRentalPhotos($dir)
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        $files = array();

        foreach ($rii as $file) {

            if ($file->isDir()) {
                continue;
            }

            $files[] = $file->getPathname();

        }

        return $files;
    }

    function getListOfRentalModels($photos)
    {

        $rentalModels = [];

        foreach ($photos as $rentalPhotoFilePath) {
            $addRentalModels =
                $this->makeRentalModel($rentalPhotoFilePath, $rentalModels);

            if ($addRentalModels !== false) {
                $rentalModels = $addRentalModels;
            }
        }

        return $rentalModels;
    }

    function makeRentalModel($rentalPhotoFilePath, $rentalModels)
    {

        $rentalPhotoFileName = pathinfo($rentalPhotoFilePath)['filename'];
        $hasUnderscore = strpos($rentalPhotoFileName, '_') !== false;
        $rentalName = $hasUnderscore
            ? substr($rentalPhotoFileName, 0,
                strpos($rentalPhotoFileName, "_", true))
            : $rentalPhotoFileName;

        if (strpos($rentalName, '.') !== false)
            return false;

        $categories = $this->categories;
        $categoryName = "";

        foreach ($categories as $id => $label) {
            if (strpos($rentalPhotoFilePath, $label) !== false) {
                $categoryName = $label;
                break;
            }
        }

        $this->rentalModel = new Rental;
        $this->rentalModel->categoryName = $categoryName;
        $this->rentalModel->name = $rentalName;
        $this->rentalModel->photos[] = $rentalPhotoFilePath;

        $rentalExists = __::filter($rentalModels, function ($rental) {
            return $rental->name === $this->rentalModel->name;
        });

        if(!__::isEmpty($rentalExists)){
            $existingRental = __::first($rentalExists);
            $existingRental->photos[] = $rentalPhotoFilePath;
            return false;
        }

        $rentalModels[] = $this->rentalModel;
        return $rentalModels;
    }
}