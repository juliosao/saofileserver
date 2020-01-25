<?php
namespace filesystem;

use \SfsException;

class FileExistsException extends SfsException
{
	static $errNumber=500;

	public function __construct($path)
	{
		parent::__construct("File exists: $path");
	}
}