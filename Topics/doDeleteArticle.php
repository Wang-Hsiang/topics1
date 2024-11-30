<?php
require_once("../db_connect2.php");

$id = $_GET["id"];

$sql = "UPDATE article SET is_deleted=1 WHERE id=$id";

if ($conn->query($sql) === TRUE) {
  echo "刪除成功";
} else {
  echo "刪除錯誤 "  . $conn->error;
}


$conn->close();

header("location:article_list.php");