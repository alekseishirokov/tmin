<?php
namespace tmin\core;

require_once $_SERVER["DOCUMENT_ROOT"] . "/class/tmin/core/Application.php";
$app = Application::getApplication();

$router = $app->getRouter();

// TODO: Add routes here.
$router->addRoute(new Route('get', '/{*path}', 'acme\todo\site\Site', 'route', array(context=>'/')));
$router->addRoute(new Route('post', '/{*path}', 'acme\todo\site\Site', 'route', array(context=>'/')));
$router->addRoute(new Route('put', '/{*path}', 'acme\todo\site\Site', 'route', array(context=>'/')));
$router->addRoute(new Route('delete', '/{*path}', 'acme\todo\site\Site', 'route', array(context=>'/')));

//$router->addRoute(new Route('get', 'api/text/{*path}', 'acme\todo\text\Text', 'route'));
//$router->addRoute(new Route('put', 'api/text/{*path}', 'acme\todo\text\Text', 'route'));
//$router->addRoute(new Route('post', 'api/text/{*path}', 'acme\todo\text\Text', 'route'));
//$router->addRoute(new Route('delete', 'api/text/{*path}', 'acme\todo\text\Text', 'route'));

//$router->addRoute(new Route('get', 'api/text', 'acme\todo\text\Text', 'show'));
//$router->addRoute(new Route('get', 'api/text/add', 'acme\todo\text\Text', 'add'));
//$router->addRoute(new Route('get', 'api/text/edit', 'acme\todo\text\Text', 'edit'));
//$router->addRoute(new Route('get', 'api/text/{id}/edit', 'acme\todo\text\Text', 'editItem'));
//$router->addRoute(new Route('get', 'api/text/{id}/done', 'acme\todo\text\Text', 'done'));
//$router->addRoute(new Route('get', 'api/text/{id}', 'acme\todo\text\Text', 'showItem'));
//$router->addRoute(new Route('post', 'api/text/{id}', 'acme\todo\text\Text', 'put'));
//$router->addRoute(new Route('post', 'api/text', 'acme\todo\text\Text', 'post'));
//$router->addRoute(new Route('put', 'api/text/{id}', 'acme\todo\text\Text', 'put'));
//$router->addRoute(new Route('delete', 'api/text/{id}', 'acme\todo\text\Text', 'delete'));

$app->run();

exit;