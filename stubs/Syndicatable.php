<?php
namespace Acorn;

interface Syndicatable
{
	public function published();
	public function lastUpdated();
	public function title();
	public function authorName();
	public function description();
	public function content();
}

