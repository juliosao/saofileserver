<?php
require_once('lib/Util.php');

try
{
    database\Database::getInstance();
    $main = app\App::getAppURL().Cfg::get()->app->main;
    header('Location: '.$main,true,302);    
}
catch(Exception $ex)
{
    $main = app\App::getAppURL().Cfg::get()->app->setup;
    header('Location: '.$main,true,302);
}
