<?php

namespace auth;

use \SfsException;

class UserNotFoundException extends SfsException
{
	static $errNumber=404;

	public function __construct($usr)
	{
		parent::__construct("User not found: $usr");
	}
}