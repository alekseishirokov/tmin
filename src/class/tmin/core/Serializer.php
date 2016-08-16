<?php

namespace tmin\core;

interface Serializer 
{
	/**
	 * Сериализует объект $instance в представление $data
	 * 
	 * @return data
	 */
	public static function serialize($instance, $data);
	
	/**
	 * Десериализует объект $instance из данных $data
	 * 
	 * @return instance 	 
	 */
	public static function unserialize($instance, $data);
}