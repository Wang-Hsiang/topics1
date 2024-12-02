<?php
require_once("../pdo-connect.php");

$title = $_POST["title"];
$content = $_POST["content"];
$imgName = $_POST['imgId'];  // 假設 imgId 是圖片的檔案名稱或 ID
$now = date('Y-m-d H:i:s');
$type = $_POST["type_name"];

// 查詢文章類型
$sql = "SELECT * FROM article_type WHERE name = :type";
$result = $conn->prepare($sql);
$result->bindParam(':type', $type, PDO::PARAM_STR);
$result->execute();
$rows = $result->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) == 0) {
    echo "沒有這種類別";
    exit;
} else {
    $type_id = $rows[0]["id"];
}

// 插入圖片
$sqlimg = "INSERT INTO img (img) VALUES (:imgName)";
$stmt = $conn->prepare($sqlimg);
$stmt->bindParam(':imgName', $imgName, PDO::PARAM_STR);

if ($stmt->execute()) {
    $imgId = $conn->lastInsertId();  // 獲取新插入的圖片 ID

    // 插入文章
    $sql = "INSERT INTO article (title, content, type_id, created_at, img_id)
            VALUES (:title, :content, :type_id, :created_at, :img_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
    $stmt->bindParam(':created_at', $now, PDO::PARAM_STR);
    $stmt->bindParam(':img_id', $imgId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $last_id = $conn->lastInsertId();
        echo "新資料輸入成功, 文章 ID 為 $last_id";
    } else {
        echo "Error inserting article: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Error inserting image: " . $stmt->errorInfo()[2];
}

// 關閉連接
$conn = null;

// 重定向到文章列表頁
header("Location: article_list.php");
exit;
?>
