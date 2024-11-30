<?php
require_once("../db_connect2.php");

// 获取来自前端的表单数据
$title = $_POST['title'];
$content = $_POST['content'];
$type = $_POST["type_name"];
$imgId = $_POST['imgId']; // 图片 ID

// 插入新文章到数据库
$sql = "INSERT INTO article (title, content, type_id, img_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $title, $content, $type, $imgId);

if ($stmt->execute()) {
    echo "文章已成功創建！";
} else {
    echo "發生錯誤，請稍後再試。";
}
?>
