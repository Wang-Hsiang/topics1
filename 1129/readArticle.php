<?php
require_once("../db_connect2.php");
if (!isset($_GET["id"])) {
    echo "請帶入 id 到此頁";
    exit;
}

$id = $_GET["id"];
$sql = "SELECT article.*, img.img AS img 
        FROM article 
        JOIN img ON article.img_id = img.id 
        WHERE article.id='$id' AND article.is_deleted=0";

$result = $conn->query($sql);
$row = $result->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadArticle</title>
</head>
<style>
    .box {
        padding-left: 20rem;
    }

    .text-scroll {
        width: 50rem;
        height: 25rem;
        padding-top: 50px;
    }

    .text-content {
        width: 50rem;
        height: 100rem;
        /* padding-top: 5px;  */
    }

    img {
        max-width: 100%;
        max-height: 300px;
        margin: 1px;
    }
</style>

<body>
    <?php include("../style.php") ?>
    <div class="box">
        <div class="container">
            <div class="py-3">
                <a href="article_list.php" class="btn btn-primary" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
            </div>
            <div class="py-3 text-end">
                <a class="btn btn-primary" href="article-edit.php?id=<?= $row["id"] ?>">
                    <i class="fa-solid fa-pen-to-square fa-fw"></i></a>
            </div>
            <h1 class="pt-3"><?= $row["title"] ?> </h1>
            <form action="doCreateArticle.php" method="post">
                <div class="text-scroll"><img src="../img/<?= $row["img"] ?>" alt="">
                </div>
                <div class="text-content"><?= htmlspecialchars($row["content"]) ?>
                </div>
            </form>
        </div>
    </div>
</body>

</html>