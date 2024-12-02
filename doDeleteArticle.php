<?php
require_once("../pdo-connect.php");

$id = $_GET["id"];

$sql = "UPDATE article SET is_deleted=1 WHERE id=$id";

if ($conn->prepare($sql) === TRUE) {
  echo "刪除成功";
} else {
  echo "刪除錯誤 "  . $conn= NULL;
}


$conn = NULL;

header("location:article_list.php");