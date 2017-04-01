<?php

require(__DIR__.'/lib/Util.php');
require(__DIR__.'/lib/App.php');
require(__DIR__.'/lib/Mod.php');
require(__DIR__.'/lib/HTMLApp.php');
require(__DIR__.'/lib/JSONApp.php');

try
{
    $tmp=array('main','index');
    $mode='views';

    if (isset($_REQUEST['app']))
    {
        $mode='api';
        $tmp=explode('.',$_REQUEST['app']);
        if(count($tmp)<2)
        {
            $tmp[1]='main';
        }
    }
    else if (isset($_REQUEST['view']))
    {
        $tmp=explode('.',$_REQUEST['view']);        
        if(count($tmp)<2)
        {
            $tmp[1]='index';
        }
    }
    else
    {
        $tmp=array('main','index');
    }

    $path=__DIR__.'/mod/'.$tmp[0].'/'.$mode.'/'.$tmp[1].'.php';
    
    if(!file_exists($path))
    {
        error_die('404',$path);
    }

    require($path);

    if(class_exists($tmp[1]))
    {
        $main=new $tmp[1]();
        $main->run();
    }
}
catch(Exception $ex)
{
    error_die('500',$ex);
}
