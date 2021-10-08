<?php

class CheckPlan extends Base{
    public static $table="check_plan";
    const ONE_WEEK='1 week';// 复习频率：1周
    const ONE_MONTH='1 month';// 1个月
    const ONE_YEAR='1 year';// 1年

    public function UncheckedWords(){
        $sql=sprintf("select Word_ID from %s where next_check_time<='%s'",static::$table,date("Y-m-d 23:59:59"));
        $wordIds=$this->pdo->getRows($sql,'Word_ID');
        if (empty($wordIds)){
            return [];
        }
        $sql=sprintf("select * from %s where ID in (%s) order by ID desc",Words::$table,implode(",",array_keys($wordIds)));
        return $this->pdo->getRows($sql);
    }

    /**
     * check plan 的增删改查
     */

    public function addCheckPlan($wordId,$checkFrequency=self::ONE_WEEK){
        $nextCheckTime=date("Y-m-d H:i:s",strtotime("+{$checkFrequency}"));
        $sql=sprintf("insert into %s(Word_ID,check_frequency,next_check_time) value(%d,'%s','%s')",static::$table,$wordId,$checkFrequency,$nextCheckTime);
        return $this->pdo->query($sql);
    }
    public function updateNextCheckTime($wordId){
        $sql=sprintf("select * from %s where Word_ID=%d",self::$table,$wordId);
        $checkPlan=$this->pdo->getFirstRow($sql);
        if (empty($checkPlan)){
            return $this->addCheckPlan($wordId);
        }
        $nextCheckTime=date("Y-m-d H:i:s",strtotime("+{$checkPlan['check_frequency']}"));
        $sql=sprintf("update %s set next_check_time='%s' where Word_ID=%d;",static::$table,$nextCheckTime,$wordId);
        return $this->pdo->query($sql);
    }
    public function updateCheckFrequency($wordId,$checkFrequency=self::ONE_MONTH){
        $sql=sprintf("select * from %s where Word_ID=%d",self::$table,$wordId);
        $checkPlan=$this->pdo->getFirstRow($sql);
        if (empty($checkPlan)){
            return $this->addCheckPlan($wordId,$checkFrequency);
        }
        $sql=sprintf("update %s set check_frequency='%s' where Word_ID=%d;",static::$table,$checkFrequency,$wordId);
        return $this->pdo->query($sql);
    }
}