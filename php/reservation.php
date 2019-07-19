<?php 
    require('function.php');

    //予約一覧を所得
    try{
        $sql='SELECT R.id,U.name AS userName,R.date,R.start,R.fin,R.number,R.use_table,T.name AS typeName,R.text FROM reservation AS R JOIN users AS U ON R.user_id = U.id JOIN type AS T ON R.type_id = T.id WHERE delete_flg = 0';
        $stt=queryPost2($sql);
        debug(print_r($stt->fetch(PDO::FETCH_ASSOC),true));
    }catch(Exception $e){
        debug($e->getMessage());
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
    <title>予約一覧</title>
</head>
<body>
    <div id="js-show"><?php session_message($_SESSION['fls_message'])?></div>
    <?php include('../html/header.html') ?>
    <div class="reservation site-width">
        <table class="reservation-table">
            <thead class="scrollHead">
                <tr><th>氏名</th><th>日付</th><th>開始</th><th>終了</th><th>人数</th><th>席数</th><th>席タイプ</th><th>備考</th><th></th><th></th></tr>
            </thead>
            <tbody class="scrollBody">
                <?php
                    while($row=$stt->fetch(PDO::FETCH_ASSOC)){
                ?>
                        <tr>
                            <td><?php echo $row['userName']?></td>
                            <td><?php echo date('Y/m/d',strtotime($row['date']))?></td>
                            <td><?php echo datetime($row['start'],'H:i')?></td>
                            <td><?php echo datetime($row['fin'],'H:i')?></td>
                            <td><?php echo $row['number']?></td>
                            <td><?php echo $row['use_table']?></td>
                            <td><?php echo $row['typeName']?></td>
                            <td><?php echo $row['text']?></td>
                            <td><a href="deleteFlg.php?id=<?php echo $row['id']?>">予約終了</a></td>
                            <td><a href="deleteFlg.php?delete=<?php echo $row['id']?>">キャンセル</a></td>
                        </tr>
                <?php }　?>
            </tbody>
        </table>
    </div>
    <?php include('../html/footer.html')?>
</body>
</html>