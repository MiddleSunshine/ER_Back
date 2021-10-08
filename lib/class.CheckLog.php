<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.Base.php";

class CheckLog extends Base{
    public static $table="check_log";
    const SUCCESS_RESULT='success';
    const FAIL_RESULT='fail';

    public function AddCheckLog(){
        $this->post=json_decode($this->post,1);
        $sql=sprintf("insert into %s(Word_ID,check_result,check_time) value(%d,'%s','%s');",static::$table,$this->post['Word_ID'],$this->post['check_result'],date("Y-m-d H:i:s"));
        $this->pdo->query($sql);
        if ($this->post['check_result']==self::SUCCESS_RESULT){
            $checkPlan=new CheckPlan();
            $checkPlan->updateNextCheckTime($this->post['Word_ID']);
        }
        return self::returnActionResult([
           'sql'=>$sql
        ]);
    }
}