<?php
namespace Acorn;

/**
 * @package Acorn
 */
class Request
{

	protected static $url;
	protected static $params = array();
	protected static $query;
	protected static $post;

	static function construct($url, $post, $query)
	{
		self::$url = $url;
		self::$post  = self::arrayToObject($post);
		self::$query = self::arrayToObject($query);
	}

	protected static function setParams(Route $route)
	{
		self::$params = self::arrayToObject($route->getMatches());
	}

	/**
	 * <p>A stdClass version of the path parameters</p>
	 * @see \Acorn\Routes::route
	 * @return array params
	 * @package Acorn
	 */
	public static function params()
	{
		return self::$params;
	}

	/**
	 * <p>A stdClass version of the query (GET) data</p>
	 * @return array query/get data
	 * @package Acorn
	 */
	public static function query()
	{
		return self::$query;
	}

	/**
	 * <p>A stdClass version of the POST data</p>
	 * @return array post data
	 * @package Acorn
	 */
	public static function post()
	{
		return self::$post;
	}

	/**
	 * <p>Returns the full request URL</p>
	 * @return string the reuqest url
	 * @package Acorn
	 */
	public static function url()
	{
		return self::$url;
	}

	/**
	 * <p>Map this request to a route using the routign table</p>
	 * @return Route returns the route which matches this request
	 * @see \Acorn\Routes::route
	 * @package Acorn
	 */
	public static function route()
	{
		// Attempt to match our URL to our route
		$route = Routes::routeFor(self::$url);
		if (false === Routes::exists($route))
		{
			// Fall back to the 404 URL
			$route = Routes::routeFor(Routes::get404());
			// Check for 404 existence
			if (false === Routes::exists($route))
				die('Route not found. And no 404 route found.' . PHP_EOL);
		}

		self::setParams($route);
		return $route;
	}

	/**
	 * <p>Converts an n-d array to a ser of nested stdClass objects</p>
	 * @param array $arr the array to convert
	 * @return stdClass the object
	 * @package Acorn
	 */
	private static function arrayToObject($arr)
	{
		if (is_array($arr))
			return (object) array_map('\Acorn\Request::arrayToObject', $arr);
		else
			return $arr;
	}

}

