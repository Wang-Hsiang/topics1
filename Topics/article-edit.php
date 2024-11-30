<?php
require_once("../db_connect2.php");
if (!isset($_GET["id"])) {
    echo "請帶入 id 到此頁";
    exit;
}
$id = $_GET["id"];
$sql = "SELECT * FROM article WHERE id='$id' AND is_deleted=0";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
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
                    <a href="delet-article.php?id=<?= $row["id"] ?>" class="btn btn-danger">確認</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="py-2">
            <a href="article_list.php" class="btn btn-primary"><i class="fa-solid fa-left-long fa-fw"></i></a>
        </div>
        <?php if ($result->num_rows > 0): ?>
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
                        <th>照片</th>
                        <td>
                            <input type="tel" class="form-control"
                                name="img"
                                value="<?= $row["img"] ?>">
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