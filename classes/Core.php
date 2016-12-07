<?php

namespace Wiredot\WP_Photo_Gallery;

use Wiredot\Preamp\Core as Preamp;

class Core {

	private static $instance = null;

	private static $settings;

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		$Preamp = Preamp::run(WP_PHOTO_GALLERY_PATH, WP_PHOTO_GALLERY_URL);

		Skin_Factory::init();

		self::$settings = get_option( 'wp-photo-gallery' );
		
		if (is_admin()) {
			// init all admin functionality
			new Admin();
			new Editor();
			new Settings();
			new Welcome();
			new Skin_Directory();
			new Help();
		} else {
			new Shortcode();
		}
	}

	public static function run() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Core ) ) {
			self::$instance = new Core;
		}
		return self::$instance;
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wp-photo-gallery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	public static function get_settings($option = null) {
		if (isset(self::$settings[$option])) {
			return self::$settings[$option];
		}

		return null;
	}

}
