<?php

namespace auth;

use \SfsException;

class GroupNotFoundException extends SfsException
{
	static $errNumber=404;

	public function __construct($usr)
	{
		parent::__construct("Group not found: $usr");
	}
}