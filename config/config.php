<?php

// 允许跨域
header("Access-Control-Allow-Origin: *");

define("PROD_DB_NAME","ER");
define("PROD_DB_HOST","172.19.0.8");
define("PROD_DB_USER","root");
define("PROD_DB_PASS","1234qwer");
define("PROD_DB_SOCKET","");
define("DEBUG_MODE",true);
define("MYSQL_SET_NAMES","utf8");
define("INDEX_FILE",dirname(__DIR__));

function __autoload($class){
    $fileName=INDEX_FILE.DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."class.".$class.".php";
    if (file_exists($fileName)){
        require_once $fileName;
    }else{
        echo $fileName.PHP_EOL;
        echo "not exists".PHP_EOL;
        exit();
    }
}