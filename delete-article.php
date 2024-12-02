<?php
require_once("../pdo-connect.php");

if (!isset($_GET["id"])) {
    echo "請帶入 id";
    exit;
}

$id = $_GET["id"];

try {
    // 使用參數化查詢
    $sql = "UPDATE article SET is_deleted = 1 WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo "刪除成功";
    } else {
        echo "刪除失敗";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// 關閉連接
$conn = null;

// 重定向到文章列表頁
header("Location: article_list.php");
exit;
?>
