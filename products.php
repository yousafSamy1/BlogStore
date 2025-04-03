<?php
require_once 'inc/conn.php';
require_once 'inc/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products View</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #1a1a1a;
            padding: 15px 0;
        }

        .navbar-brand {
            color: #ff0000 !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #ffffff !important;
            font-size: 1rem;
        }

        .nav-link:hover {
            color: #ff0000 !important;
        }

        .banner {
            position: relative;
            height: 400px;
            background: url('https://images.unsplash.com/photo-1519681393784-d120267933ba') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .text-content h4 {
            font-size: 1.5rem;
            font-weight: 400;
            color: #ff0000;
        }

        .text-content h2 {
            font-size: 3rem;
            font-weight: 700;
        }

        .latest-products {
            padding: 50px 0;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-heading h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
        }

        .section-heading a {
            color: #ff0000;
            text-decoration: none;
        }

        .section-heading a:hover {
            text-decoration: underline;
        }

        .product-item {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-5px);
        }

        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px; /* Adjusted for side-by-side */
        }

        .down-content {
            padding: 20px;
        }

        .down-content h4 {
            font-size: 1.25rem;
            font-weight: 500;
            color: #333;
        }

        .down-content h6 {
            font-size: 0.9rem;
            color: #777;
        }

        .down-content p {
            font-size: 0.95rem;
            color: #555;
        }

        .btn-info {
            background-color: #ff0000;
            border: none;
        }

        .btn-info:hover {
            background-color: #e60000;
        }

        .pagination .page-link {
            color: #ff0000;
        }

        .pagination .page-item.active .page-link {
            background-color: #ff0000;
            border-color: #ff0000;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo $message['blog']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><?php echo $message['all posts']; ?></a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="addPost.php"><?php echo $message['add new post']; ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] == "ar"): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="inc/changeLang.php?lang=en">English</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="inc/changeLang.php?lang=ar">العربية</a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="handle/logout.php"><?php echo $message['log out']; ?></a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><?php echo $message['login']; ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <div class="banner header-text">
        <div class="text-content">
            <h4>Best Offer</h4>
            <h2>New Arrivals On Sale</h2>
        </div>
    </div>

    <!-- Latest Products -->
    <div class="latest-products">
        <div class="container">
            <div class="row">
                <?php
                require_once 'inc/success.php';
                require_once 'inc/errors.php';
                ?>
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>View All products</h2>
                    </div>
                </div>

                <?php
                $limit = 3;

                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                } else {
                    $page = 1;
                }

                $query = "SELECT COUNT(id) as total FROM posts";
                $runQuery = mysqli_query($conn, $query);
                $total = mysqli_fetch_assoc($runQuery)['total'];

                $numberOfPages = ceil($total / $limit);
                $offset = ($page - 1) * $limit;

                if ($page > $numberOfPages) {
                    header("location:{$_SERVER['PHP_SELF']}?page=$numberOfPages");
                } elseif ($page < 1) {
                    header("location:{$_SERVER['PHP_SELF']}?page=1");
                }

                $query = "SELECT id, title, SUBSTR(body, 1, 48) as body, image, created_at FROM posts LIMIT $limit OFFSET $offset";
                $runQuery = mysqli_query($conn, $query);

                if (mysqli_num_rows($runQuery) > 0) {
                    $posts = mysqli_fetch_all($runQuery, MYSQLI_ASSOC);
                } else {
                    header("location:404.php");
                    $msg = "No data found";
                }
                ?>

                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="col-12">
                            <div class="product-item">
                                <div class="row">
                                    <!-- Image Column -->
                                    <div class="col-md-6">
                                        <a href="viewPost.php?id=<?php echo $post['id']; ?>">
                                            <img src="uploads/<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>">
                                        </a>
                                    </div>
                                    <!-- Content Column -->
                                    <div class="col-md-6">
                                        <div class="down-content">
                                            <a href="viewPost.php?id=<?php echo $post['id']; ?>">
                                                <h4><?php echo $post['title']; ?></h4>
                                            </a>
                                            <h6><?php echo $post['created_at']; ?></h6>
                                            <p><?php echo $post['body'] . "... read more"; ?></p>
                                            <div class="d-flex justify-content-end">
                                                <a href="viewPost.php?id=<?php echo $post['id']; ?>" class="btn btn-info">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?php echo $msg; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="container d-flex justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="<?php if ($page > 1) echo $_SERVER['PHP_SELF'] . "?page=" . ($page - 1); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $numberOfPages; $i++): ?>
                    <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . "?page=" . $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if ($page >= $numberOfPages) echo 'disabled'; ?>">
                    <a class="page-link" href="<?php if ($page < $numberOfPages) echo $_SERVER['PHP_SELF'] . "?page=" . ($page + 1); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require_once 'inc/footer.php'; ?>