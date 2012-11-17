<?php
namespace Acorn;

interface Syndicatable
{
	public function published();
	public function lastUpdated();
	public function title();
	public function authorName();
	public function description($view);
	public function content($view);
}

