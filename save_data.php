<?php
// データベースに接続するための設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベースの接続情報を設定
$host = "YOUR_HOST";
$user = "YOUR_USER";
$password = "YOUR_PASSWORD";
$dbname = "YOUR_DBNAME";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("接続失敗： " . $conn->connect_error);
}

//POST確認ログ
file_put_contents("log.txt", "---POST確認---\n". print_r($_POST, true), FILE_APPEND);

//値を取り出す(配列が来た場合は先頭要素だけにする)
$no = isset($_POST["No"]) ? (is_array($_POST["No"]) ? $_POST["No"][0] : $_POST["No"]) : null;
$time = isset($_POST["Time"]) ? (is_array($_POST["Time"]) ? $_POST["Time"][0] : $_POST["Time"]) : null; 
$status = isset($_POST["Status"]) ? (is_array($_POST["Status"]) ? $_POST["Status"][0] : $_POST["Status"]) : null;
$distance = isset($_POST["Distance"]) ? (is_array($_POST["Distance"]) ? $_POST["Distance"][0] : $_POST["Distance"]) : null;

//ログ出力（確認用）
file_put_contents("log.txt", "受信データ： No=$no, Time=$time, Status=$status, Distance=$distance\n", FILE_APPEND); 

//必須チェック
if($no === null || $time === null || $status === null) {
    die("エラー: 必要なデータが送信されていません");
}

//SQL実行
$sql = "INSERT INTO sensor (`No`, `Time`, `Status`,`Distance`) VALUES ('$no', '$time', '$status','$distance')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>
