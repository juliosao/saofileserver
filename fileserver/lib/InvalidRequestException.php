<?php

class InvalidRequestException extends SfsException
{
	static $errNumber=400;

	public function __construct($msg="Invalid request")
	{
		parent::__construct($msg);
	}
}