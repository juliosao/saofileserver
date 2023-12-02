<?php

class NotFoundException extends SfsException
{
	static $errNumber=404;

	public function __construct($path)
	{
		parent::__construct("Not found: $path");
	}
}