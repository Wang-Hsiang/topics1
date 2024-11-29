<?php
require_once("../db_connect2.php");

$title = $_POST["title"];
$content = $_POST["content"];
$img = $_POST["img"];
$now = date('Y-m-d H:i:s');
$type = $_POST["type_name"];
echo "$title, $content, $type ";

$sql = "SELECT * FROM article_type WHERE name='$type'";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

if (count($rows) == 0) {
    echo "沒有這種類別";
    exit;
} else {
    $type_id = $rows[0]["id"];
    echo $type_id;
}

$sql = " INSERT INTO article (title, content, type_id, created_at)
	     VALUES ('$title', '$content','$type_id', '$now')
";

// echo $sql;
// exit;



if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("Location: article_list.php");
