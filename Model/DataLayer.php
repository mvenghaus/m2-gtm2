<?php

namespace Inkl\GoogleTagManager\Model;

class DataLayer
{
	private $dataLayer = [];

	public function getAll()
	{
		return $this->dataLayer;
	}

	public function add($name, $value, $index = null)
	{
		if ($index)
		{
			$this->dataLayer[$index] = [$name => $value];
		} else
		{
			$this->dataLayer[] = [$name => $value];
		}

		return $this;
	}

	public function remove($index)
	{
		unset($this->dataLayer[$index]);

		return $this;
	}

	public function clear()
	{
		$this->dataLayer = [];

		return $this;
	}

}