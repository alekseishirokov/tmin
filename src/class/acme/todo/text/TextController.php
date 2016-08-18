<?php

namespace acme\todo\text;
use tmin\core;

class TextController extends core\Controller
{
	
	function getRouter()
	{
		$router = new core\Router;
		
		$router->addRoute(new core\Route('get', '{id}/edit', 'acme\todo\text\Text', 'editItem'));
		$router->addRoute(new core\Route('get', '{id}/done', 'acme\todo\text\Text', 'done'));
		$router->addRoute(new core\Route('get', 'edit',      'acme\todo\text\Text', 'edit'));
		$router->addRoute(new core\Route('get', 'add',       'acme\todo\text\Text', 'add'));
		$router->addRoute(new core\Route('get', '{id}',      'acme\todo\text\Text', 'showItem'));
		$router->addRoute(new core\Route('get', '',          'acme\todo\text\Text', 'show'));
		$router->addRoute(new core\Route('post', '{id}',     'acme\todo\text\Text', 'put')); // Этот нужен, потому что браузеры не умеют отправлять формы методом put.
		$router->addRoute(new core\Route('post', '',         'acme\todo\text\Text', 'post'));
		$router->addRoute(new core\Route('put', '{id}',      'acme\todo\text\Text', 'put'));
		$router->addRoute(new core\Route('delete', '{id}',   'acme\todo\text\Text', 'delete'));		
		
		return $router;
	}
	
	
	function show()
	{
		$model = new TextModel();
		$items = $model->getList();
				
		if ($items)
		{		
			return new core\Response(core\Status::OK, $items, 'show');
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}
	
	function edit()
	{
		$model = new TextModel();
		$items = $model->getList();
				
		if ($items)
		{		
			return new core\Response(core\Status::OK, $items, 'edit');
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
			return new core\Response(core\Status::OK, $item, 'showItem');
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}

	function editItem($id)
	{
		$model = new TextModel();
		$item = $model->get($id);
				
		if ($item)
		{
			return new core\Response(core\Status::OK, $item, 'editItem');
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}

	function add()
	{
		return new core\Response(core\Status::OK, null, 'add');
	}


	function put(TextEntity $item)
	{
		$model = new TextModel();
		$updatedItem = $model->update($item);
		
		if ($updatedItem)
		{	
			return new core\Response(core\Status::NoContent);
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}


	function post(TextEntity $item)
	{		
		$model = new TextModel();
		$insertedItem = $model->insert($item);
		
		if ($insertedItem)
		{			
			return new core\Response(core\Status::Created, $item, 'editItem');
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}
	
	function delete($id)
	{		
		$model = new TextModel();
		if ($model->delete($id))
		{
			return new core\Response(core\Status::OK);
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}
	}


	function done($id)
	{
		$model = new TextModel();
		if ($model->markDone($id))
		{
			return new core\Response(core\Status::OK);
		}
		else
		{
			return new core\Response(core\Status::NotFound);
		}		
	}
}