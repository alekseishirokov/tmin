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
	
	function insert(TextEntity $item)
	{
		return $item;
	}
	
	function update(TextEntity $item)
	{
		return $item;
	}
	
	function delete($id)
	{
		return true;
	}
	
	function markDone($id)
	{
		return true;
	}
	
	private function getData()
	{
		$r = array();
		
		$t = new TextEntity();
		$t->id = 1;
		$t->title = 'One';
		$t->date = '2016-10-23';
		$t->content = 'First text';

		$t2 = new TextEntity();
		$t2->id = 2;
		$t2->title = 'Two';
		$t2->date = '2016-10-24';
		$t2->content = 'Second text';
		
		$r[] = $t;
		$r[] = $t2;
		
		return $r;
	}
}