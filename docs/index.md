# What is it ??

Tiga-Router is a WordPress router, our aim is to simplify building custom WordPress application. 

# Registering Routes

Register desired routes using `TigaRoute` class. Route registration must be done using a hook called `tiga_route`.

```php
TigaRoute::method( string $path, string|array $callback, array $args );
```

- Available methods: get, post, put, delete.
- $path & $callback are mandatory. While $args is optional

Example:

```php
function register_theme_routes() {
	TigaRoute::get( '/items/new', 'item_new' );
	TigaRoute::post( '/items/create', 'item_create' );
	TigaRoute::get( '/items/{id:num}', 'item_edit' );
	TigaRoute::put( '/items/{id:num}', 'item_update' );
	TigaRoute::delete( '/items/{id:num}', 'item_delete' );
	TigaRoute::get('/items', 'item_index' );
}
add_action( 'tiga_route', 'register_theme_routes' );
```

## Class based callback

For class function callback, make sure no namespace is used. Or if it is used make used make sure it is loaded properly.

```php
function register_theme_routes() {
	// call method inside class
	TigaRoute::get( '/items/new', array( 'class_name', 'method_name' ) );
	// or
	$controller = new Class_Name();
	TigaRoute::get( '/items/new', array( $controller, 'method_name' ) );
	// or
	TigaRoute::get( '/items/new', array( $this, 'method_name' ) );
}
add_action( 'tiga_route', 'register_theme_routes');
```

## Caveat

Tiga Router is using route hash to conditionaly flush rewrite rules. However this come with some caveat : Do not make conditional route registration. 


```php
// Example only , do not do this
if( is_admin() ) {
	TigaRoute::get( '/items/new', 'item_new');	
}
TigaRoute::get( '/items/list', 'item_list');
```

When the route hash is different, it will flush WordPress rewrite rules each time we access different page

#### Available pattern:
- `:num`: only accept numeric parameters (non-greedy match)
- `:num?`: only accept numeric parameters (greedy match)
- `:any`: accept all url-valid parameters (non-greedy match)
- `:any?`: accept all url-valid parameters (greedy match)
- `:all`: accept all characters parameters (non-greedy match)
- `:all?`: accept all characters parameters (greedy match)

or you can use any regular expression, exp: `:[a-z]+`

### Route grouping

Routes with same path can be grouped into one. For example:

```php
TigaRoute::group( '/items', function() {
    TigaRoute::get( '/list', 'item_list' );
    TigaRoute::post( '/new', 'item_new' );
} );
// same as:
TigaRoute::get( '/items/list', 'item_list' );
TigaRoute::post( '/items/new', 'item_new' );
```

### Set Page Title

Page title can be set easily while registering the route. Example:

```php
TigaRoute::get( '/items/list', 'item_list', array( 'title' => 'All Items - Your Site' );

// route parameter value also can be passed to title
TigaRoute::get( '/items/{id:num}', 'item_detail', array( 'title' => 'Item no {id} - Your Site' );
```

### Translation Route (Polylang Integration)

If your site are using Polylang, you can set a translation route using this simple args:
```php
TigaRoute::get( '/items/list', 'item_list', array( 'polylang' => true ) );
```
This args will duplicate the route into translation routes based on Polylang configuration. For example, if your site are using english and indonesian, the registered routes will be:
```
/en/items/list
/id/items/list
```
All those routes will have same callback but with different language loaded.

## Controller & View

The registered route will run a function callback.  

```php
// route registration.
function register_theme_routes() {
    TigaRoute::get( '/items/{id:num}', 'item_detail' );
}
add_action( 'tiga_route', 'register_theme_routes' );

// callback controller
function item_detail( $request ) {

    // getting parameter from url.
    $id = $request->input('id');
    $data = array(
        'id' => $id,
        'title' => get_the_title( $id )
    );
    
    // load a view file and passing data.
	set_tiga_template( 'page-index.php', $data );
}
```

Passed data should be formatted as array, and can be accessed directly from view file using its index name. Example: `$id` and `$title`

### Getting Route Parameter 

Use the `$request` object to access variable in the current request. The `$request` object also provide a function to sanitize the input.

#### `$request->all()`

- return (array) -> get all inputs

#### `$request->input( $key, $default )`

- $key (string) (required) -> input name
- $default (boolean) (optional) (default:false) -> set default value for input
- return (mixed) -> get input value

#### `$request->file( $key )`

- $key (string) (required) -> file name
- return (boolean) -> return true if input key exists

#### `$request->has( $key )`

- $key (string) (required) -> input name
- return (boolean) -> return true if input key exists

#### `$request->hasFile( $key )`

- $key (string) (required) -> file name
- return (boolean) -> return true if input key exists

