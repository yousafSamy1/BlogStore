<?php require_once 'inc/header.php' ?>
<?php require_once 'inc/conn.php' ?>
    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="banner header-text">
      <div class="owl-banner owl-carousel">
        <div class="banner-item-01">
          <div class="text-content">
          </div>
        </div>
        <div class="banner-item-02">
          <div class="text-content">
          </div>
        </div>
        <div class="banner-item-03">
          <div class="text-content">
          </div>
        </div>
      </div>
    </div>
    <!-- Banner Ends Here -->

    <div class="latest-products">
      <div class="container">
        <div class="row">
          <?php
            require_once 'inc/success.php';
            require_once 'inc/errors.php';
          ?>
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Latest Posts</h2>
              <a href="products.php" class="btn btn-outline-primary float-right">
                View all Posts <i class="fa fa-angle-right ml-1"></i>
              </a>
              <div class="clearfix"></div>
            </div>
          </div>

          <?php
            $limit = 3;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max(1, $page);  

            $query = "SELECT COUNT(id) AS total FROM posts";
            $runQuery = mysqli_query($conn, $query);
            $total = mysqli_fetch_assoc($runQuery)['total'];
            $numberOfPages = ceil($total / $limit); 

            if($page > $numberOfPages) {
                header("Location: {$_SERVER['PHP_SELF']}?page=$numberOfPages");
                exit;
            }

            $offset = ($page - 1) * $limit;
            $query = "SELECT id, title, SUBSTR(`body`, 1, 100) AS body, image, created_at 
                      FROM posts 
                      ORDER BY created_at DESC 
                      LIMIT $limit OFFSET $offset";
            $runQuery = mysqli_query($conn, $query);

            if(mysqli_num_rows($runQuery) > 0) {
                $posts = mysqli_fetch_all($runQuery, MYSQLI_ASSOC);
            } else {
                $msg = "<div class='col-12 text-center py-5'>
                          <div class='alert alert-info'>No posts found</div>
                        </div>";
            }
          ?>

          <?php if(!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
              <div class="col-md-4 mb-4">
                <div class="product-item h-100">
                  <div class="post-thumbnail">
                    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" 
                         alt="<?php echo htmlspecialchars($post['title']); ?>" 
                         class="img-fluid">
                    <div class="post-date-overlay">
                      <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                    </div>
                  </div>
                  <div class="down-content p-4">
                    <h4 class="mb-3"><?php echo htmlspecialchars($post['title']); ?></h4>
                    <p class="text-muted mb-4"><?php echo htmlspecialchars($post['body']) . '...'; ?></p>
                    <div class="text-right">
                      <a href="viewPost.php?id=<?php echo $post['id']; ?>" 
                         class="btn btn-outline-primary btn-sm">
                        Read More <i class="fa fa-arrow-right ml-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <?php echo $msg; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($numberOfPages > 1): ?>
        <div class="row mt-5">
          <div class="col-md-12">
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page == 1) echo 'disabled'; ?>">
                  <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . ($page - 1); ?>">
                    &laquo; Previous
                  </a>
                </li>

                <?php 
                  $startPage = max(1, $page - 2);
                  $endPage = min($numberOfPages, $page + 2);
                  
                  if($startPage > 1) {
                      echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                      if($startPage > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                  }
                  
                  for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li class="page-item <?php if($page == $i) echo 'active'; ?>">
                      <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . $i; ?>">
                        <?php echo $i; ?>
                      </a>
                    </li>
                  <?php endfor;
                  
                  if($endPage < $numberOfPages) {
                      if($endPage < $numberOfPages - 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                      echo '<li class="page-item"><a class="page-link" href="?page='.$numberOfPages.'">'.$numberOfPages.'</a></li>';
                  }
                ?>

                <li class="page-item <?php if($page == $numberOfPages) echo 'disabled'; ?>">
                  <a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . ($page + 1); ?>">
                    Next &raquo;
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

<?php require_once 'inc/footer.php' ?>