<?php
// データベースに接続するための設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続の設定を外部ファイルから読み込む
require_once 'db_config.php';

// 値を取り出した後に入れる
$status = isset($_POST["Status"]) ? (is_array($_POST["Status"]) ? $_POST["Status"][0] : $_POST["Status"]) : null;


$allowed_statuses = ["使用中", "未使用"];
if (!in_array($status, $allowed_statuses)) {
    // 許可された文字以外が来たら、処理を止める
    die(json_encode(["status" => "error", "message" => "不正な値です"]));
}

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("接続失敗： " . $conn->connect_error);
}

//POSTを受け取る
$no = isset($_POST["No"]) ? (is_array($_POST["No"]) ? $_POST["No"][0] : $_POST["No"]) : null;
$time = isset($_POST["Time"]) ? (is_array($_POST["Time"]) ? $_POST["Time"][0] : $_POST["Time"]) : null; 
$status = isset($_POST["Status"]) ? (is_array($_POST["Status"]) ? $_POST["Status"][0] : $_POST["Status"]) : null;
$distance = isset($_POST["Distance"]) ? (is_array($_POST["Distance"]) ? $_POST["Distance"][0] : $_POST["Distance"]) : null;

//SQLテンプレ
$sql = "INSERT INTO sensor (`No`, `Time`, `Status`, `Distance`) VALUES (?, ?, ?, ?)";

//プリペアドステートメントを準備
$stmt = $conn->prepare($sql);

if($stmt){
    $stmt->bind_param("issi", $no, $time, $status, $distance);


//実行する
if($stmt->execute()){
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

//ステートメントを閉じる
$stmt->close();
}else{
    echo json_encode(["status" => "error", "message" => $conn->error]);

}

$conn->close();
?>
