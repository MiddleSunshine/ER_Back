<?php
require_once __DIR__.DIRECTORY_SEPARATOR."config.php";
require_once __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."Apis".DIRECTORY_SEPARATOR."class.YouDao.php";
$base=new Base();

$youdao=new YouDao();
$youdao->Call('hello');
