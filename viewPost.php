<?php require_once 'inc/header.php' ?>
<?php require_once 'inc/conn.php' ?>

    <!-- Page Content -->
    <div class="page-heading products-heading header-text">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="text-content">
              <h4>view Post</h4>
              <h2>view new personal post</h2>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
      if(isset($_GET['id'])){
        $id = $_GET['id'];
      }else{
        header("location:404.php");
      }

      $query = "select posts.* , users.name from posts join users
        on users.id = posts.user_id
      where posts.id=$id";
      $runQuery = mysqli_query($conn,$query);
      if(mysqli_num_rows($runQuery)==1){

        $post = mysqli_fetch_assoc($runQuery);

      }else{
        header("location:404.php");
        
      }

    ?>
    
    <div class="best-features about-features">
      <div class="container">
        <div class="row">
          <?php
            require_once 'inc/success.php';
          ?>
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Our Background</h2>
            </div>
          </div>
          <div class="col-md-6">
            <div class="right-image">
              <img src="uploads/<?php echo $post['image'] ?>" alt="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="left-content">
              <h4><?php echo $post['title'] ?></h4>
              <p><?php echo $post['body'] ?></p>
              <p>created_at : <?php echo $post['created_at'] ?></p>
              <p>created_by : <?php echo $post['name'] ?></p>
              
              <?php if(isset($_SESSION['user_id'])): ?>
              <div class="d-flex justify-content-center">
                  <a href="editPost.php?id=<?php echo $post['id'] ?>" class="btn btn-success mr-3 "> edit post</a>
              
                  <!--   -->
                  <form action="handle/deletePost.php?id=<?php echo $id ?>" method="post">
                    <button type="submit" name="submit" class="btn btn-danger">delete</button>
                  </form>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
</div>

    <?php require_once 'inc/footer.php' ?>
