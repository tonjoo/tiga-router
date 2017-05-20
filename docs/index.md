# What is it ??

Tiga-Router is a WordPress router, our aim is to simplify building custom WordPress application. 

# Guide

## Registering Routes
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
## Controller
```
function item_index($request) {
    $data = $request->all(true);
	set_tiga_template( 'page-index.php', $data);
}
```

## Getting Route Parameter 

### `$request->all( $sanitize )`

> $sanitize (boolean) (optional) (default:false) -> set sanitize for inputs
> return (array) -> get all inputs

### `$request->input( $key, $sanitize )`

> $key (string) (required) -> input name
> $sanitize (boolean) (optional) (default:false) -> set sanitize for input
> return (mixed) -> get input value

### `$request->has( $key )`

> $key (string) (required) -> input name
> return (boolean) -> return true if input key exists

## Page Template

### `set_tiga_template($template_name, $data)`
> $template_name (string) template name / file name
> $data (mixed) -> variable that passed to template

## Use Case 

( Work in progress ) 

1. Ajax Call
2. Custom front end for user generated content 