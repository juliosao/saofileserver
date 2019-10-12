<?php

class InvalidRequestException extends FsoException
{
	static $errNumber=400;

	public function __construct($msg="Invalid request")
	{
		parent::__construct($msg);
	}
}