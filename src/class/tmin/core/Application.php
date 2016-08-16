<?php

namespace tmin\core;

class Application
{
	public static function getApplication()
	{
		if (!isset(self::$_application))
		{
			require_once $_SERVER["DOCUMENT_ROOT"] . '/class/tmin/core/ClassLoader.php';
			spl_autoload_register('tmin\core\ClassLoader::Load');
		
			self::$_application = new Application();
		}
		
		return self::$_application;
	}
	
	
	public function getAuthorizer()
	{
		if (isset($this->_authorizer))
		{
			return $this->_authorizer;
		}
		
		// TODO: Смотреть в конфиге, какой авторизатор исползовать.
		// Пока используем только Basic.
		//require_once $_SERVER["DOCUMENT_ROOT"] . '/api/class/tmin/BasicAuthorizer.php';
		//$this->_authorizer = new BasicAuthorizer();
		
		return $this->_authorizer;
	}
	
	
	public function getRouter()
	{
		if (isset($this->_router))
		{
			return $this->_router;
		}
		
		$this->_router = new Router();

//		// TODO: Если сделать различие по типу параметра, то два последних можно будет делать так:
//		//$router->addRoute(new Route('get', 'api/news/{id:int}', 'News', 'showItem'));
//		//$router->addRoute(new Route('get', 'api/news/{alias:string}', 'News', 'showItemByAlias'));

		return $this->_router;
	}
		
	
	// узнаем, в каком виде выдавать ответ.
	public function getRenderer()
	{
		if (isset($this->_renderer))
		{
			return $this->_renderer;
		}
		
		switch ($this->getAcceptAlias()) 
		{
			case 'json':
				$this->_renderer = new JsonRenderer();
				break;

			case 'html':
			default:
				$this->_renderer = new HtmlRenderer();
				break;
		}
		
		return $this->_renderer;
	}

	
	public function run()
	{			
		
		try
		{
			$route = $this->getRouter()->getRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REDIRECT_URL']);
		}
		catch (\Exception $e)
		{
			$route = null;
			echo 'Route error: ' . $e->getMessage();
		}
				
		// не у всех же контроллеров должен быть рендер.
		$renderer = $this->getRenderer();
		
		if ($route)
		{
			$handler = array($route->controller . 'Controller', $route->action);
			if (is_callable($handler))
			{
				$response = Controller::callController($route->controller, $route->action, $route->params);
				if ($response)
				{

					// Выдаем ответ. В зависимости от заголовка «Accept», ответ формируется в нужном виде.
					// TODO: Вот это присваивание не сильно нравится. 
					// Оно нужно только чтобы обратиться в правильный каталог за шаблоном.
					$response->controller = $route->controller;
					$response->action = $route->action;

					$renderer->renderResponse($response);
				}
				else
				{
					$renderer->renderResponse(new Response(Status::NotFound));
				}
			}
			else
			{
				$renderer->renderResponse(new Response(Status::NotFound));
			}
		}
		else
		{
			// 404 
			// echo 'No such route.';
			$renderer->renderResponse(new Response(Status::BadRequest));
		}
	}

	
	// text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
	private function getAcceptAlias() 
	{
		
		$r = "html";
		
		if (!array_key_exists('HTTP_ACCEPT', $_SERVER))
		{
			return $r;
		}
		
		$accept = explode(';', $_SERVER['HTTP_ACCEPT']);
		$acceptTypes = (count($accept) > 0) ? explode(',', $accept[0]) : array();

		foreach ($acceptTypes as $acceptType) 
		{
			foreach (ContentType::$contentTypes as $key => $value) 
			{
				if ($acceptType == $key)
				{
					return $value;
				}
			}
		}
		
		return $r;
	}
	
	
	private static $_application = null;
	private $_router = null;
	private $_authorizer = null;
	private $_renderer = null;
}