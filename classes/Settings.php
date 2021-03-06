<?php

namespace Wiredot\WP_Gallery;

use Wiredot\Copernicus\Twig\Twig;

class Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
		add_action( 'wp_ajax_WP_GALLERY_general', array( $this, 'save_general_settings' ) );
	}

	public function add_settings_menu() {
		add_submenu_page(
			'edit.php?post_type=wp-gallery',
			__( 'Settings' ),
			__( 'Settings' ),
			'manage_options',
			'wp-gallery-settings',
			array( $this, 'settings_page' )
		);
	}

	public function settings_page() {

		$options = get_option( 'wp-gallery' );

		if ( ! is_array( $options ) ) {
			$options = array(
				'hide_css' => 0,
				'hide_js' => 0,
				'image_quality' => 90,
			);
		}

		$Twig = new Twig();
		echo $Twig->twig->render(
			'settings.twig', array(
				'hide_css' => $options['hide_css'],
				'hide_js' => $options['hide_js'],
				'image_quality' => $options['image_quality'],
			)
		);
	}

	public function save_general_settings() {
		$hide_js = intval( $_POST['hide_js'] );
		$hide_css = intval( $_POST['hide_css'] );
		$image_quality = intval( $_POST['image_quality'] );

		$options = array(
			'hide_css' => $hide_css,
			'hide_js' => $hide_js,
			'image_quality' => $image_quality,
		);

		update_option( 'wp-gallery', $options );

		set_transient( 'WP_GALLERY_message', __( 'Settings updated.', 'wp-gallery' ), 2 );
		wp_redirect( 'edit.php?post_type=wp-gallery&page=wp-gallery-settings' );
		exit;
	}

}
