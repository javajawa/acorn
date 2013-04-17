<?php
namespace Acorn;

/**
 * <p>Static class that handles routing or requests to controllers</p>
 * @package Acorn
 */
class Routes
{

	protected static $error404 = '';
	protected static $routes = array();
	protected static $namespace = '\\';

	static function get404()
	{
		return self::$error404;
	}

	/**
	 * <p>Find the correct route for a particular path</p>
	 * @param String $path the path string
	 * @return Route the route found (or null)
	 */
	static function routeFor($path)
	{
		foreach (self::$routes as $route)
			if (true === $route->match($path) && $route->exists())
				return $route;

		return null;
	}

	public static function route($path, $controller, $method, array $params = array())
	{
		self::$routes[] = new Route($path, $controller, $method, $params);
	}

	public static function set404($path)
	{
		self::$error404 = $path;
	}

	public static function setNamespace($namespace)
	{
		if (false === is_string($namespace))
			trigger_error ('Namespace must be a string');

		if ('\\' !== substr($namespace, -1))
			$namespace .= '\\';

		self::$namespace = $namespace;
	}

	public static function getNamespace()
	{
		return self::$namespace;
	}

	public static function exists(Route $route = null)
	{
		return (null !== $route && true === $route->exists());
	}
}

class Route
{
	protected $controller;
	protected $method;
	protected $path;
	protected $params;

	protected $matches = array();

	public function __construct($path, $controller, $method, array $params)
	{
		$this->controller = $controller;
		$this->method = $method;
		$this->path = $this->formatPath($path);
		$this->params = $params;
	}

	public function __get($name)
	{
		if ('controller' === $name)
		{
			if (true === class_exists($this->controller))
				return $this->controller;
			else if (true === class_exists(Routes::getNamespace() . $this->controller))
				return Routes::getNamespace() . $this->controller;
			else
				return null;
		}
		if ('action' === $name)
		{
			if (!empty($this->method))
				return $this->method;

			$matches = $this->getMatches();
			return is_array($matches['method']) ? current($matches['method']) : $matches['method'];
		}

		return isset($this->$name) ? $this->$name : null;
	}

	public function __isset($name)
	{
		return isset($this->$name);
	}

	public function getMatches()
	{
		return array_merge_recursive($this->matches, $this->params);
	}

	protected function formatPath($path)
	{
		// Replace the argument sections of the route's path with regex code
		// that will match them using names matches.
		// ?page has to be converted to (?'page'[^/]+)/
		$path = preg_replace('@/\?([^/]+)/@i',    '/(?\'\1\'[^/]+)/',   $path);
		$path = preg_replace('@/\?([^/]+)$@i',    '/(?\'\1\'[^/]+)',    $path);

		// Allow a trailing slash in the URI
		if ('/' !== substr($path, -1))
			$path .= '/?';
		else
			$path .= '?';

		// We construct the overall regex for this route
		return '@^' . $path . '$@i';
	}

	public function match($path)
	{
		$matches = array();

		if (1 === preg_match($this->path, $path, $matches))
		{
			// Path arguments to not be index numberically
			// and ?... sections (args) to be split into an array
			foreach ($matches as $k => &$v)
				if (is_numeric($k))
					unset($matches[$k]);

			$this->matches = $matches;
			return true;
		}
		return false;
	}

	public function exists()
	{
		if (true === class_exists($this->controller))
			$class = $this->controller;
		else if (true === class_exists(Routes::getNamespace() . $this->controller))
			$class = Routes::getNamespace() . $this->controller;
		else
			return false;

		if (false === method_exists($class, $this->action))
			return false;

		return true;
	}
}

