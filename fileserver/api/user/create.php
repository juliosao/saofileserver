<?php

require('../../lib/Util.php');


class createUser extends app\JSONApp
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function main()
    {
		$name=getParam('name');
		$name=getParam('mail');
		$pw=getParam('pw');
        $pw2=getParam('pw2');
	}
}