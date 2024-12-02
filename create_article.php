<?php
require_once("../pdo-connect.php");

$sql = "SELECT * 
FROM article_type";

$result = $conn->prepare($sql);
$result->execute();  
$row = $result->fetchAll(PDO::FETCH_ASSOC);
$rowCount = $result->rowCount();


$sql2 = "SELECT * 
FROM img";

$result2 = $conn->prepare($sql2);
$result2->execute(); 
$row2 = $result2->fetchAll(PDO::FETCH_ASSOC);
$rowCount2 = $result2->rowCount();

?>
<!doctype html>
<html lang="en">

<head>
    <title>Create User</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php") ?>
</head>
<style>
    .box {
        padding-left: 20rem;
    }

    .other-form {
        width: 50rem;
        display: block;
        border: 1px solid grey;
        border-radius: 6px;
        padding: 6px 12px;

    }

    .main-form {
        width: 50rem;
        height: 100px;
        /* 調整為多行高度 */
        border: 1px solid grey;
        border-radius: 6px;
        padding: 6px 12px;
        overflow-y: scroll;
        display: flex;
    }

    .img-form {
        width: 13rem;
        /* 調整為多行高度 */
        border: 1px solid grey;
        border-radius: 6px;
        padding: 3px 2px;
        display: flex;
    }

    img {
        max-width: 100%;
        max-height: 300px;
        margin: 1px;
    }

    label {
        padding-top: 25px;
    }
</style>



<body>
    <?php include("../style.php") ?>
    <div class="box">
        <div class="container">
            <div class="py-2">
                <a href="article_list.php" class="btn btn-primary" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
            </div>
            <h1 class="pt-3">新增文章</h1>
            <form action="doCreateArticle.php" method="post" id="form">
                <div class="table-main">
                    <div class="mb-2">
                        <label for="" class="form-label">文章標題</label>
                        <input type="title" class="other-form" name="title">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">文章內容</label>
                        <textarea type="content" class="main-form" name="content"></textarea>
                    </div>
                    <div class="mb-2">
                        <div class="content pb-2 overflow-hidden"></div>
                        <input type="file" class="img-form" name="imgId" accept="image/*" multiple>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">類型</label>
                        <select class="d-block" name="type_name" id="type" style="width: 7rem;">
                            <option value="" disabled selected>請選擇類型</option>
                            <?php for ($i = 0; $i < $rowCount; $i++): ?>
                                <option value="<?= $row[$i]["name"] ?>"><?= $row[$i]["name"] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- 修正提交按鈕 -->
                <button class="btn btn-info mt-5" type="submit">送出</button>
            </form>
        </div>
    </div>

    <script>
        const inputFile = document.querySelector("input[type=file]")
        const uploadimgs = document.querySelector(".uploadimgs")
        inputFile.addEventListener("change", e => {
            console.log(e.currentTarget.files.length);
            for (let i = 0; i < e.currentTarget.files.length; i++) {
                const node = document.createElement("img");
                const src = URL.createObjectURL(e.currentTarget.files[i]);
                node.src = src;
                uploadimgs.appendChild(node);
            }
        })
    </script>


</body>

</html>