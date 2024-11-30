<?php
require_once("../db_connect2.php");

$per_page = 4;
$sqlAll = "SELECT * FROM article WHERE is_deleted=0";
$resultAll = $conn->query($sqlAll);
$articleAllCount = $resultAll->num_rows;

$name = $_GET["name"] ?? null;
$search = $_GET["search"] ?? null;
$p = $_GET["p"] ?? 1;
$order = $_GET["order"] ?? 1;

$start_item = ($p - 1) * $per_page;
$total_page = ceil($articleAllCount / $per_page);

// 構建 WHERE 條件
$whereClause = "WHERE article.is_deleted=0";
if ($search) {
    $whereClause .= " AND article.title LIKE '%$search%'";
}
if ($name) {
    $whereClause .= " AND article.type_id=$name";
}

// 構建 ORDER BY 條件
$orderClause = "";
switch ($order) {
    case 1:
        $orderClause = "ORDER BY article.id ASC";
        break;
    case 2:
        $orderClause = "ORDER BY article.id DESC";
        break;
}

// 完整查詢語句
$sql = "SELECT article.*, article_type.name AS type_name, img.img AS img
        FROM article
        JOIN article_type ON article.type_id = article_type.id
        JOIN img ON article.img_id = img.id
        $whereClause
        $orderClause
        LIMIT $start_item, $per_page";

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// 類型查詢
$sql2 = "SELECT * FROM article_type";
$result2 = $conn->query($sql2);
$typeList = $result2->fetch_all(MYSQLI_ASSOC);

?>
<!doctype html>
<html lang="en">

<head>
    <title>Article-list</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php") ?>
    <style>
        /* 已省略，沿用之前的 CSS */
    </style>
</head>

<body>
    <?php include("../style.php") ?>

    <div class="box">
        <div class="container">
            <ul class="nav nav-underline pb-2 mt-5">
                <li class="nav-item">
                    <a class="nav-link <?php if (!$name) echo 'active'; ?>" href="article_list2.php?p=1&order=1">全部</a>
                </li>
                <?php foreach ($typeList as $type): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($name == $type['id']) echo 'active'; ?>" href="article_list2.php?name=<?= $type['id'] ?>&p=1&order=1">
                        <?= $type['name'] ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <h1 class="text-center pt-5 pb-1">文章列表</h1>
            <div class="py-2 text-end">
                <div class="col-md-auto">
                    <a class="btn btn-primary" href="create_article.php" title="新增文章"><i class="fa-solid fa-user-plus"></i> 新增文章</a>
                </div>
            </div>

            <div class="py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        共計 <?= $result->num_rows ?> 篇文章
                    </div>
                    <div>
                        <div class="btn-group">
                            <a class="btn btn-primary <?php if ($order == 1) echo 'active'; ?>" href="article_list2.php?name=<?= $name ?>&p=<?= $p ?>&order=1">
                                <i class="fa-solid fa-arrow-down-1-9 fa-fw"></i>
                            </a>
                            <a class="btn btn-primary <?php if ($order == 2) echo 'active'; ?>" href="article_list2.php?name=<?= $name ?>&p=<?= $p ?>&order=2">
                                <i class="fa-solid fa-arrow-down-9-1 fa-fw"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($rows): ?>
            <div class="table-main">
                <!-- 表格頭部 -->
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
                <!-- 表格內容 -->
                <?php foreach ($rows as $article): ?>
                <div class="table-row">
                    <div class="col0 col1"><?= $article['id'] ?></div>
                    <div class="col0 col2"><?= $article['title'] ?></div>
                    <div class="col0 col3 text-scroll"><?= htmlspecialchars($article['content']) ?></div>
                    <div class="col0 col4"><img src="../img/<?= $article['img'] ?>" alt=""></div>
                    <div class="col0 col5"><?= $article['created_at'] ?></div>
                    <div class="col0 col6"><?= $article['type_name'] ?></div>
                    <div class="col0 col7">
                        <a class="btn btn-primary" href="readArticle.php?id=<?= $article['id'] ?>">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i>觀看</a>
                    </div>
                    <div class="col0 col8">
                        <button class="btn btn-danger" type="button" data-id="<?= $article['id'] ?>">刪除</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- 分頁 -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_page; $i++): ?>
                    <li class="page-item <?php if ($i == $p) echo 'active'; ?>">
                        <a class="page-link" href="article_list2.php?name=<?= $name ?>&p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>

            <?php else: ?>
            <div>沒有文章。</div>
            <?php endif; ?>
        </div>
    </div>
    <!-- JavaScript -->
    <script>
        // 已省略，與之前的刪除功能代碼相同。
    </script>
</body>

</html>
