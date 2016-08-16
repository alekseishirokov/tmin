<?php

namespace tmin\core;

class ContentType
{
	public static $contentTypes = array(
		"application/json" => "json",
		"application/xml" => "xml", 
		"text/html" => "html", 
		"text/plain" => "html",
		"application/x-www-form-urlencoded" => "html",
		"multipart/form-data" => "html"
	);
}