<?php

namespace tmin\core;

class Controller
{
	public static function callController($controller, $action, $params)
	{
		$controllerName = $controller . 'Controller';

		$handler = array($controllerName, $action);
		if (!is_callable($handler))
		{
			// нет такого дейстаия у контроллера.
			return;
		}
		
		$methodParams = self::bindParams($controller, $action, $params);
		
		// Получаем объект типа Response, который содержит все необходимое для формирования ответа.
		$controllerInstance = new $controllerName();
		$response = call_user_func_array(array($controllerInstance, $action), $methodParams);
		$response->controller = $controller;
		$response->action = $action;
		return $response;
	}
	
	
	protected static function bindParams($controller, $action, $params)
	{
		$r = array();
		
		$method = new \ReflectionMethod($controller . 'Controller', $action);
		foreach ($method->getParameters() as $param) 
		{
			// если нашли параметр, то добавляем его значение в массив параметров.
			if (array_key_exists($param->name, $params))
			{
				$r[$param->name] = $params[$param->name];
				continue;
			}

			// если не нашли, то добавляем значение по-умолчанию.
			if ($param->isOptional()) 
			{
				$r[$param->name] = $param->getDefaultValue();
				continue;
			}

			// Нет значения для параметра. Гененрировать ошибку?
			throw new \Exception('Не хватило параметров.');
		}
		
		return $r;
	}

	
	function getRouter() { return new Router(); }

	
	function route($path = null)
	{
		$router = $this->getRouter();
		
		$route = null;
		try
		{
			$route = $router->getRoute($_SERVER['REQUEST_METHOD'], $path);
		}
		catch (Exception $e)
		{
			echo 'Route error: ' . $e->getMessage();
		}
		
		if (!$route)
		{
			return new Response(Status::BadRequest);
		}		
		
		return Controller::callController($route->controller, $route->action, $route->params);
	}
}