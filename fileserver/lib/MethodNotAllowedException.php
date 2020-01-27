<?php

class MethodNotAllowedException extends SfsException
{
	static $errNumber=405;

	public function __construct()
	{
		parent::__construct("Method not allowed");
	}
}