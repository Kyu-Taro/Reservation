<?php
    require('function.php');

    try{
        $sql='SELECT * FROM users';
        $items=queryPost3($sql);
        debug(print_r($items,true));
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
    <title>顧客情報</title>
</head>
<body>
    <?php require('../html/header.html')?>
    <div class="site-width reservation">
        <table class="reservation-table">
            <thead class="scrollHead"> 
                <tr><th>ID</th><th>氏名</th></th><th>電話番号</th></tr>
            </thead>
            <tbody class="scrollBody">
                <?php foreach($items as $item){ ?>
                    <tr>
                        <td><?php echo $item['id'] ?></td>
                        <td><?php echo $item['name']?></td>
                        <td><?php echo $item['tell']?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <?php include('../html/footer.html')?>
</body>
</html>