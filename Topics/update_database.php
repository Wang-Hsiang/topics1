<?php
require_once("db_connect2.php");

header("Content-Type: application/json");

// 獲取 img.id
$data = json_decode(file_get_contents("php://input"), true);
$imgId = $data['imgId'];

// 更新資料庫
$sql = "UPDATE some_table SET column_name = ? WHERE some_condition = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $imgId, $someCondition); // 根據實際情況替換參數
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}
