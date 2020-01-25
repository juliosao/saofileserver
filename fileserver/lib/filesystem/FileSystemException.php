<?php
namespace filesystem;

use \SfsException;

class FileSystemException extends SfsException
{
	static $errNumber=500;

	public function __construct($path)
	{
		$err=error_get_last(); 
		error_clear_last();
		if($err!==null)
		{
			parent::construct($err['message'].' at '.$path);
			return;
		}
		parent::__construct("FileSystemObject Error at $path");
	}
}