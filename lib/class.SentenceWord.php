<?php

class SentenceWord extends Base{
    public static $table="sentence_word";
    public function updateSentenceWord($sentenceId,$wordIds){
        $sql=sprintf("select Word_ID,ID from %s where Sentence_ID=%d",static::$table,$sentenceId);
        $databaseWordIds=$this->pdo->getRows($sql,'Word_ID');
        $insertWordIds=[];
        foreach ($wordIds as $wordId){
            if (isset($databaseWordIds[$wordId])){
                unset($databaseWordIds[$wordId]);
                continue;
            }
            $insertWordIds[]=$wordId;
        }
        $deleteWordIds=array_keys($databaseWordIds);
        if(!empty($deleteWordIds)){
            $sql=sprintf("delete from %s where Sentence_ID=%d and Word_ID in (%s)",static::$table,$sentenceId,implode(",",$deleteWordIds));
            $this->pdo->query($sql);
        }
        if (!empty($insertWordIds)){
            foreach ($insertWordIds as $insertWordId){
                $sql=sprintf("insert into %s(Word_ID,Sentence_ID) value(%d,%d)",static::$table,$insertWordId,$sentenceId);
                $this->pdo->query($sql);
            }
        }
        return self::returnActionResult([
            'delete'=>$deleteWordIds,
            'insert'=>$insertWordIds
        ]);
    }
    public function getRelatedWords($sentenceId){
        if (!$sentenceId){
            return [];
        }
        $sql=sprintf("select Word_ID from %s where Sentence_ID=%d",static::$table,$sentenceId);
        $rows=$this->pdo->getRows($sql,'Word_ID');
        if (empty($rows)){
            return [];
        }
        $sql=sprintf("select ID,word from %s where ID in (%s)",Words::$table,implode(",",array_keys($rows)));
        return $this->pdo->getRows($sql);
    }
}