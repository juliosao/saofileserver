<?php
require_once('lib/Util.php');
$main = app\App::getAppURL().Cfg::get()->app->main;
header('Location: '.$main,true,302);
