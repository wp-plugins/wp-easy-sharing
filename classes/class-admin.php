<?php
class ES_Admin {


	
	public function __construct() {

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		
		add_filter( "plugin_action_links_wp-easy-sharing/index.php", array( $this, 'add_settings_link' ) );

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'wp-easy-sharing' ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_css' ) );
		}
	}
	
	function wes_plugin_activation_action(){
		$defaults = array(
				'twitter_username' => "",
				'auto_add_post_types' => array( 'post' ),
				'social_options'=>array('facebook','twitter','googleplus'),
				'load_esset'=>array('load_css','load_js'),
				'facebook_text'=>"",
				'twitter_text'=>"",
				'googleplus_text'=>"",
				'linkedin_text'=>"",
				'pinterest_text'=>"",
				'pinterest_image'=>"",
				'show_icons'=>'0',
				'before_button_text'=>''
		);
		update_option( 'wpe_sharing', $defaults );
		update_option( 'wes_wpe_sharing','f,t,g,l,p');
		update_option( 'wes_pluign_version ',ES_VERSION);
	}
	
	public function load_css() {
		wp_enqueue_style ( 'wp-easy-sharing', ES_PLUGIN_URL . 'assets/admin-styles.css' );
		wp_enqueue_media();
		wp_enqueue_script( 'wp-easy-sharing', ES_PLUGIN_URL . 'assets/socialshareadmin.js', array(), ES_VERSION, true );
	}

	public function register_settings() {
		register_setting( 'wpe_sharing', 'wpe_sharing', array($this, 'sanitize_settings') );
	}

	public function sanitize_settings( $settings ) {
		$settings['before_button_text'] = trim( strip_tags( $settings['before_button_text'] ) );
		$settings['twitter_username'] = trim( strip_tags( $settings['twitter_username'] ) );
		$settings['facebook_text'] = trim( strip_tags( $settings['facebook_text'] ) );
		$settings['twitter_text'] = trim( strip_tags( $settings['twitter_text'] ) );
		$settings['googleplus_text'] = trim( strip_tags( $settings['googleplus_text'] ) );
		$settings['linkedin_text'] = trim( strip_tags( $settings['linkedin_text'] ) );
		$settings['pinterest_text'] = trim( strip_tags( $settings['pinterest_text'] ) );
		$settings['pinterest_image'] = trim( strip_tags( $settings['pinterest_image'] ) );
		$settings['auto_add_post_types'] = ( isset( $settings['auto_add_post_types'] ) ) ? $settings['auto_add_post_types'] : array();
		$settings['show_sharebutton'] = ( isset( $settings['show_sharebutton'] ) ) ? $settings['show_sharebutton'] : array();
		return $settings;
	}

	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=wp-easy-sharing">'. __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function add_menu_item() {
		add_options_page( 'WP Easy Sharing', 'WP Easy Sharing', 'manage_options', 'wp-easy-sharing', array( $this, 'show_settings_page' ) );
	}

	public function show_settings_page() {
		$opts = ES_get_options();
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		include ES_DIR . 'templates/settings-page.php';
	}
}