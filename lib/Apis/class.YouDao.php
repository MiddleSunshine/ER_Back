<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.Api.php";

class YouDao extends Api {
    protected $id="";
    protected $key="";
    protected function CallApi($word)
    {
        static $http;
        if (!$http){
            $http=new HttpCrawler();
        }
        $salt='ER';
        $currentTimestamp=time();
        if (strlen($word)>20){
            $input=substr($word,0,10).strlen($word).substr($word,10);
        }else{
            $input=$word;
        }
        $sign=hash('sha256',$this->id.$input.$salt.$currentTimestamp.$this->key);
        $response=$http->GetHttpResult(
            "https://openapi.youdao.com/v2/dict?".http_build_query([
                'q'=>$word,
                'langType'=>'en',
                'appKey'=>$this->key,
                'dicts'=>'en',
                'salt'=>$salt,
                'sign'=>$sign,
                'signType'=>'v2',
                'curtime'=>$currentTimestamp,
                'docType'=>'json'
            ])
        );
        return $response['content'];
    }
}