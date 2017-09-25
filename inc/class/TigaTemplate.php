<?php

class TigaTemplate {
	/**
	 * The custom page template.
	 *
	 * @var Template
	 */
	private $template;

	/**
	 * Template data.
	 *
	 * @var data
	 */
	private $data;

	/**
	 * The unique instance .
	 *
	 * @var $instance
	 */
	private static $instance;

	public function __construct( $template, $data ) {
		$this->template = $template;
		$this->data = $data;

	}

	public static function init( $template, $data ) {

		self::$instance = new self( $template, $data );

		add_action( 'template_include', array( self::$instance, 'set_template' ) );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			return false;
		}

		return self::$instance;
	}

	/*
     * Render template from set_tiga_template()
     */
	public function render_template() {

		$data = $this->data;
		$new_template = locate_template( array( $this->template ) );

		if ( '' != $new_template ) {
			include $new_template ;
		}

	}

	function set_template() {

		return TIGA_WORDPRESS_ROUTER_PATH . 'inc/tiga-template.php';

	}


}



