<?php

namespace app;

use \SfsException;

class InvalidBundleException extends SfsException
{
	static $errNumber=400;

	public function __construct($bundle)
	{
		parent::__construct('Invalid bundle:'.$bundle);
	}
}