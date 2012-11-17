<?php
namespace Acorn;

abstract class Renderer
{
	protected $view;

	public function __construct($view)
	{
		$this->view = $view;
	}

	public final function render($indent = 0)
	{
		if (DEBUG)
			$this->indent($this->doRender(), $indent);
		else
			return $this->doRender();
	}

	/**
	 * <p>Idents a string by a given number of tab characters</p>
	 * <p>Indentation can make source code easier to read when debugging,
	 * but will increase the size of a page when it is being downloaded.
	 * Therefore, it is recommended that this function is overriden to
	 * <code>return $str;</code> in production environments</p>
	 */
	protected function indent($str, $amount)
	{
		if (!DEBUG)
			return $str;

		$indent = str_repeat("\t", $amount);
		return $indent . preg_replace("/\n(.)/", PHP_EOL . $indent . '$1', $str);
	}

	abstract protected function doRender();
}

