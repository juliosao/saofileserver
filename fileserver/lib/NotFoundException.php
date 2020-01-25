<?php
namespace filesystem;

use \SfsException;

class NotFoundException extends FileSystemException
{
	static $errNumber=404;

	public function __construct($path)
	{
		parent::__construct("Not found: $path");
	}
}