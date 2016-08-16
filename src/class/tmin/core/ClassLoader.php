<?php

namespace tmin\core;

class ClassLoader
{
	static function Load($className)
	{		
		if (preg_match('/\\\\/', $className))
		{
			$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
		}
		
		$className = $_SERVER["DOCUMENT_ROOT"] . '/class/' . $className . '.php';
		
		if (file_exists($className))
		{
			require_once($className);
		}
	}
}