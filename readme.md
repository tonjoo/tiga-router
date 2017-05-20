# Tiga Router
Contributors: todi.adiyatmo, gamaup
Tags: router
Requires at least: 4.4
Tested up to: 4.7.3
Stable tag: 1
License: MIT
License URI: https://www.gnu.org/licenses/gpl-3.0.html

## Description
Tiga is a WordPress router, simplify building custom WordPress application 

### Registering Routes
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
### Controller
```
function item_index($request) {
    $data = $request->all(true);
	set_tiga_template( 'page-index.php', $data);
}
```

### Request
##### `$request->all($sanitize)`
> $sanitize (boolean) (optional) (default:false) -> set sanitize for inputs
> return (array) -> get all inputs
##### `$request->input($key,$sanitize)`
> $key (string) (required) -> input name
> $sanitize (boolean) (optional) (default:false) -> set sanitize for input
> return (mixed) -> get input value
##### `$request->has($key)`
> $key (string) (required) -> input name
> return (boolean) -> return true if input key exists

### Template
`set_tiga_template($template_name, $data)`
> $template_name (string) template name / file name
> $data (mixed) -> variable that passed to template