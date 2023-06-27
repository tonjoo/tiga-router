<?php

namespace Models;

class Posts {

	public function __construct() {}

	public function latest( $limit ) {
		$query = wp_query_builder()
			->from( 'posts' )
			->where( [
				"post_type" => "post",
				"post_status" => "publish"
			] )
			->limit( $limit )
			->get();
		
		return $query;
	}

}