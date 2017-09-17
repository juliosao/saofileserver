<?php
require_once('lib/Util.php');
$main = App::getAppURL().Cfg::get()->app->main;
header('Location: '.$main,true,302);
