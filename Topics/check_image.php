<?php
require_once("db_connect2.php");

header("Content-Type: application/json");

// 獲取檔案名稱
$data = json_decode(file_get_contents("php://input"), true);
$fileName = $data['fileName'];

// 查詢資料庫
$sql = "SELECT id FROM img WHERE img = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fileName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["success" => true, "id" => $row['id']]);
} else {
    echo json_encode(["success" => false]);
}
