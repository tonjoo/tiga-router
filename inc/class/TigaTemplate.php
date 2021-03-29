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
	 * When the $template is a direct file path. 
	 * Useful if we use tiga-router on a plugin instead of theme.
	 *
	 * @var bool
	 */
	private $is_direct_path;

	/**
	 * The unique instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Constructor
	 *
	 * @param string $template       Template location.
	 * @param mixed  $data           Passed data.
	 * @param bool   $is_direct_path When the $template is a direct file path. Useful if we use tiga-router on a plugin instead of theme.
	 */
	public function __construct( $template, $data, $is_direct_path = false ) {
		$this->template = $template;
		$this->data = $data;
		$this->is_direct_path = $is_direct_path;
	}

	/**
	 * Init template
	 *
	 * @param  string $template Template location.
	 * @param  mixed  $data     Passed data.
	 */
	public static function init( $template, $data, $is_direct_path = false ) {
		self::$instance = new self( $template, $data, $is_direct_path );

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
		if ( $this->is_direct_path ) {
			$new_template = $this->template;
		} else {
			$new_template = locate_template( array( $this->template ) );
		}

		// include variable $data to the template
		set_query_var( 'data', $this->data );

		// load template
		if ( '' != $new_template ) {
			load_template( $new_template );
		}
	}

	/**
	 * Set template
	 */
	function set_template() {
		return TIGA_WORDPRESS_ROUTER_PATH . 'inc/tiga-template.php';
	}

}
