<?php
/**
 * TigaTemplate class
 *
 * @package Tiga Router
 */

/**
 * TigaTemplate class
 */
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
	 * The unique instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Constructor
	 *
	 * @param string $template Template location.
	 * @param mixed  $data     Passed data.
	 */
	public function __construct( $template, $data ) {
		$this->template = $template;
		$this->data = $data;

	}

	/**
	 * Init template
	 *
	 * @param  string $template Template location.
	 * @param  mixed  $data     Passed data.
	 */
	public static function init( $template, $data ) {

		self::$instance = new self( $template, $data );

		add_action( 'template_include', array( self::$instance, 'set_template' ) );
	}

	/**
	 * Get Instrance
	 *
	 * @return mixed
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			return false;
		}

		return self::$instance;
	}

	/**
	 * Render template
	 */
	public function render_template() {

		$data = is_array( $this->data ) ? extract( $this->data ) : $this->data;
		$new_template = locate_template( array( $this->template ) );

		if ( '' != $new_template ) {
			include $new_template ;
		}

	}

	/**
	 * Set template
	 */
	function set_template() {
		return TIGA_WORDPRESS_ROUTER_PATH . 'inc/tiga-template.php';
	}


}



