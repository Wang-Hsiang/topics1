<?php
require_once("../db_connect2.php");

if(!isset($_POST["name"])){
    exit("請循正常管道進入此頁");
}

$id=$_POST["id"];
$name=$_POST["name"];
$email=$_POST["email"];
$phone=$_POST["phone"];

$sql="UPDATE users SET name='$name', email='$email',phone='$phone' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤: " . $conn->error;
}

$conn->close();

header("location: article-edit.php?id=$id");