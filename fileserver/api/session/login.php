<?php
require_once('../../lib/Util.php');

class login extends App
{
    public function __construct()
    {
        parent::__construct();
    }

    function run()
    {
        if(!isset($_REQUEST['usr']) || !isset($_REQUEST['pw']))
        {
            //header('HTTP/1.1 301 Moved Permanently');
            $path= isset($_REQUEST['p']) ? App::getAppURL()+'/login/login.php?p='.$_REQUEST['p'] : App::getAppURL().'views/login/login.php';
            header('Location: '.$path,true,302);
            //echo $path;
            exit();
        }

        $usr=Auth::checkPassw($_REQUEST['usr'],$_REQUEST['pw']);

        if($usr)
        {
            Auth::set($usr);
            //header('HTTP/1.1 301 Moved Permanently');
            $path= isset($_REQUEST['p']) ? $_REQUEST['p'] : App::getAppURL().'/views/main';
            //echo $path;
            header('Location: '.$path,true,302);
        }
        else
        {
            //header('HTTP/1.1 301 Moved Permanently');
            $path= isset($_REQUEST['p']) ? App::getAppURL()+'/login/login.php?p='.$_REQUEST['p'] : App::getAppURL().'views/login/login.php';
            //echo $path;
            header('Location: '.$path,true,302);
        }
        exit();
    }
}

$l=new login();
$l->run();