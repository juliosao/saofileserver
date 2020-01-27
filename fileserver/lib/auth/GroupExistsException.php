<?php

namespace auth;

use \SfsException;

class GroupExistsException extends SfsException
{
	static $errNumber=404;

	public function __construct($usr)
	{
		parent::__construct("Group exists: $usr");
	}
}