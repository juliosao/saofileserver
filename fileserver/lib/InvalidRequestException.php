<?php

class InvalidRequestException extends FsoException
{
	static $errNumber=400;

	public function __construct()
	{
		parent::__construct("Invalid request");
	}
}