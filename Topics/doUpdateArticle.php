<?php
require_once("../db_connect2.php");

if(!isset($_POST["title"])){
    exit("請循正常管道進入此頁");
}

$id=$_POST["id"];
$title=$_POST["title"];
$content=$_POST["content"];
// $phone=$_POST["phone"];

$sql="UPDATE article SET title='$title', content='$content' 
WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤: " . $conn->error;
}

$conn->close();

header("location: article_list.php?id=$id");