<?php
/**
Plugin Name: Maintenance Mode & Coming Soon
Plugin URI: http://web-settler.com/maintenance-mode/?ref=wpOrg
Description: Add a responsive maintenance mode or coming soon page to your site that lets visitors know your site is down or under construction.
Author: Maintenance Mode Builder
Author URI: http://web-settler.com/maintenance-mode/?ref=authoruri
Version: 3.8.1
Copyright: 2017 Muneeb ur Rehman http://muneeb.me/contact/?ref=readmewp
**/

require plugin_dir_path( __FILE__ ) . 'config.php';

require WPMMP_PLUGIN_INCLUDE_DIRECTORY . 'functions.php';

define( 'WPMMP_PRO_VERSION_ENABLED', true );

add_option( 'wpmmp_install_version', WPMMP_PLUGIN_VERSION );

load_wpmmp();

