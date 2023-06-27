<?php

namespace Controllers;

use Models\Posts as Posts;

class WelcomeController {

	private static $instances = [];

    public $posts;

	public function __construct() {
        $this->posts = new Posts();
    }

	public static function instance() {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    public function index() {
    	$posts = $this->posts->latest(10);
    	tiga_view( 'welcome', compact( 'posts' ) );
    }

}