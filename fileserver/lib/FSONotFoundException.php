<?php

class FSONotFoundException extends FsoException
{
	static $errNumber=404;

	public function __construct($path)
	{
		parent::__construct("FSO not found: $path");
	}
}