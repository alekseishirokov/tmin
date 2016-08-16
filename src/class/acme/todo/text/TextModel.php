<?php

namespace acme\todo\text;
use tmin\core;

class TextModel extends core\Model
{
	
	function getList()
	{		
		return $this->getData();
	}
	
	function get($id)
	{
		return array_pop(array_filter($this->getData(), function($item) use ($id) {
			return $item->id == $id;
		}));
	}
	
	
	private function getData()
	{
		$r = array();
		
		$t = new TextEntity();
		$t->id = 1;
		$t->title = 'One';
		$t->content = 'First text';

		$t2 = new TextEntity();
		$t2->id = 2;
		$t2->title = 'Two';
		$t2->content = 'Second text';
		
		$r[] = $t;
		$r[] = $t2;
		
		return $r;
	}
}