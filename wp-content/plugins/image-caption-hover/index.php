<?php
/**
 * Plugin Name: Image Caption Hover
 * Plugin URI: https://webcodingplace.com/image-caption-hover-pro-wordpress-plugin
 * Description: A collection of more than 200 hover effects for images and captions that works on all modern browsers and devices.
 * Version: 10.3.6
 * Author: WebCodingPlace
 * Author URI: http://webcodingplace.com/
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: image-caption-hover
 */

/*
  Copyright (C) 2015  WebCodingPlace  help@webcodingplace.com
*/
require_once('widget.php');

require_once('plugin.class.php');

if( class_exists('Image_Caption_Hover')){
	
	$just_initialize = new Image_Caption_Hover;
}

require_once('cpt.class.php');

if( class_exists('Image_Caption_Hover_CPT')){
	
	$just_initialize = new Image_Caption_Hover_CPT;
}

?>