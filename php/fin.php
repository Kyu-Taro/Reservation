<?php 
    require_once('function.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <title>予約完了画面</title>
</head>
<body>
    <?php include('../html/header.html') ?>
    <section class="confirmation site-width">
        <h1>予約情報</h1>
            <div class="container site-width">
                <table>
                    <tr><td>日付</td><td><?php echo $_SESSION['day']?></td></tr>
                    <tr><td>開始</td><td><?php echo $_SESSION['start']?></td></tr>
                    <tr><td>終了</td><td><?php echo $_SESSION['fin']?></td></tr>
                    <tr><td>席タイプ</td><td><?php echo $_SESSION['type']?></td></tr>
                    <tr><td>人数</td><td><?php echo $_SESSION['number']?></td></tr>
                    <tr><td>氏名</td><td><?php echo $_SESSION['name']?></td></tr>
                    <tr><td>電話番号</td><td><?php echo $_SESSION['tell']?></td></tr>
                    <tr><td>備考</td><td><?php echo $_SESSION['text']?></td></tr>
                </table>
                <a href="index.php">終了</a>
            </div>
    </section>
    <?php include('../html/footer.html') ?>
</body>
</html>