# Tiga CLI

### Generate starter files

Run this command on your wordpress installation:
```
wp tiga init
```
This command will generate starter files directly on your active theme. The starter files will be formatted as a MVC app which contains route, controllers, model and view file.

### View all registered routes

This command will shows all registered routes

```
wp tiga list
```

# Helper Functions

- `is_tiga()` - check if current page is a tiga page.
- `tiga_is_route( string $route )` - check if current page is matched with given route.
- `tiga_is_group( string $route )` -  check if current page is within a route group.
- `tiga_set_404()` - throw the route to 404 page.
- `tiga_set_401( string $message = '401 Unauthorized' )` - throw the route to 401.
- `tiga_set_403( string $message = '403 Forbidden' )` - throw the route to 403.

## Pagination 

Built in pagination class is on `Tiga\Pagination`

```php
$pagination = new \Tiga\Pagination;

// set up pagination parameter
$config = array()
// Total Row from the database
$config['rows'] = $total_row ; 
$config['current_page'] = $current_page;
$config['per_page'] = 10;
// append parameter from url
$config['appends'] = array('sort_by','q'); 
$config['base_url'] = 'http://127.0.0.1/wordpress/[paginate]';

$pagination->setup($config);

// render the pagination
$pagination->render();
```

### Pagination Parameter

The pagination output can be customized

```php
$config['rows']   		  = // Total Row from the database
$config['per_page']       = 10;
$config['per_page']       = 0; // first page
$config['base_url']		  = 'http://127.0.0.1/wordpress/[paginate]'
$config['appends'] 		  = array();
$config['item_to_show']   = 2;
$config['skip_item']      = true;
        
$config['first_tag_open'] = "<li>";
$config['first_tag_close']= "</li>";
    
$config['last_tag_open']  = "<li>";
$config['last_tag_close'] = "</li>";
    
$config['prev_tag_open']  = "<li>";
$config['prev_tag_close'] = "</li>";
$config['prev_tag_text']  = "Prev";
     
$config['next_tag_open']  = "<li>";
$config['next_tag_close'] = "</li>";
$config['next_tag_text']  = "Next";
        
$config['cur_tag_open']   = "<li class='active'>";
$config['cur_tag_close']  = "</li>";
$config['link_attribute'] = "class=''";
$config['link_attribute_active'] = "class='active'";
        
$config['num_tag_open']   = "<li>";
$config['num_tag_close']  = "</li>";
$config['skip_tag_open']  = "<li>";
$config['skip_tag_close'] = "</li>";
$config['skip_tag_text']  = "<a href='#'>....</a>";
$config['start_page']     = 0;
        
```

## Session

Session wrapper class is on `Tiga\Session`, based on `WP Session Manager` plugin.

```php
$session = new \Tiga\Session;

$sesion->set('key',$value);
$sesion->get('key',$value);
$sesion->has('key');
$sesion->pull('key',$value);
$sesion->keys();
$sesion->clear();
```

#### Set session: `$session->set($key, $value)`
- $key (string) session key.
- $value (mixed) session value.

#### Get session: `$session->get($key,$value)`
- $key (string) session key.
- $value (string) default value if session is not exists.
- return (mixed) session value.

#### Check if session exists: `$session->has($key)`
- $key (string) session key.
- return (boolean) session status with given key.

#### Get session then delete it: `$session->pull($key,$value)`
- $key (string) session key.
- $value (string) default value if session is not exists.
- return (boolean) session status with given key.

#### Get all session keys: `$session->keys()`

#### Clear all session: `$session->clear()`

How to use `$_SESSION` instead of `WP Session` on wrapper class:
```
define( 'TIGA_SESSION', '$_SESSION' );
```

# Query Builder

Tiga are using Wordpress Query Builder Library by  [10quality](https://github.com/10quality/wp-query-builder)

This library has included to Tiga Router. Below codes will show you how to use query builder on Tiga Router:

```php
$query = wp_query_builder()
	->from( 'posts' )
	->where( [
		"post_type" => "post",
		"post_status" => "publish"
	] )
	->get();
```

Please refer to their documentation, visit: [https://github.com/10quality/wp-query-builder/wiki](https://github.com/10quality/wp-query-builder/wiki)


# Handle Ajax Request

Sample code to handle ajax request on `/sample_ajax_tiga` path

```
function register_theme_routes() {
	TigaRoute::get( '/sample_ajax_tiga', 'ajax_sample');
}
add_action( 'tiga_route', 'register_theme_routes');

function ajax_sample($request) {

    wp_send_json( $json );
}
```

# Sample Theme and CRUD 

https://github.com/tonjoo/tiga-theme-sample



