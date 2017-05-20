### What is it ??

Tiga-Router is a WordPress router, our aim is to simplify building custom WordPress application. 

### Adding Route

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
### Getting Route parameter

```

```

### Use Case 

( Work in progress ) 

1. Ajax Call
2. Custom front end for user generated content 