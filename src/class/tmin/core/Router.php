<?php

namespace tmin\core;

class Router
{
	private $routes = array();
	private $regexps = array();
	
	function addRoute(Route $route) 
	{
		$method = strtoupper($route->method);
		
		$this->routes[$method][] = $route;
		
		// убираем ведущие и конечные символы /, чтобы не зависеть от них при поиске пути.
		$template = trim($route->template, '/');
		
		// Заменяем все {*varname} в шаблоне на (?P<varname>.*?)
		$pattern = '/\{\*(.+?)\}/';
		$replacement = '(?P<$1>.*?)';
		$template = preg_replace($pattern, $replacement, $template);
		
		// Заменяем все {varname} в шаблоне на (?P<varname>[^/]+?)
		$pattern = '/\{(.+?)\}/';
		$replacement = '(?P<$1>[^/]+?)';
		$template = preg_replace($pattern, $replacement, $template);

		$this->regexps[$method][] = '{^' . $template . '$}';
	}
	
	function getRoute($method, $path)
	{
		if (!array_key_exists($method, $this->routes)) {return null;} // TODO: можно выдавать ошибку: «Неизвестный метод».
		if (!array_key_exists($method, $this->regexps)) {return null;}
		
		$path1 = trim($path, '/');
		$path2 = $path1 . '/';
		
		foreach ($this->regexps[$method] as $key => $regexp) 
		{
			$matches = array();
			if (1 == preg_match($regexp, $path1, $matches))
			{	
				assert(array_key_exists($key, $this->routes[$method]));
				$route = $this->routes[$method][$key];
				$route = $this->bindParams($route, $matches);
				return $route;
			}
			
			$matches = array();
			if (1 == preg_match($regexp, $path2, $matches))
			{	
				assert(array_key_exists($key, $this->routes[$method]));
				$route = $this->routes[$method][$key];
				$route = $this->bindParams($route, $matches);
				return $route;
			}			
		}
		
		return null;
	}
	
	// Делает из описания объекта ссылку, которую можно вставлять в документ.
	// Router->getPath(new Route('get', 'api/feedbacks/{id}', 'Feedback', 'showItem', new array($id=>1)); 
	// ($method, $template, $controller, $action, $params = array())
	// ->getPath('News', 'showItemByAlias', new array(alias=>'lyublyu-svoyu-rabotu'));
	function getPath($controller, $action = 'show', $params = array(), $method = 'get', $context = '')
	{
		
		$pattern = '/\{\*(.+?)\}$/';
		
		foreach ($this->routes[strtoupper($method)] as $route)
		{	
			// если встретили окончание типа {*path}, то спрашиваем ссылку у контроллера.
			if (preg_match($pattern, $route->template))
			{				
				// создаем экземпляр контроллера
				$cn = $route->controller . 'Controller';
				$c = new $cn();
				// спрашиваем путь у его роутера, если он есть.				
				$router = $c->getRouter();
				
				if (isset($router))
				{
					$path = $router->getPath($controller, $action, $params, $method, $context);
					if ($path !== null)
					{
						return preg_replace($pattern, $path, $route->template);
					}
				}
			}
			
			if ($route->controller == $controller 
			    && $route->action == $action
			    && $this->isEqualParams($route->params, $params)
			    && $this->isCanFillWithParams($route->template, array_merge($params, $route->params)) // TODO: Если есть в $route->params, то тоже подходит.
			    /* && $router->params == $params сравниваем значения параметров с совпадающими именами */)
			{
				return $this->fillWithParams($route, $params);
			}
		}
		
		return null;
	}

	/**
	 * Возвращает true, если массив параметров $params2 является подмножеством массива $params1.
	 * Сравниваются значения параметров с одинаковыми названиями.
	 * 
	 * @param type $params1
	 * @param type $params2
	 * @return boolean
	 */
	private function isEqualParams($params1, $params2)
	{
		if (count($params1) == 0) { return true; }
		foreach ($params2 as $name => $value) 
		{
			if (array_key_exists($name, $params1) && $params1[$name] != $value)
			{
				return false;
			}
		}
		return true;
	}
	
	
	private function isCanFillWithParams($template, $params)
	{
		$pattern = '/\{(.+?)\}/';

		$matches = array();
		if (1 == preg_match($pattern, $template, $matches))
		{
			foreach ($matches as $index => $match) 
			{
				if (($index % 2 !== 0) && !array_key_exists($match, $params))
				{
					return false;
				}
			}
		}
		
		return true;
	}

