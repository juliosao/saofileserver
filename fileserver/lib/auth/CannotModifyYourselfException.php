<?php

namespace auth;

use \SfsException;

class CannotModifyYourselfException extends SfsException
{
	static $errNumber=401;

	public function __construct($msg='')
	{
		if($msg=='')
			parent::__construct("Cannot modify yourself!");
		else
			parent::__construct("Cannot modify yourself: ".$msg);
	}
}