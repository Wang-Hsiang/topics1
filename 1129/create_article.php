<?php
require_once("../db_connect2.php");

$sql = "SELECT * 
FROM article_type";


$result = $conn->query($sql);
$row = $result->fetch_all(MYSQLI_ASSOC);
$rowCount = $result->num_rows;

// echo "<pre>";
// print_r($row[0]["name"]);
// echo "</pre>";

// exit;
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
            <form action="doCreateArticle.php" method="post">
                <div class="table-main">
                    <div class="mb-2">
                        <label for="" class="form-label">文章標題</label>
                        <input type="title" class="other-form" name="title">
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">文章內容</label>
                        <textarea type="content" class="main-form" name="content">
                        </textarea>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">照片</label>
                        <div class="content pb-2 overflow-hidden"></div>
                        <input type="file" class="img-form" name="img" multiple>
                    </div>
                    <script>
                        const input_file = document.querySelector("input[type='file']");
                        const content = document.querySelector(".content");

                        input_file.addEventListener("change", e => {
                            console.log(e.currentTarget.files.length); // 打印選擇的文件數量
                            for (let i = 0; i < e.currentTarget.files.length; i++) {
                                const file = e.currentTarget.files[i];
                                if (file.type.startsWith("image/")) { // 確保選擇的是圖片
                                    const node = document.createElement("img"); // 創建一個 <img> 元素
                                    const src = URL.createObjectURL(file); // 創建指向圖片的臨時 URL
                                    node.src = src; // 設定圖片的源
                                    content.append(node); // 把圖片添加到 <div class="content"> 中
                                } else {
                                    console.log(`File ${file.name} is not an image.`);
                                }
                            }
                        });
                    </script>
                    <div class="mb-2">
                        <label for="" class="form-label">類型</label>
                        <select class="d-block" name="type_name" id="type" style="width: 7rem;">
                            <option value="" disabled selected>請選擇類型</option>
                            <?php for ($i=0; $i < $rowCount; $i++): ?>
                            <option value="<?= $row[$i]["name"] ?>"><?= $row[$i]["name"] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <button class="btn btn-info mt-5" type="submit">送出</button>
            </form>
        </div>
    </div>
</body>

</html>