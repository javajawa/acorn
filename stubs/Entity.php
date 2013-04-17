<?php
namespace Acorn;

abstract class Entity
{
	public function __get($name)
	{
		if (property_exists($this, $name))
		{
			return $this->$name;
		}
		throw new \Exception(sprintf('Property %s does not exist in Entity %s', $name, get_class($this)));
	}

	public function __set($name, $value)
	{
		if (property_exists($this, $name))
		{
			$this->$name = $value;
			return $this;
		}
		throw new \Exception(sprintf('Property %s does not exist in Entity %s', $name, get_class($this)));
	}

	public function __isset($name)
	{
		return property_exists($this, $name);
	}

	public function __unset($name)
	{
		if (property_exists($this, $name))
			$this->$name = null;
	}
}

