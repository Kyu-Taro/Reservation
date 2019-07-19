<?php
    require('function.php');

    $id=$_GET['id'];
    $delete=$_GET['delete'];

    try {
        if ($id) {
            $sql='UPDATE reservation SET delete_flg = 1 WHERE id = :id';
            $date=[':id'=>$id];
            queryInsert($sql, $date);
            $_SESSION['fls_message']='予約終了しました。';
            header('Location:reservation.php');
        }else{
            $sql='DELETE FROM reservation WHERE id = :id';
            $date=[':id'=>$delete];
            queryInsert($sql, $date);
            $_SESSION['fls_message']='予約をキャンセルしました。';
            header('Location:reservation.php');
        }
    }catch(Exception $e){
        debug($e->getMessage());
    }