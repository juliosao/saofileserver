<?php

function error_die($err=404,$msg='')
{
    include (__DIR__.'/../views/error'.$err.'.php');     
}
