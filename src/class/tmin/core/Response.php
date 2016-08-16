<?php

namespace tmin\core;

class Response
{
	public $status;
	
	public $data;// = array();
	
	public $controller;
	
	public $action;
	
	public $template;
	
	public function __construct($status, $data = null, $template = null) 
	{
		$this->status = $status;
		$this->data = $data;
		$this->template = $template;
	}
}

abstract class Status
{
	const OK = '200 OK';
	const Created = '201 Created';
	const NoContent = '204 No Content';
	const Unauthorized = '401 Unauthorized';
	const NotFound = '404 Not Found';
	const BadRequest = '400 Bad Request'; // 400 Bad Request. The request could not be understood by the server due to malformed syntax. The client SHOULD NOT repeat the request without modifications.
}