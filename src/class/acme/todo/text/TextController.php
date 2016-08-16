<?php

namespace acme\todo\text;
use tmin\core;

class TextController extends core\Controller
{
	
	function show()
	{
		$model = new TextModel();
		$items = $model->getList();
				
		if ($items)
		{		
			return new core\Response(core\Status::OK, $items);
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}
	
	
	function showItem($id)
	{
		$model = new TextModel();
		$item = $model->get($id);
				
		if ($item)
		{
			return new core\Response(core\Status::OK, $item);
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}
}