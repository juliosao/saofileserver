<?php

class UnauthorizedException extends FsoException
{
	static $errNumber=401;

	public function __construct()
	{
		parent::__construct("Unauthorized");		
	}
}