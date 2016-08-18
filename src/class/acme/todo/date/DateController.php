<?php

namespace acme\todo\date;
use tmin\core;

class DateController extends core\Controller
{
	function edit($date, $name)
	{
		$data = new \stdClass();
		$data->date = date_format(date_create($date), 'Y-m-d');
		$data->dateEdit = date_format(date_create($date), 'd.m.Y');
		$data->name = $name;
		return new core\Response(core\Status::OK, $data);
	}
	
	function show($date)
	{
		$date = date_create($date);
		$date_parts = getdate(date_timestamp_get($date));
		$data = "{$date_parts['mday']} {$this->mnames[$date_parts['mon']]} {$date_parts['year']}";		
		return new core\Response(core\Status::OK, $data);
	}
	
	var $mnames = array(1=>"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентябля", "Октября", "Ноября", "Декабря");
}