<?php

namespace tmin\core;

class HtmlRenderer extends Renderer
{
	public $context = '';
	
	public function render($controller, $params) 
	{
		// 1. запускаем контроллер.
		$action = array_key_exists('action', $params) ? $params['action'] : 'show';
		
		$response = Controller::callController($controller, $action, $params);

		if (!$response) // нет ответа.
		{
			return;
		}
		
		// 2. рисуем ответ контроллера.
		$this->template($response); // renderResponse?
	}
	
	public function renderResponse(Response $response) // renderAsDocument?
	{
		header("HTTP/1.1 " . $response->status);
		header('Content-Type:text/html;charset=UTF-8');
		
		// Important: The URL loading system classes do not call their delegates 
		// to handle request challenges unless the server response contains a 
		// WWW-Authenticate header.
		if ($response->status == Status::Unauthorized)
		{
			header("WWW-Authenticate:Basic realm=\"\"");
		}
		
		// TODO: Если status с ошибкой, то выводить страницу с ошибкой 
		// или не выводить содержимого вообще.
		if (
			  $response->status == Status::NotFound 
			  || $response->status == Status::BadRequest 
			  || $response->status == Status::NoContent 
			  || $response->status == Status::Unauthorized)
		{
			return;
		}
		
		ob_start();
		$this->template($response);
		$content = ob_get_clean();
		$content = preg_replace('/<\/head>/i', $this->getCss() . $this->getJs() . '</head>', $content, 1);

		echo $content;
	}
	
	public function template(Response $response) // renderResponse
	{		
		$tpl = \tmin\Config::$tpl;
		$actionTemplate = isset($response->template) ? $response->template : $response->action;
		$controllerPath = $response->controller;
		if (preg_match('/\\\\/', $controllerPath))
		{
			$controllerPath = str_replace('\\', DIRECTORY_SEPARATOR, $controllerPath);
		}
		
		$tplfilename = $_SERVER["DOCUMENT_ROOT"] . "/tpl/{$tpl}/{$controllerPath}/html/{$actionTemplate}.php";
				
		if (file_exists($tplfilename))
		{
			$data = $response->data;
			include $tplfilename;
		}
		else
		{
			echo '<pre>'; print_r($response->data); echo '</pre>';
		}
	}
	
	private $_js = array();     // переменная для хранения названий яваскриптов, которые нужно добавить к странице.
	private $_css = array();    // переменная для хранения названий файлов css, которые нужно добавить к странице.


	/**
	 *	Возвращает тэги яваскриптов для вставки в html.
	 */
	function getJs()
	{	
		$r = '';
		
		foreach ($this->_js as $j)
		{
			$r .= empty($j) ? "" : "<script type='text/javascript' src='{$j}'></script>";
		}
		
		return $r;
	}
	
	
	/**
	 *	Добавляет яваскрипт к списку яваскриптов.
	 */
	function js($filename)
	{	
		// не добавляем дубликаты.
		if (!isset($this->_js[$filename]))
		{
			$this->_js[$filename] = $filename;
		}
	}

	
	/**
	 *	Возвращает тэги css для вставки в html.
	 */
	function getCss()
	{	
		$r = '';
		
		foreach ($this->_css as $s)
		{
			$r .= empty($s) ? "" : "<link type='text/css' href='{$s}' rel='stylesheet'>";
		}
		
		return $r;
	}
	
	
	/**
	 *	Добавляет css-файл к списку css-файлов.
	 */
	function css($filename)
	{		
		// не добавляем дубликаты.
		if (!isset($this->_css[$filename]))
		{
			$this->_css[$filename] = $filename;
		}
	}
	
	
	public function linkOn($controller, $action = 'show', $params = array(), $method = 'get', $context = '')
	{
		$context = !empty($context) ? $context : $this->context;
		
		$router = $this->getRouterByContext($context, $method);
		if (isset($router))
		{
			$path = $router->getPath($controller, $action, $params, $method, $context);
			
			if (!empty($path))
			{
				return (empty($context) ? '/' : $context) . $path;
			}
		}
		
		return ' link on '. $controller . ' ' . $action . ' method ' . $method . ' params ' . print_r($params, true) . ' context ' . $context;
	}
	
	
	function getRouterByContext($context, $method)
	{
		$app = Application::getApplication();
		
		if (empty($context))
		{
			return $app->getRouter();
		}
		
		$route = $app->getRouter()->getRoute(strtoupper($method), $context);
		//print_r($app->getRouter());
		if (isset ($route))
		{
			$controller = $route->controller;
			
			$controllerName = $controller . 'Controller';
			$c = new $controllerName();
			
			return $c->getRouter();
		}
		
		return null;
	}
}