<?php

class RentalsParser
{
    public $rentalsDirectory;
    public $files = [];

    public function __construct()
    {
        $this->rentalsDirectory =
            str_replace(
                '\\',
                '/',
                getcwd()
                . '\\..\\wp-content\\plugins\\rentals-importer\\rentals\\'); //plugin_dir_path( __FILE__ ) . '\*.php';
    }

    public function getRentalsInfo()
    {
        return $this->glob_recursive($this->rentalsDirectory);
    }

    function glob_recursive($dir)
    {
        $it =
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir));

        $files = [];

        while ($it->valid()) {

            $file = $it->current();
            array_push($files, $file->pathName);

            $it->next();
        }

        return $files;

    }

}