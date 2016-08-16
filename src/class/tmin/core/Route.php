<?php

namespace tmin\core;

class Route
{
	public $template = '';
	public $method = '';
	public $controller = '';
	public $action = '';
	public $params = array();
	
	function __construct($method, $template, $controller, $action, $params = array()) 
	{
		$this->method = $method;
		$this->template = $template;
		$this->controller = $controller;
		$this->action = $action;
		$this->params = $params;
	}
	
}