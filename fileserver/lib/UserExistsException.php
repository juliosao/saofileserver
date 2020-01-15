<?php

class UserExistsException extends FsoException
{
	static $errNumber=404;

	public function __construct($usr)
	{
		parent::__construct("User exists: $usr");
	}
}