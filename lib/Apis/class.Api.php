<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.YouDao.php";

abstract class Api{
    abstract public function Call($words);
}