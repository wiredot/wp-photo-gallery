<?php

namespace WP_PG;

class WP_PG_Theme {

	private $name;

	private $path;

	private $url;

	private $css = array();

	private $js = array();

	public function __construct($name) {
		$this->name = $name;
		$this->path = WP_PG_PATH.'/themes/'.$name;
		$this->url = WP_PG_URL.'themes/'.$name;
		$this->get_theme_config();

		add_filter( 'wp_enqueue_scripts', array($this, 'add_css') );
		add_filter( 'wp_enqueue_scripts', array($this, 'add_js') );
	}

	public function get_theme_config() {
		$config_file = $this->path.'/config.php';

		if (file_exists($config_file)) {
			require $config_file;

			if (isset($wp_pg_theme_config['css'])) {
				$this->css = $wp_pg_theme_config['css'];
			}
		
			if (isset($wp_pg_theme_config['js'])) {
				$this->js = $wp_pg_theme_config['js'];
			}
		}
	}

	public function add_css() {
		foreach ($this->css as $key => $css) {
			wp_enqueue_style( $key, $this->url . '/' .$css );
		}
	}

	public function add_js() {
		foreach ($this->js as $key => $js) {
			wp_enqueue_script( $key, $this->url . '/' . $js, array('jquery'), '1.0.0', true );
		}
	}

	public function get_path() {
		return $this->path;
	}

// class end
}