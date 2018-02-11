<?php

class Rental{
    public $name = '';
    public $description = '';
    public $attributes = array();
    public $photos = array();

    public $categoryName = '';


    public function __toString() {
        return $this->name;
    }
}
