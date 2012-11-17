<?php
namespace Acorn;

class Acorn
{
	private static $classpath = array();

	/**
	 * <p>Loads classes dynamically</p>
	 * <p>Classes in the namespace acorn are searched for in ACORN_PATH and then
	 * PLUIN_PATH.</p>
	 * @param string $class the class name
	 * @return boolean whether the file was found.
	 */
	public static function loadClass($class)
	{
		foreach (self::$classpath as $classpath)
		{
			$file = $classpath->map($class);

			if (null === $file)
				continue;

			if (file_exists($file))
				require_once $file;

			if (class_exists($class))
				return true;
		}

		return false;
	}

	public static function addClassPath($format, $namespace = '')
	{
		self::$classpath[] = new ClassPath($format, $namespace);
	}

	public static function HTTPStatusMessage($code)
	{
		switch ($code)
		{
			case 100: return 'Continue';
			case 101: return 'Switching Protocols';
			case 102: return 'Processing';
			case 200: return 'OK';
			case 201: return 'Created';
			case 202: return 'Accepted';
			case 203: return 'Non-Authoritative Information';
			case 204: return 'No Content';
			case 205: return 'Reset Content';
			case 206: return 'Partial Content';
			case 207: return 'Multi-Status';
			case 208: return 'Already Reported';
			case 226: return 'IM Used';
			case 300: return 'Multiple Choices';
			case 301: return 'Moved Permanently';
			case 302: return 'Found';
			case 303: return 'See Other';
			case 304: return 'Not Modified';
			case 305: return 'Use Proxy';
			case 306: return 'Switch Proxy';
			case 307: return 'Temporary Redirect';
			case 308: return 'Permanent Redirect';
			case 400: return 'Bad Request';
			case 401: return 'Unauthorized';
			case 402: return 'Payment Required';
			case 403: return 'Forbidden';
			case 404: return 'Not Found';
			case 405: return 'Method Not Allowed';
			case 406: return 'Not Acceptable';
			case 407: return 'Proxy Authentication Required';
			case 408: return 'Request Timeout';
			case 409: return 'Conflict';
			case 410: return 'Gone';
			case 411: return 'Length Required';
			case 412: return 'Precondition Failed';
			case 413: return 'Request Entity Too Large';
			case 414: return 'Request-URI Too Long';
			case 415: return 'Unsupported Media Type';
			case 416: return 'Requested Range Not Satisfiable';
			case 417: return 'Expectation Failed';
			case 418: return 'I\'m a teapot';
			case 420: return 'Enhance Your Calm';
			case 422: return 'Unprocessable Entity';
			case 423: return 'Locked';
			case 424: return 'Failed Dependency';
			case 424: return 'Method Failure';
			case 425: return 'Unordered Collection';
			case 426: return 'Upgrade Required';
			case 428: return 'Precondition Required';
			case 429: return 'Too Many Requests';
			case 431: return 'Request Header Fields Too Large';
			case 451: return 'Unavailable For Legal Reasons';
			case 494: return 'Request Header Too Large';
			case 495: return 'Cert Error';
			case 496: return 'No Cert';
			case 497: return 'HTTP to HTTPS';
			case 499: return 'Client Closed Request';
			case 500: return 'Internal Server Error';
			case 501: return 'Not Implemented';
			case 502: return 'Bad Gateway';
			case 503: return 'Service Unavailable';
			case 504: return 'Gateway Timeout';
			case 505: return 'HTTP Version Not Supported';
			case 506: return 'Variant Also Negotiates';
			case 507: return 'Insufficient Storage';
			case 508: return 'Loop Detected';
			case 509: return 'Bandwidth Limit Exceeded';
			case 510: return 'Not Extended';
			case 511: return 'Network Authentication Required';
			default:  return;
		}
	}

	public static function getErrorTypeName($type)
	{
		switch($type)
		{
			case E_ERROR:				return 'PHP Error';
			case E_WARNING:				return 'PHP Warning';
			case E_PARSE:				return 'Parse Error';
			case E_NOTICE:				return 'PHP Notice';
			case E_CORE_ERROR:			return 'PHP Core Error';
			case E_CORE_WARNING:		return 'PHP Core Warning';
			case E_CORE_ERROR:			return 'Compile Error';
			case E_CORE_WARNING:		return 'Compile Warning';
			case E_USER_ERROR:			return 'User Error';
			case E_USER_WARNING:		return 'User Warning';
			case E_USER_NOTICE:			return 'User Notice';
			case E_STRICT:				return 'Strict Notice';
			case E_RECOVERABLE_ERROR:	return 'Recoverable PHP Error';
			case E_DEPRECATED:			return 'Core Deprecation Warning';
			case E_USER_DEPRECATED:		return 'User Deprecation Warning';
		}
		return '';
	}
}

class ClassPath
{
	protected $namespace;
	protected $format;

	public function __construct($format, $namespace = '')
	{
		if ('\\' === substr($namespace, 0, 1))
			$namespace = substr($namespace, 1);

		$this->format = $format;
		$this->namespace = $namespace;
	}

	public function map($class)
	{
		if ('\\' === substr($class, 0, 1))
			$classpath = substr($class, 1);
		else
			$classpath = $class;

		if ($this->namespace !== substr($class, 0, strlen($this->namespace)))
			return null;

		$classpath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
		$classname = explode(DIRECTORY_SEPARATOR, $classpath);
		$classname = array_pop($classname);
		$relclspth = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($this->namespace)));
		if (empty($relclspth[0]))
			array_shift($relclspth);

		return str_replace(
			array('%q', '%r', '%c'),
			array($classpath, $relclspth, $classname),
			$this->format
		);
	}
}

