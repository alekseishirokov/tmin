<?php
namespace tmin\core;

require_once $_SERVER["DOCUMENT_ROOT"] . "/class/tmin/core/Application.php";
$app = Application::getApplication();

$router = $app->getRouter();

$router->addRoute(new Route('get', 'api/text', 'acme\todo\text\Text', 'show'));
$router->addRoute(new Route('get', 'api/text/{id}', 'acme\todo\text\Text', 'showItem'));

$app->run();

exit;