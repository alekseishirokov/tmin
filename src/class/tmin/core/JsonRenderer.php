<?php

namespace tmin\core;

class JsonRenderer extends Renderer
{
	public function render($controller, $params) 
	{
		// 1. запускаем контроллер.
		$action = array_key_exists('action', $params) ? $params['action'] : 'show';
		$response = Controller::callController($controller, $action, $params);

		if (!$response) // нет ответа.
		{
			return;
		}

		// 2. рисуем ответ.
		$this->template($response);
	}

	public function renderResponse(Response $response)
	{
		header("HTTP/1.1 " . $response->status);
		header('Content-Type:application/json;charset=UTF-8');
		
		if ($response->status == Status::Unauthorized)
		{
			header("WWW-Authenticate:Basic realm=\"\"");
		}
		
		// TODO: Если status с ошибкой, то выводить страницу с ошибкой 
		// или не выводить содержимого вообще.
		if ($response->status == Status::NotFound || $response->status == Status::BadRequest || $response->status == Status::NoContent || $response->status == Status::Unauthorized)
		{
			return;
		}
		
		$this->template($response);
	}
	
	public function template(Response $response)
	{	
		$tpl = \tmin\Config::$tpl;
		$actionTemplate = isset($response->template) ? $response->template : $response->action;
		
		$controllerPath = $response->controller;
		if (preg_match('/\\\\/', $controllerPath))
		{
			$controllerPath = str_replace('\\', DIRECTORY_SEPARATOR, $controllerPath);
		}
		
		$tplfilename = $_SERVER["DOCUMENT_ROOT"] . "/tpl/{$tpl}/{$controllerPath}/json/{$actionTemplate}.php";
		
		if (file_exists($tplfilename))
		{
			$data = $response->data;
			include $tplfilename;
		}
		else
		{
			echo json_encode($response->data);
		}
	}
}