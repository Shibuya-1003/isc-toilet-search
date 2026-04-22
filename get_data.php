<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_config.php';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error){
    die("接続失敗： ".$conn->connect_error);
}

// Noごとに最新の1件ずつを取る
$sql = "
  SELECT t1.*
  FROM sensor t1
  INNER JOIN (
    SELECT No, MAX(id) AS max_id
    FROM sensor
    GROUP BY No
  ) t2
  ON t1.No = t2.No AND t1.id = t2.max_id
  ORDER BY t1.No ASC
";

$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
header("Content-Type: application/json");
echo json_encode($data);
?>
