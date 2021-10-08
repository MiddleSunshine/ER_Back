<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.Base.php";

class Words extends Base{
    public static $table='words';

    public function GetID(){
        $this->post=json_decode($this->post,1);
        $word=$this->post['word'] ?? '';
        $sql=sprintf("select ID from %s where word='{$word}';",static::$table);
        $word=$this->pdo->getFirstRow($sql);
        return self::returnActionResult([
            'ID'=>$word['ID'] ?? 0
        ]);
    }
    public function addNewWord($word){
        static $checkPlan;
        if (!$checkPlan){
            $checkPlan=new CheckPlan();
        }
        if (empty($word)){
            return 0;
        }
        $sql=sprintf("insert into %s(word,AddTime,LastUpdateTime) value('%s','%s','%s')",static::$table,$word,date("Y-m-d H:i:s"),date("Y-m-d H:i:s"));
        $this->pdo->query($sql);
        $sql=sprintf("select ID from %s where word='%s';",static::$table,$word);
        $wordData=$this->pdo->getFirstRow($sql);
        $checkPlan->addCheckPlan($wordData['ID']);
        return $wordData['ID'] ?? 0;
    }
    public function Save(){
        $id=$this->get['id'] ?? 0;
        $fieldMap=[
            'word'=>'',
            'phonetic_transcription'=>'',
            'related_word_1'=>'',
            'related_word_2'=>'',
            'related_word_3'=>'',
            'note'=>'',
            'explain'=>'',
            'AddTime'=>date("Y-m-d H:i:s"),
            'LastUpdateTime'=>date("Y-m-d H:i:s"),
            'source'=>''
        ];
        $sql=[];
        $this->post=json_decode($this->post,1);
        foreach ($this->post as $filed=>$value){
            switch ($filed){
                case "ID":
                    break;
                case 'explain':
                    $filed=sprintf('`%s`',$filed);
                case "LastUpdateTime":
                    $filed=='LastUpdateTime' && $value=date("Y-m-d H:i:s");
                default:
                    $sql[$filed]=addslashes($value ?? $fieldMap[$filed]);
            }
        }
        empty($sql['AddTime']) && $sql['AddTime']=addslashes(date("Y-m-d H:i:s"));
        empty($sql['LastUpdateTime']) && $sql['LastUpdateTime']=addslashes(date("Y-m-d H:i:s"));
        $returnData=$this->handleSql($sql,$id,'word');
        $checkPlan=new CheckPlan();
        $checkPlan->addCheckPlan($returnData['ID']);
        return $returnData;
    }
}