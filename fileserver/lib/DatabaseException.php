<?php

class DatabaseException extends FsoException
{
	static $errNumber=500;

	public function __construct($msg)
	{
		parent::__construct("DatabaseException: $msg");
	}
}