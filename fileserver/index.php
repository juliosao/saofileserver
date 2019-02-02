<?php
require_once('lib/Util.php');

try
{
    Database::getInstance();
    $main = App::getAppURL().Cfg::get()->app->main;
    header('Location: '.$main,true,302);    
}
catch(Exception $ex)
{
    $main = App::getAppURL().Cfg::get()->app->setup;
    header('Location: '.$main,true,302);
}
