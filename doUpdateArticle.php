<?php
require_once("../pdo-connect.php");

$id = $_POST["id"];
$title = $_POST["title"];
$content = $_POST["content"];
$imgId = $_POST['imgId'];
$now = date('Y-m-d H:i:s');
$type = $_POST["type_name"];

$sql = "SELECT * FROM article_type WHERE name = :type";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':type', $type, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) == 0) {
    echo "沒有這種類別";
    exit;
} else {
    $type_id = $rows[0]["id"];
    echo $type_id;
}

// 更新文章資料
$sql = "UPDATE article
        SET title = :title, 
            content = :content, 
            type_id = :type_id, 
            created_at = :created_at, 
            img_id = :img_id
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':title', $title, PDO::PARAM_STR);
$stmt->bindParam(':content', $content, PDO::PARAM_STR);
$stmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
$stmt->bindParam(':created_at', $now, PDO::PARAM_STR);
$stmt->bindParam(':img_id', $imgId, PDO::PARAM_INT);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "資料更新成功!";
    // 更新成功後重定向
    header("Location: article_list.php");
    exit;
} else {
    echo "更新失敗: " . $stmt->errorInfo()[2];
}

// 關閉連接
$conn = null;
?>
