<?php
    require_once('function.php');
    
    //セッションが存在した場合(前画面で入力してこの画面にきた場合)
    if(!empty($_SESSION)){
        //セッションを変数に格納
        $day=$_SESSION['day'];
        $start=$_SESSION['start'];
        $fin=$_SESSION['fin'];
        $type=$_SESSION['type'];
        $number=$_SESSION['number'];
        $ans=$_SESSION['ans'];

    }else{
        header('Location:index.php');
    }

    //POST送信された場合(予約情報全て送信されて登録する処理)
    if(!empty($_POST)){

        // 変数に送信された値を格納
        $day=$_POST['day'];
        $start=$_POST['start'];
        $fin=$_POST['fin'];
        $type=$_POST['type'];
        $number=$_POST['number'];
        $name=$_POST['name'];
        $tell=$_POST['tell'];
        $ans=$_SESSION['ans'];
        $text=$_POST['text'];
        $reservation_date=date("Y/m/d H:i:s");
        
        //名前が入力されているかのチェック
        if(empty($err_msg)){
            if(required($name)){
                erro('name',ERROR4);
            }

            //電話番号が入力されているかをチェック
            if(required($tell)){
                erro('tell',ERROR5);
            }
        }

        //電話番号がハイフンなしで入力されているかをチェック
        if(empty($err_msg)){
            if (numberCheck($tell)) {
                erro('tell', ERROR6);
            }
        }

        //ここまでのバリデーションチェックで問題がなければ下記を実行
        if(empty($err_msg)){
            try{
                //入力された電話番号がすでに顧客情報として登録されていないか確認
                $sql='SELECT * FROM users WHERE tell = :tell AND name = :name';
                $data=[':tell'=>$tell,':name'=>$name];
                $items=queryPost($sql,$data);

                //すでに登録があった場合
                if($items){
                    $id=$items['id'];
                    $sql='INSERT INTO reservation(user_id,date,start,fin,number,use_table,type_id,text,reservation_date) VALUES(:user_id,:date,:start,:fin,:number,:use_table,:type_id,:text,:reservation_date)';
                    $data=[':user_id'=>$id,':date'=>$day,':start'=>$start,':fin'=>$fin,':number'=>$number,':use_table'=>$ans,':type_id'=>$type,':text'=>$text,':reservation_date'=>$reservation_date];
                    queryInsert($sql,$data);

                    //次の画面で登録内容を表示するためにセッションに格納する
                    $_SESSION['day']=$day;
                    $_SESSION['start']=$start;
                    $_SESSION['fin']=$fin;
                    $_SESSION['number']=$number;
                    $_SESSION['name']=$name;
                    $_SESSION['tell']=$tell;
                    $_SESSION['type']=$type;
                    $_SESSION['text']=$text;
                    header('Location:fin.php');
                }else{

                    //DBに接続(最初にユーザー情報の登録を行う)
                    $sql='INSERT INTO users(name,tell) VALUES(:name,:tell)';
                    $data=[':name'=>$name,':tell'=>$tell];
                    $db=queryInsert($sql, $data);
                    //登録したユーザー情報のユーザーIDを所得する
                    $id=$db->lastInsertId();
                    $sql2='INSERT INTO reservation(user_id,date,start,fin,number,use_table,type_id,text,reservation_date) VALUES(:user_id,:date,:start,:fin,:number,:use_table,:type_id,:text,:reservation_date)';
                    $data2=[':user_id'=>$id,':date'=>$day,':start'=>$start,':fin'=>$fin,':number'=>$number,':use_table'=>$ans,':type_id'=>$type,':text'=>$text,':reservation_date'=>$reservation_date];
                    queryInsert($sql2,$data2);

                    //次の画面で登録内容を表示するための情報をセッションに格納する
                    $_SESSION['day']=$day;
                    $_SESSION['start']=$start;
                    $_SESSION['fin']=$fin;
                    $_SESSION['number']=$number;
                    $_SESSION['name']=$name;
                    $_SESSION['tell']=$tell;
                    $_SESSION['type']=$type;
                    $_SESSION['text']=$text;
                    header('Location:fin.php');
                }

            }catch(Exception $e){
                debug($e->getMessage());
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
    <title>予約ページ</title>
</head>
<body>
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
    <?php include('../html/header.html') ?>
    <section class="main site-width">
        <form action="register.php" method="POST" class="form-register">
        <h2 class="error"><?php if(!empty($err_msg['top'])) echo $err_msg['top'] ?></h2>
            予約日:<span class="error"></span><br/>
            <input type="date" name="day" value="<?php echo $day ?>"><br/>
            開始時間:<span class="error"></span><br/>
            <input type="time" name="start" value="<?php echo $start ?>"><br/>
            終了時間:<span class="error"></span><br/>
            <input type="time" name="fin" value="<?php echo $fin ?>"><br/>
            席タイプ:<span class="error"></span><br/>
            <select name="type" value="<?php echo $type ?>">
                <option vaue="0">選択してください</option>
                <option value="1" <?php if($type === '1') echo 'selected' ?>>禁煙テーブル</option>
                <option value="2" <?php if($type === '2') echo 'selected' ?>>喫煙テーブル席</option>
                <option value="3" <?php if($type === '3') echo 'selected' ?>>禁煙カウンター</option>
                <option value="4" <?php if($type === '4') echo 'selected' ?>>喫煙カウンター</option>
            </select><br/>
            人数:<span class="error"></span><br/>
            <input type="text" name="number" value="<?php echo $number ?>"><br/>
            氏名:<span class="error"><?php if(!empty($err_msg['name'])) echo $err_msg['name'] ?></span><br/>
            <input type="text" name="name" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ?>"><br/>
            電話番号:<span class="error"><?php if(!empty($err_msg['tell'])) echo $err_msg['tell'] ?></span><br/>
            <input type="text" name="tell" value="<?php if(!empty($_POST['tell'])) echo $_POST['tell'] ?>"><br/>
            備考欄:<br/>
            <textarea cols="40" rows="10" name="text" value="<?php if(!empty($_POST['text'])) echo $_POST['text'] ?> "></textarea><br/>
            <input type="submit" value="確認">
        </form>
    </section>
    <?php include('../html/footer.html') ?>
</body>
</html>