<?php
/*
Plugin Name: Rentals Importer
Description: Rentals Importer
Author: Joe Badaczewski
Version: 1.0
*/
require_once
    plugin_dir_path( __FILE__ ) . '/rentals-parser/rentals-parser.php';

class RentalsImporter {
    public $Name = "Rentals Importer";
    public $MenuTitle = "Rentals Importer";
    public $Capability = "manage_options";
    public $MenuSlug = "rentals-importer";

    public $rentalsParser;
    public $files;

    public function __construct(){
        $this->rentalsParser = new RentalsParser;
    }

    public function render(){
        $this->files = $this->rentalsParser->getRentalsInfo();

        foreach($this->files as $name => $Regex){
            echo "$name\n";
        }

        echo "<h1>Hello World!</h1>";
    }

    public function createAdminPage(){
        add_menu_page(
            $this->Name,
            $this->MenuTitle,
            $this->Capability,
            $this->MenuSlug,
            array($this, 'render'));
    }

    public function run(){
        add_action('admin_menu', array($this, 'createAdminPage'));
    }



}

if ( ! defined( 'WPINC' ) ) {
    die;
}

//require_once plugin_dir_path( __FILE__ ) . 'includes/class-single-post-meta-manager.php';

function run_rental_importer() {

    $ri = new RentalsImporter;
    $ri->run();

}

run_rental_importer();