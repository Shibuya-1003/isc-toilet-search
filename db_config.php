<?php
    //環境変数からDB接続情報を取得
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $dbname = getenv('DB_NAME');

    //DB接続
    $conn = new mysqli($host, $user, $password, $dbname);
    
    //接続エラーの確認
    if ($conn->connect_error){
        die(json_encode(["status" => "error", "message" => "接続失敗" ]));
    }
?>
