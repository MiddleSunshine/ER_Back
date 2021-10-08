<?php

class Check extends Base{
    public function List(){
        $checkPlan=new CheckPlan();
        return self::returnActionResult($checkPlan->UncheckedWords());
    }
}