# tmin

## Overview

Tmin is a mini PHP Framework.

## Basic principles

1. Homogeneous components. No need to distinguish between components, modules, plugins, chunks and such concepts. All is a components. You can query component by http or embed it into the templated output. 
2. MVC.
3. REST. Pass proper `Accept` header with http-request to get html or json response.  
4. Human readable urls.
5. Templated output.
6. Namespaces.


## Installation

Copy `src` folder content into your web directory.


## Using

1. Create folder for your component under the `class` folder. 
Folder structure must correspond with the namespace stucture of your project.
For example, file `TextController.php` containing `TextController` class:
	```
	<?php
	namespace acme\todo\text;
	class TextController {...}
	```
must placed into the folder `/class/acme/todo/text`.

2. Create custom components, and put them into propiate folders. 
See contents of `/class/acme/todo` for example.

3. (Optional) Create templates for your components. See folder `/tpl/fancy/acme/todo/` for example.
"fancy" is the name of the template.
It can be set in `/class/tmin/Config.php` which template to use.
	```
	public static $tpl = "fancy";
	```

4. Edit `index.php` file. Add custom routes to your components.
	```
	$router = $app->getRouter();

	// TODO: Add routes here.
	$router->addRoute(new Route('get', 'api/text', 'acme\todo\text\Text', 'show'));
	$router->addRoute(new Route('get', 'api/text/{id}', 'acme\todo\text\Text', 'showItem'));

	$app->run();
	```
5. Browse your routes.
	```
	http://yourhost/api/text
	```