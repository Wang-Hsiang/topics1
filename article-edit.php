<?php
require_once("../pdo-connect.php");
if (!isset($_GET["id"])) {
    echo "請帶入 id 到此頁";
    exit;
}
$id = $_GET["id"];
$sql = "SELECT * FROM article WHERE id = :id AND is_deleted = 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "找不到該文章";
    exit;
}

// 查詢圖片
$sql2 = "SELECT * FROM img";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$rowCount2 = count($row2); // 使用 count() 檢查結果數量

// 查詢文章類型
$sql3 = "SELECT * FROM article_type";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute();
$row3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
$rowCount3 = count($row3); 
// echo "<pre>";
// print_r($row3);
// echo "</pre>";
?>
<!doctype html>
<html lang="en">

<head>
    <title>Article-Delete</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php") ?>

</head>
<style>
    textarea {
        height: 200px;
    }

    .img-form {
        width: 13rem;
        /* 調整為多行高度 */
        border: 1px solid grey;
        border-radius: 6px;
        padding: 3px 2px;
        display: flex;
    }

    .content {
        width: 30rem;
        height: 30rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    img {
        max-width: 100%;
        max-height: 300px;
        margin: 1px;
    }
</style>

<body>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">確認刪除</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    確認刪除該文章?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a href="delete-article.php?id=<?= $row["id"] ?>" class="btn btn-danger">確認</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="py-2">
            <a href="article_list.php" class="btn btn-primary"><i class="fa-solid fa-left-long fa-fw"></i></a>
        </div>
        <?php if ($stmt->rowCount() > 0): ?>
            <h1><?= $row["title"] ?></h1>
            <form action="doUpdateArticle.php" method="post">
                <table class="table table-bordered">
                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                    <tr>
                        <th>文章標題</th>
                        <td>
                            <input type="text" class="form-control"
                                name="title"
                                value="<?= $row["title"] ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>文章內容</th>
                        <td>
                            <div class="text-scroll">
                                <textarea class="form-control"
                                    name="content"
                                    rows="4"><?= htmlspecialchars($row["content"]) ?></textarea>
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <th>類別</th>
                        <td>
                            <div class="mb-2">
                                <select class="d-block" name="type_name" id="type" style="width: 7rem;">
                                    <option value="" disabled selected>請選擇類型</option>
                                    <?php for ($i = 0; $i < $rowCount3; $i++): ?>
                                        <option value="<?= $row3[$i]["name"] ?>"><?= $row3[$i]["name"] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
    </div>
    </td>
    </tr>
    <tr>
        <th>照片</th>
        <td>
            <div class="mb-2">
                <div class="content pb-2 overflow-hidden"></div>
                <input type="file" class="img-form" name="img" multiple>
            </div>
            <script>
                const input_file = document.querySelector("input[type='file']");
                const content = document.querySelector(".content");
                const form = document.querySelector("form");

                input_file.addEventListener("change", e => {
                    let imgId = null; // Default to null, in case no match is found

                    console.log(e.currentTarget.files.length); // 打印選擇的文件數量
                    for (let i = 0; i < e.currentTarget.files.length; i++) {
                        const file = e.currentTarget.files[i];
                        if (file.type.startsWith("image/")) { // 確保選擇的是圖片
                            const node = document.createElement("img"); // 創建一個 <img> 元素
                            const src = URL.createObjectURL(file); // 創建指向圖片的臨時 URL
                            node.src = src; // 設定圖片的源
                            content.append(node); // 把圖片添加到 <div class="content"> 中

                            // Compare the file name with the database image names
                            <?php foreach ($row2 as $image): ?>
                                if (file.name === "<?= $image['img'] ?>") {
                                    imgId = "<?= $image['id'] ?>"; // Get the corresponding img id
                                    console.log("Matched Image ID: ", imgId);
                                }
                            <?php endforeach; ?>

                            // If we found a match, create a hidden input to store the imgId
                            if (imgId) {
                                const hiddenInput = document.createElement("input");
                                hiddenInput.type = "hidden";
                                hiddenInput.name = "imgId";
                                hiddenInput.value = imgId;
                                form.appendChild(hiddenInput);
                            }
                        } else {
                            console.log(`File ${file.name} is not an image.`);
                        }
                    }
                });
            </script>

        </td>
    </tr>
    <tr>
        <th>發布時間</th>
        <td><?= $row["created_at"] ?></td>
    </tr>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            <button class="btn btn-primary" type="submit">儲存</button>
            <a href="article_list.php?id=<?= $row["id"] ?>" class="btn btn-primary">取消</a>
        </div>
        <div>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal" type="button">刪除</button>
        </div>
    </div>

    </form>
<?php else: ?>
    <h1>找不到使用者</h1>
<?php endif; ?>
</div>

<!-- Bootstrap JavaScript Libraries -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
</body>

</html>