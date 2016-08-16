<?php

namespace tmin\core;

class JsonSerializer implements Serializer
{
	public static function serialize($instance, $data) 
	{
		$data = json_encode($instance);
		
		return $data;
	}
	
	// data {"id":"1","date":"2016-05-18 09:28:01","author":null,"content":"first comment","filename":null,"is_published":"0"}
	public static function unserialize($instance, $data) 
	{
		$assoc = true;
		$a = json_decode($data, $assoc);
		$class = new \ReflectionClass($instance);
		$properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) 
		{
			$paramName = $property->getName();
			if (array_key_exists($paramName, $a))
			{
				$instance->$paramName = urldecode($a[$paramName]);
			}
		}
		
		return $instance;
	}
}