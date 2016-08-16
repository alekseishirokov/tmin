<?php

namespace tmin\core;

class PostSerializer implements Serializer
{
	public static function serialize($instance, $data) 
	{
		$a = array();
		
		$class = new \ReflectionClass($instance); 
		$properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) 
		{
			$paramName = $property->getName();
			$a[$paramName] = urlencode($instance->$paramName);
		}
		
		$data = implode('&', $a);
		
		return $data;
	}
	
	
	public static function unserialize($instance, $data) 
	{
		$class = new \ReflectionClass($instance); 
		$properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) 
		{
			$paramName = $property->getName();
			if (array_key_exists($paramName, $data))
			{
				$instance->$paramName = $data[$paramName];
			}
		}
		
		return $instance;
	}
}