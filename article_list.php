<?php
require_once("../pdo-connect.php");

// $sql = "SELECT article.*, 
// article_type.name AS type_name
// FROM article
// JOIN article_type ON article.type_id = article_type.id
// WHERE article.is_deleted=0 
// ";

// $result = $conn->query($sql);
// $rows = $result->fetch_all(MYSQLI_ASSOC);
// echo"<pre>";
// print_r($rows);
// echo"</pre>";

// exit;

// $countStmt = $db_host->prepare($countSql);
// $countStmt->execute();
// $total_results = $countStmt->rowCount();
$per_page = 4;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$start = ($p - 1) * $per_page;

$order = isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'DESC' : 'ASC'; //不用動
$allowed_columns = ['id', 'title', 'img', 'content', 'type_id', 'created_at']; //內容改你的類別 記得跟下面一樣
$sort_column = isset($_GET['sort_column']) && in_array($_GET['sort_column'], $allowed_columns) ? $_GET['sort_column'] : 'id'; //不需要改 除非你要的第一個排序不是id
$sql = "SELECT * from article 
where is_deleted=0 and title 
LIKE '%$search%' 
ORDER BY $sort_column $order 
limit $start,$limit ";

// $sql = "SELECT article.*, article_type.name AS type_name
//         FROM article
//         JOIN article_type ON article.type_id = article_type.id
//         WHERE article.is_deleted=0";

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql = "SELECT article.*, article_type.name AS type_name, img.img AS img
            FROM article
            JOIN article_type ON article.type_id = article_type.id
            JOIN img ON article.img_id = img.id
            WHERE article.title LIKE '%$search%' AND article.is_deleted=0";
} else if (isset($_GET["p"])) {
    $p = $_GET["p"];
    if (!isset($_GET["order"])) {
        header("location: article_list.php?p=1&order=1");
    }
    $order = $_GET["order"];
    $start_item = ($p - 1) * $per_page;
    $total_page = ceil($articleAllCount / $per_page);

    $whereClause = "";
    switch ($order) {
        case 1:
            $whereClause = "ORDER BY id ASC";
            break;
        case 2:
            $whereClause = "ORDER BY id DESC";
            break;
    }

    $sql = "SELECT article.*, article_type.name AS type_name, img.img AS img
            FROM article
            JOIN article_type ON article.type_id = article_type.id
            JOIN img ON article.img_id = img.id
            WHERE article.is_deleted=0 
            $whereClause
            LIMIT $start_item, $per_page";
} else {
    header("location: article_list.php?p=1&order=1");
}

$result = $conn->query($sql);

$sqlAll = "SELECT * from article where is_deleted=0 and title LIKE '%$search%' ORDER BY $sort_column $order limit $start,$per_page ";
$resultAll = $conn->prepare($sqlAll);
$resultAll->execute();
$articleAllCount = $resultAll->rowCount();

if (isset($_GET["search"])) {
    $article_count = $result->rowCount();
} else {
    $article_count = $articleAllCount;
}
$rows = $result->fetchall(PDO::FETCH_ASSOC);

// $sql2 = "SELECT * FROM article_type";
// $result2 = $conn->query($sql2);
// $rows2 = $result2->fetch_all(MYSQLI_ASSOC);



// echo"<pre>";
// print_r($rows);
// echo"</pre>";

// $type_nameArr = [];
// foreach ($result2 as $type_name) {
//     $type_nameArr[$type_name["id"]] = $type_name["name"];
// }

?>
<!doctype html>
<html lang="en">

<head>

    <title>Article-list</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php") ?>
    <style>
        .box {
            padding-left: 20rem;
            width: 100rem;
            overflow: hidden;
            display: flex;
        }

        .table-main {
            .table-row {
                display: flex;
                flex-wrap: nowrap;
                border: 1px solid green;
                text-align: center;
                padding: 5px 0;
            }

            .col0 {
                width: calc(100% / 8);
            }

            .col1 {
                width: 100px;
            }

            .col2 {
                width: 200px;
            }

            .col3 {
                width: 400px;
            }

            .col4 {
                width: 200px;
            }
        }

        .col4 img {
            width: 200px;
            height: 200px;
        }

        .text-scroll {
            position: relative;
            /* 設定最大高度以啟用滾動 */
            max-height: 200px;
            overflow-y: auto;
            /* 啟用垂直滾動條 */
            /* word-break: break-word !important; */
            /* 單詞自動換行，防止溢出 */
            /* white-space: pre-wrap; */
            /* 保留空白和換行符 */
        }
    </style>

