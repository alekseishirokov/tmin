<?php

namespace tmin\core;

abstract class Renderer 
{
	abstract public function render($controller, $params);
	abstract public function renderResponse(Response $response);	
}