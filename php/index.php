<?php

//関数ファイルの読み込み
require_once('function.php');

//POSTされていた場合の処理
if (!empty($_POST)) {
    //フォームに空がないかのチェック
    if (!empty($_POST['day']) && !empty($_POST['start']) && !empty($_POST['fin']) && !empty($_POST['type']) && !empty($_POST['number'])) {

        //POSTされたデータ
        $date=$_POST['day'];
        $start=$_POST['start'];
        $fin=$_POST['fin'];
        $type=$_POST['type'];
        $number=(int)$_POST['number'];
    }else{
        erro('top',ERROR1);
    }

    //人数が半角数字で入力されているかをチェック
    if(empty($err_msg)){
        if(harfNumber($number)){
            erro('number',ERROR2);
        }
    }

    //ここまでのバリデーションで問題なければDB接続
    if (empty($err_msg)) {
        try {
            //入力された席タイプの席数を所得(デフォルトの数)
            $sql1='SELECT * FROM shop WHERE id = :id';
            $arr1=[':id'=>$type];
            $items1=queryPost($sql1, $arr1);
            $sheet=$items1['number'];

            //入力された人数で必要テーブル数を所得する
            if ($type == 1 || $type == 2) {
                $ans=round($number/4);
            } else {
                $ans=$number;
            }

            //指定され時間と席タイプから予約可能な席の数を計算する1回目の処理
            $items=timecount($start, $type, $date);
            debug('1回目の処理'.print_r($items, true));

            //2回目の処理
            $items2=timecount(datetime(HARF, $start), $type, $date);
            debug('2回目の処理'.print_r($items2, true));

            //３回目の処理
            $items3=timecount(datetime(HOUR, $start), $type, $date);
            debug('3回目の処理'.print_r($items3, true));

            //最後の処理
            $items4=timecount(datetime(PULS_HOUR_HARF, $start), $type, $date);
            debug('4回目の処理'.print_r($items4, true));

            if (($items['use_table'] + $ans) <= $sheet && ($items2['use_table'] + $ans) <= $sheet && ($items3['use_table'] + $ans) <= $sheet && ($items4['use_table'] + $ans) <= $sheet) {
                //全ての時間帯確認してOKだった場合
                //セッションにデータを格納
                $_SESSION['day']=$date;
                $_SESSION['start']=$start;
                $_SESSION['fin']=$fin;
                $_SESSION['type']=$type;
                $_SESSION['number']=$number;
                $_SESSION['ans']=$ans;

                header('Location:register.php');
            } else {
                //埋まってた場合
                erro('top',ERROR3);
            }
        } catch (exception $e) {
            debug('エラーメッセージ'.$e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <title>予約管理画面</title>
</head>
<body>
    <div id="js-show"><?php session_message($_SESSION['fls_message'])?></div>
    <?php include('../html/header.html') ?>
    <section class="main site-width">
        <form action="../php/index.php" method="POST">
        <h2 class="error"><?php if(!empty($err_msg['top'])) echo $err_msg['top'] ?></h2>
            予約日:<span class="error"></span><br/>
            <input type="date" name="day" value="<?php if(!empty($_POST['day'])) echo $_POST['day'] ?>"><br/>
            開始時間:<span class="error"></span><br/>
            <input type="time" name="start" value="<?php if(!empty($_POST['start'])) echo $_POST['start'] ?>"><br/>
            終了時間:<span class="error"></span><br/>
            <input type="time" name="fin" value="<?php if(!empty($_POST['fin'])) echo $_POST['fin'] ?>"><br/>
            席タイプ:<span class="error"></span><br/>
            <select name="type" value="<?php if(!empty($_POST['type'])) echo $_POST['type'] ?>">
                <option vaue="0">選択してください</option>
                <option value="1">禁煙テーブル</option>
                <option value="2">喫煙テーブル席</option>
                <option value="3">禁煙カウンター</option>
                <option value="4">喫煙カウンター</option>
            </select><br/>
            人数:<span class="error"><?php if(!empty($err_msg['number'])) echo $err_msg['number']?></span><br/>
            <input type="text" name="number" value="<?php if(!empty($_POST['number'])) echo $_POST['number'] ?>"><br/>
            <input type="submit" value="確認">
        </form>
    </section>
    <?php include('../html/footer.html') ?>
</body>
</html>