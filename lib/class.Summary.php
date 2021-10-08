<?php

class Summary extends Base{
    public function WordsSummary(){
        $startTime=date("Y-m-d 00:00:00",strtotime("-31 day"));
        $endTime=date("Y-m-d H:i:s");
        $year=date("Y-");
        $days=self::getDays($startTime,$endTime);
        $returnData=[
            'days'=>$days,
            'new_words'=>[],
            'review_words'=>[],
            'amount_words'=>[],
            'rest_words'=>[]
        ];
        $returnData['amount_words'][-1]=$this->getLastAmountWords($startTime);
        foreach ($days as $index=>$day){
            $newWordsNumber=$this->getNewWords($year.$day." 00:00:00",$year.$day." 23:59:59");
            $returnData['new_words'][]=$newWordsNumber;
            $returnData['review_words'][]=$this->getReviewWords($year." 00:00:00",$year.$day." 23:59:59");
            $returnData['amount_words'][]=$returnData['amount_words'][$index-1]+$newWordsNumber;
            $returnData['rest_words'][]=$returnData['amount_words'][$index]-$returnData['review_words'][$index];
        }
        unset($returnData['amount_words'][-1]);
        return self::returnActionResult($returnData);
    }
    public static function getDays($startTime,$endTime){
        $timeStamp=strtotime($startTime);
        $endTimeStamp=strtotime($endTime);
        $days=[];
        while ($timeStamp<=$endTimeStamp){
            $days[]=date("m-d",$timeStamp);
            $timeStamp+=24*60*60;
        }
        return $days;
    }
    public function getNewWords($startTime,$endTime){
        $sql=sprintf("select count(*) as number from %s where AddTime between '%s' and '%s'",Words::$table,$startTime,$endTime);
        $number=$this->pdo->getFirstRow($sql);
        return $number['number'] ?? 0;
    }
    public function getReviewWords($startTime,$endTime){
        $sql=sprintf("select count(*) as number from %s where check_result='%s' and check_time between '%s' and '%s'",CheckLog::$table,CheckLog::SUCCESS_RESULT,$startTime,$endTime);
        $number=$this->pdo->getFirstRow($sql);
        return $number['number'] ?? 0;
    }
    public function getLastAmountWords($time){
        $sql=sprintf("select count(*) as number from %s where AddTime<'%s'",Words::$table,$time);
        $number=$this->pdo->getFirstRow($sql);
        return $number['number'] ?? 0;
    }
}