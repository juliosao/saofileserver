<?php

namespace auth;

use \SfsException;

class UserExistsException extends SfsException
{
	static $errNumber=404;

	public function __construct($usr)
	{
		parent::__construct("User exists: $usr");
	}
}