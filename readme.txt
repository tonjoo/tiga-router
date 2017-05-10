=== WooCommerce ===
Contributors: todi.adiyatmo, gamaup
Tags: router
Requires at least: 4.4
Tested up to: 4.7.3
Stable tag: 1
License: MIT
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Tiga is a WordPress router, simplify building custom WordPress application 

== Description ==

```
// Hooking up our function to theme setup
add_action( 'init', 'register_item_post_type' );

function register_theme_routes() {

	TigaRoute::get( '/items/new', 'item_new');
	TigaRoute::post( '/items/create', 'item_create');
	TigaRoute::get( '/items/{id:num}', 'item_edit');
	TigaRoute::post( '/items/{id:num}', 'item_update');
	TigaRoute::delete( '/items/{id:num}', 'item_delete');
	TigaRoute::get('/items', 'item_index');

}

add_action( 'tiga_route', 'register_theme_routes');

function item_index() {

	set_tiga_template( 'inc/items/index', $data);
}

function item_new() {

	$data['items'] = // some data;

	set_tiga_template( 'inc/items/new.php', $data);
}
```
