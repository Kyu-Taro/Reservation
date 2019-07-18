<?php
session_start();

//エラーログの設定
ini_set('log_errors','on');
ini_set('error_log','php.log');

//エラーを格納する配列
$err_msg=[];

//入力時間の1時間半前の時刻が入った定数
const HOURHARF='-90 minute';
const HARF='+30 minute';
const HOUR='+1 hour';
const PULS_HOUR_HARF='+90 minute';

//バリデーションのエラーメッセージ定数
const ERROR1='※全て入力してください';
const ERROR2='※半角数字で入力してください';
const ERROR3='※予約が一杯です';
const ERROR4='※名前の入力は必須です';
const ERROR5='※電話番号を入力してください';
const ERROR6='※電話番号はハイフン抜きで入力してください';

//デバックの関数
$debug_flg=true;
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ:'.$str);
    }
}

//DB接続関数
function getDb(){
    $dbh='mysql:dbname=reservation; host=localhost; charset=utf8';
    $user='root';
    $pass='root';
    $option=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true];
    $db=new PDO($dbh,$user,$pass,$option);

    return $db;
}

//時間を足し算して時間にじて返す関数
function datetime($time,$start){
    $date=strtotime($time,strtotime($start));
    $time=date('H:i',$date);
    return $time;
}

//DB SELECTクエリー実行関数
function queryPost($sql,$data=[]){
    $db=getDB();
    $stt=$db->prepare($sql);
    $stt->execute($data);
    $items=$stt->fetch(PDO::FETCH_ASSOC);
    return $items;
}

//DB SELECTクエリー実行関数２
function queryPost2($sql,$data=[]){
    $db=getDB();
    $stt=$db->prepare($sql);
    $stt->execute($data);
    return $stt;
}


//DB INSERT/UPDATEクエリ実行関数
function queryInsert($sql,$data=[]){
    $db=getDb();
    $stt=$db->prepare($sql);
    $stt->execute($data);
    return $db;
}

//時間毎の使用席数を計算する関数
function timecount($start,$type,$date){
    try {
        $time=datetime(HOURHARF, $start);
        $sql='SELECT sum(use_table) AS use_table FROM reservation WHERE type_id = :type AND start between :time AND :start AND date = :date WHERE delte_flg = 0';
        $arr=[':type'=>$type,':date'=>$date,':start'=>$start,':time'=>$time];
        $items=queryPost($sql, $arr);
        return $items;
    }catch (Exception $e){
        debug('エラー'.$e->getMessage());
    }
}

//エラーメッセージを管理する関数
function erro($key,$str){
    global $err_msg;
    $err_msg[$key]=$str;
}

//全て半角数字かどうかをチェックする関数
function harfNumber($str){
    if(preg_match("/^[0-9]+$/", $str)){
        return false;
    }else{
        return true;
    }
}

//中身がからじゃないかを調べる関数
function required($str){
    if(empty($str)){
        return true;
    }else{
        return false;
    }
}

//電話番号が正しく入力されているか(ハイフンなし)
function numberCheck($str){
    $pata="/^(0{1}\d{9,10})$/";
    if(preg_match($pata,$str)){
        return false;
    }else{
        return true;
    }
}

//セッションを一度だけしようする容易に削除する関数
function session_message($str){
    if (!empty($str)) {
        $session_data=$_SESSION['fls_message'];
        $_SESSION['fls_message']='';
        echo $session_data;
    }
}