	/* заполняем шаблон значениями параметров. 
	 * Лишние параметры вставляем как ?name=value&name=value 
	 * Если переданных параметров не хватает, то выдаем ошибку. */
	private function fillWithParams($route, $params)
	{
		$query = array();
		$template = $route->template;
		
		foreach ($params as $name => $value) 
		{
			$count = 0;
			// TODO: Это перестанет работать, если параметры будут задаваться не только именем, 
			// а, например, типом или регекспом.
			$template = str_replace("{{$name}}", $value, $template, $count);
			// если не нашли параметр в шаблоне и он не указан в массиве params, то запоминаем его в масссиве переменных.
			if ($count == 0 && !empty($value) && !array_key_exists($name, $route->params))
			{
				$query[] = "$name=" . urlencode($value); 
			}
		}
		
		// добавляем к пути переменные, если они есть
		if (count($query) > 0)
		{
			$template = $template . '?' . implode('&', $query);
		}
		
		return $template;
	}
	
	
	private function bindParams($route, $matches)
	{
		$r = $route;
		
		// PHP 5 comes with a complete reflection API that adds 
		// the ability to reverse-engineer classes, interfaces, functions, 
		// methods and extensions. 
		// Additionally, the reflection API offers ways to retrieve 
		// doc comments for functions, classes and methods.
		
		// берем список параметров у функции, и ищем в пути, в параметрах request, и в теле сообщения подходящие значения.
		// и аполняем ими массив с параметрами.
		

		$action = new \ReflectionMethod($route->controller . 'Controller', $route->action);
		//print_r($action);
		// проходим по всем пармаметрам метода:
		foreach ($action->getParameters() as $param) 
		{
			//print_r($param);
			// если уже есть заполненный параметр, то оставляем его как есть.
			if (array_key_exists($param->name, $route->params))
			{
				continue;
			}


			// если в пути нашли такой параметр, то добавляем его значение в массив параметров.
			if (array_key_exists($param->name, $matches))
			{
				$r->params[$param->name] = $matches[$param->name];
				continue;
			}
			// если параметр есть в массиве Request, то добавляем его из Request.
			if (array_key_exists($param->name, $_REQUEST))
			{
				$r->params[$param->name] = $_REQUEST[$param->name];
				continue;
			}

			// если параметр не простой (с классом), то пытаемся получить его из тела
			// The method returns ReflectionClass object of parameter type class or NULL if none.
			// TODO: ReflectionParameter::getClass() will cause a fatal error (and trigger __autoload) 
			// if the class required by the parameter is not defined. 
			$paramClass = $param->getClass();
			if ($paramClass != null)
			{					
				// создаем экземпляр класса.
				$instance = new $paramClass->name();

				$instance = $this->unserializeFromBody($instance);

				$r->params[$param->name] = $instance;

				continue;
			}

			// если параметр простой, и есть в массиве Request, то добавляем его из Request.
			if (array_key_exists($param->name, $_REQUEST))
			{
				$r->params[$param->name] = $_REQUEST[$param->name];
				continue;

			}


			// TODO: если у параметра указано добавлять его из тела, то добавляем его из тела.
//				if (array_key_exists($param->name, ...))
//				{
//					$route->params[$param->name] = ...;
//					continue;
//				}
			// если нигде не нашли, то добавляем значение по-умолчанию.
			if ($param->isOptional()) 
			{
				$r->params[$param->name] = $param->getDefaultValue();
				continue;
			}

			// Нет значения для параметра. Гененрировать ошибку?
			throw new \Exception('Не хватило параметров.');
		}

		
		return $r;
	}
	
	
	// как узнать, какое тело пришло 'htmlpost' или 'json'?
	private function unserializeFromBody($instance)
	{
		$r = $instance;
		switch ($this->getContentTypeAlias()) 
		{
			case 'json':
				$r = JsonSerializer::unserialize($instance, file_get_contents("php://input"));
				break;
			
			case 'html':
			default:
				$r = PostSerializer::unserialize($instance, $_POST);				
				break;
		}
		
		return $r;
	}
	
	// text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
	private function getContentTypeAlias() 
	{
		$r = "html";
		
		if (!array_key_exists('CONTENT_TYPE', $_SERVER))
		{
			return $r;
		}
		
		$accept = explode(';', $_SERVER['CONTENT_TYPE']);
		$acceptTypes = (count($accept) > 0) ? explode(',', $accept[0]) : array();

		foreach ($acceptTypes as $acceptType) 
		{
			foreach (ContentType::$contentTypes as $key => $value) 
			{
				if ($acceptType == $key)
				{
					return $value;
				}
			}
		}
		
		return $r;
	}
}