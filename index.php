<?php
/*
Plugin Name: WP Easy Sharing
Version: 1.0
Plugin URI: http://wordpress.org/plugins/wp-easy-sharing/
Description: Adds very attractive responsive social sharing buttons of Facebook, Twitter, Linkedin, Pinterest and Google+ to wordpress posts, pages or media. 
Author: Fahad Mahmood
Author URI: http://shop.androidbubbles.com
Text Domain: wp-easy-sharing
License: GPL v3
*/

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}


define( "ES_DIR", plugin_dir_path( __FILE__ ) ); 
define( "ES_PLUGIN_URL", plugins_url( '/' , __FILE__ ) );

require_once ES_DIR . 'core.php';

if( ! is_admin() ) {
	require_once ES_DIR . 'classes/class-public.php';
	new ES_Public();
} elseif( ! defined("DOING_AJAX") || ! DOING_AJAX ) {
	require ES_DIR . 'classes/class-admin.php';
	new ES_Admin();
}

register_activation_hook(__FILE__, array('ES_Admin','wes_plugin_activation_action'));

add_action( 'plugins_loaded', 'wes_update_db_check_while_plugin_upgrade' );

function wes_update_db_check_while_plugin_upgrade(){
	
	
	
	update_option('wes_wpe_sharing','f,t,g,l,p');
	$default=get_option('wpe_sharing');
	$default['linkedin_text']='';
	$default['pinterest_text']='';
	update_option('wpe_sharing',$default);
	
	
}