</head>

<body>
    <?php include("../style.php") ?>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">確認刪除</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button class="btn btn-danger btn-confirm" data-id="">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="container">
            <!-- <ul class="nav nav-underline pb-2 mt-5">
                <li class="nav-item">
                    <a class="nav-link <?php if (!isset($_GET["name"])) echo "active" ?>" aria-current="page" href="article_list.php">全部</a>
                </li>
                <?php foreach ($result2 as $type_name): ?>
                        <a class="nav-link <?php
                                            if (isset($_GET["name"]) && $_GET["name"] == $type_name["id"]) echo "active";
                                            ?>" href="article_list.php?name=<?= $type_name["id"] ?>">
                            <?= $type_name["name"] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>     -->
            <h1 class="text-center pt-5 pb-1">文章列表</h1>
            <div class="py-2 text-end">
                <div class="col-md-auto">
                    <a class="btn btn-primary" href="create_article.php" title="新增文章"><i class="fa-solid fa-user-plus"></i> 新增文章</a>
                </div>
            </div>
            <div class="py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        共計 <?= $article_count ?> 篇文章
                    </div>
                </div>
            </div>
            <?php if ($article_count > 0): ?>
                <div class="table-main">
                    <div class="text-danger " style="width: 80px; cursor: pointer;" onclick="window.location.href='?sort_column=id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&p=<?= $p ?>&search=<?= $search ?>'">ID
                        <!--               也要更改 ↓ -->
                        <?php if ($sort_column == 'id'): ?>
                            <!--                                                            這個也要↓ -->
                            <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'id' ? 'up' : 'down'; ?>"></i>
                        <?php endif; ?>
                    </div>
                    <div class="table-row">
                        <div class="col0 col1">文章編號</div>
                        <div class="col0 col2">文章標題</div>
                        <div class="col0 col3">文章內容</div>
                        <div class="col0 col4">照片</div>
                        <div class="col0 col5">發布時間</div>
                        <div class="col0 col6">類型</div>
                        <div class="col0 col7">觀看</div>
                        <div class="col0 col8">刪除</div>

                    </div>
                    <?php foreach ($rows as $article): ?>
                        <div class="table-row">
                            <div class="col0 col1"><?= $article["id"] ?></div>
                            <div class="col0 col2"><?= $article["title"] ?></div>
                            <div class="col0 col3 text-scroll"><?= htmlspecialchars($article["content"]) ?></div>
                            <div class="col0 col4"><img src="../img/<?= $article["img"] ?>" alt=""></div>
                            <div class="col0 col5"><?= $article["created_at"] ?></div>
                            <div class="col0 col6"><?= $article["type_name"] ?></div>
                            <div class="col0 col7">
                                <a class="btn btn-primary" href="readArticle.php?id=<?= $article["id"] ?>">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>觀看</a>
                            </div>
                            <div class="col0 col8">
                                <div>
                                    <button class="btn btn-danger " type="button" data-id="<?= $article["id"] ?>">刪除</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($_GET["p"])): ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_page; $i++): ?>
                                <li class="page-item 
                            <?php if ($i == $_GET["p"]) echo "active"; ?>">
                                    <a class="page-link" href="article_list.php?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                沒有這篇文章
            <?php endif; ?>
        </div>
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
    <script>
        const confirmModal = new bootstrap.Modal('#confirmModal', {});
        const btnConfirm = document.querySelector(".btn-confirm");
        const btns = document.querySelectorAll(".table-row button");
        btns.forEach(function(btn) {
            btn.addEventListener("click", function() {
                // alert(this.dataset.id)
                btnConfirm.setAttribute("data-id", this.dataset.id);
                confirmModal.show();
            });
        });

        btnConfirm.addEventListener("click", function() {
            const id = this.dataset.id;
            window.location.href = "delete-article.php?id=" + id;

        });
    </script>
</body>

</html>