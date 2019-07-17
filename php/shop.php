<?php
    require_once('function.php');

    if(!empty($_POST)){
        try {
            //変更内容を所得してshopテーブルを書き換える
            $sql='UPDATE shop SET number = :number WHERE id = :id';
            $data=[':number'=>$_POST['table1'],':id'=>1];
            queryInsert($sql, $data);
            $sql='UPDATE shop SET number = :number WHERE id = :id';
            $data=[':number'=>$_POST['table2'],':id'=>2];
            queryInsert($sql, $data);
            $sql='UPDATE shop SET number = :number WHERE id = :id';
            $data=[':number'=>$_POST['counter1'],':id'=>3];
            queryInsert($sql, $data);
            $sql='UPDATE shop SET number = :number WHERE id = :id';
            $data=[':number'=>$_POST['counter2'],':id'=>4];
            queryInsert($sql, $data);
            $_SESSION['fls_message']='更新完了しました';
            header('Location:index.php');
        }catch(Exception $e){
            debug($e->getMessage());
        }
    }else{
        //現在の登録内容を表示するためにDBからデーターを所得する
        try{
            //現在の席数のデータを所得するクエリ
            $sql='SELECT number FROM shop';
            $items=queryPost2($sql);
            $row=$items->fetchAll(PDO::FETCH_COLUMN);
        }catch(Exception $e){
            debug($e->getMessage());
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
    <title>店舗管理</title>
</head>
<body>
    <?php include('../html/header.html')?>
    <div class="shop-container　site-width">
        <form action="shop.php" method="POST">
        <h1>店舗更新ページ</h1>
            禁煙テーブル席数:<span class="error"></span><br/>
            <input type="number" name="table1" value="<?php echo $row[0]?>"><br/>
            喫煙テーブル席:<span class="error"></span><br/>
            <input type="number" name="table2" value="<?php echo $row[1]?>"><br/>
            禁煙カウンター席:<span class="error"></span><br/>
            <input type="number" name="counter1" value="<?php echo $row[2]?>"><br/>
            喫煙カウンター席:<span class="error"></span><br/>
            <input type="number" name="counter2" value="<?php echo $row[3]?>"><br/>
            <input type="submit" value="変更">
        </form>
    </div>
    <?php include('../html/footer.html')?>
</body>
</html>