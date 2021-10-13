<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.YouDao.php";

abstract class Api{
    public function Call($words){
        $cacheIndex=__DIR__.DIRECTORY_SEPARATOR."Cache".DIRECTORY_SEPARATOR;
        if (file_exists($cacheIndex.$words)){
            return file_get_contents($cacheIndex.$words.".json");
        }else{
            $response=$this->CallApi($words);
            if ($response){
                file_put_contents($cacheIndex.$words.".json",$response);
            }
            return  $response;
        }
    }
    abstract protected function CallApi($words);
}