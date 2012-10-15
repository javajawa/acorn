<?php
namespace Acorn;

/**
 * @package Acorn
 */
abstract class Controller
{

	protected $params;
	protected $query;
	protected $post;

	protected function __construct()
	{
		$this->params = Request::params();
		$this->query  = Request::query();
		$this->post   = Request::post();
	}

	public abstract function before();

	public abstract function after();

	public function handleException(\Exception $ex)
	{
		die((string)$ex);
	}

	public function handleError(array $err)
	{
		if (!(error_reporting() & $err['type']))
			return true;

		printf('<div class="error"><h2>%s</h2><p>%s</p>', Acorn::getErrorTypeName($err['type']), $err['message']);

		printf('</div>');

		return true;
	}

	public function stackframe($file, $line, $context = 3)
	{
		if (false === file_exists($file))
		{
			echo '<div class="stackframe"><h4>Non-File call</h4></div>';
			return;
		}
		$fileContent = file($file);
		$fileContent = array_splice($fileContent, $line-$context-1, $context+$context+1);

		echo '<div class="stackframe"><p><code>' .$file .' Line '.$line.'</code></p><code class="stackframe-code">';

		foreach ($fileContent as $currline => $theline)
		{
			$theline = highlight_string('<?php ' . $theline, true);
			$count = 1;
			$theline = str_replace('&lt;?php&nbsp;', '', $theline, $count);

			$theline = str_replace(array('<code>', '</code>'), '', $theline);
			$theline = str_pad($line-$context+$currline, 4, '0', STR_PAD_LEFT) . ' | ' . $theline;

			if ($currline === $context)
				echo '<div style="background: #ee0;">' . $theline . '</div>';
			else
				echo $theline;
		}

		echo PHP_EOL . '</code></div>';
	}

}

