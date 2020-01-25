<?php

namespace auth;

class UnauthorizedException extends SfsException
{
	static $errNumber=401;

	public function __construct($msg='')
	{
		if($msg=='')
			parent::__construct("Unauthorized");
		else
			parent::__construct("Unauthorized: ".$msg);
	}
}