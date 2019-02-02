<?php

class InvalidRequestException extends FsoException
{
	static $errNumber=405;

	public function __construct()
	{
		parent::__construct("Invalid request");
	}
}