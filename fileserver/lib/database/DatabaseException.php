<?php

namespace database;

class DatabaseException extends SfsException
{
	static $errNumber=500;

	public function __construct($msg)
	{
		parent::__construct("DatabaseException: $msg");
	}
}