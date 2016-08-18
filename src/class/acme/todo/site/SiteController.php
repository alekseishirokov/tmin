<?php
namespace acme\todo\site;
use tmin\core;

class SiteController extends core\Controller
{
	
	function getRouter()
	{
		$router = new core\Router;
		
		$router->addRoute(new core\Route('get', 'text/{*path}', 'acme\todo\text\Text', 'route'));
		$router->addRoute(new core\Route('get', '{*path}', 'acme\todo\text\Text', 'route'));
		$router->addRoute(new core\Route('put', 'text/{*path}', 'acme\todo\text\Text', 'route'));
		$router->addRoute(new core\Route('post', 'text/{*path}', 'acme\todo\text\Text', 'route'));
		$router->addRoute(new core\Route('delete', 'text/{*path}', 'acme\todo\text\Text', 'route'));	
		
		return $router;
	}


	function route($path, $context = '')
	{	
		$response = parent::route($path);
		
		// 2. рисуем ответ контроллера.
		$renderer = core\Application::getApplication()->getRenderer();
		$renderer->context = $context;
		
		ob_start();
		$renderer->template($response);
		$content = ob_get_clean();
		
		$data = new \stdClass();
		$data->content = $content;
		$data->response = $response;

		return new core\Response($response->status, $data, 'route');
	}
}