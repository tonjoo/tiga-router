# What is it ??

Tiga-Router is a WordPress router, our aim is to simplify building custom WordPress application. 

# Registering Routes

Register desired routes using `TigaRoute` class.

```
function register_theme_routes() {
	TigaRoute::get( '/items/new', 'item_new');
	TigaRoute::post( '/items/create', 'item_create');
	TigaRoute::get( '/items/{id:num}', 'item_edit');
	TigaRoute::post( '/items/{id:num}', 'item_update');
	TigaRoute::delete( '/items/{id:num}', 'item_delete');
	TigaRoute::get('/items', 'item_index');
}
add_action( 'tiga_route', 'register_theme_routes');
```
Available pattern: `:num`, `:num?`, `:any`, `:any?`, `:all`, `:all?`
## Controller

The registered route will run a function callback.  

```
// executed on '/items' route
function item_index($request) {
    $data = $request->all(true);
	set_tiga_template( 'page-index.php', $data);
}
```

## Getting Route Parameter 

Use the `$request` object to access variable in the current request. The `$request` object also provide a function to sanitize the input.

### `$request->all()`

- return (array) -> get all inputs

### `$request->input( $key, $default )`

- $key (string) (required) -> input name
- $default (boolean) (optional) (default:false) -> set default value for input
- return (mixed) -> get input value

### `$request->file( $key )`

- $key (string) (required) -> file name
- return (boolean) -> return true if input key exists

### `$request->has( $key )`

- $key (string) (required) -> input name
- return (boolean) -> return true if input key exists

### `$request->hasFie( $key )`

- $key (string) (required) -> file name
- return (boolean) -> return true if input key exists

# Helper Class and Function

## Page Template

You can call a page template (theme page template) on a `Controller` using the `set_tiga_template` function.

### `set_tiga_template($template_name, $data)`
- $template_name (string) template name / file name
- $data (mixed) -> variable that passed to template


## Pagination 

Built in pagination class is on `Tiga\Pagination`

```
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

```
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

```
$session = new \Tiga\Session;

$sesion->set('key',$value);
$sesion->get('key');
$sesion->has('key');
$sesion->pull('key',$value);
$sesion->keys();
$sesion->clear();
```

### `$session->set($key, $value)`

- return (array) -> get all inputs

How to use `$_SESSION` instead of `WP Session` on wrapper class:
```
define( 'TIGA_SESSION', '$_SESSION' );
```

# Use Case 

( Work in progress ) 

1. Ajax Call
2. Custom front end for user generated content 
