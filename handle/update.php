<?php
require_once '../inc/conn.php';

if(!isset($_SESSION['user_id'])){
    header("location:login.php");

  }
  
if(isset($_POST['submit']) && isset($_GET['id'])){
    $id = $_GET['id'];

    $title = trim(htmlspecialchars($_POST['title']));
    $body = trim(htmlspecialchars($_POST['body']));

    $errors = [];

    if(empty($title)){
        $errors[] = "title is requried";
    }elseif(is_numeric($title)){
        $errors[] = "title must be string";
    }

    // body
    if(empty($body)){
        $errors[] = "body is requried";
    }elseif(is_numeric($body)){
        $errors[] = "body must be string";
    }

    $query = "select image from posts where id=$id";
    $runQeruy = mysqli_query($conn,$query);
    if(mysqli_num_rows($runQeruy)==1){

      $oldImage =   mysqli_fetch_assoc($runQeruy)['image'];

      if(!empty($_FILES['image']['name'])){
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size']/(1024*1024); // mb
        $image_error = $image['error'];
        $image_ext = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));

        if($image_error !=0) {
            $errors[] = "image not correct";
        }elseif($image_size >1) {
            $errors[] = "image large size";
        }elseif(! in_array($image_ext ,['png','jpg','jpeg'])){
            $errors[] = "choose correct image";
        }

        $newName = uniqid().".$image_ext";
      }else{
        $newName = $oldImage;
      }

      if(empty($errors)){

          $query = "update posts set `title`='$title' , `body`='$body',`image`='$newName' where id=$id";
          
          $runQuery = mysqli_query($conn,$query);
          
          if($runQeruy) {
              // 

            if(!empty($_FILES['image']['name'])){
                unlink("../uploads/$oldImage");
                move_uploaded_file($image_tmp_name,"../uploads/$newName");
            }

            $_SESSION['success'] = "post updated successfuly";
            header("location:../viewPost.php?id=$id");


            }else{
                
                $_SESSION['errors'] = ["error while update"];
            header("location:../editPost.php?id=$id");
            }
        }else{
            $_SESSION['errors'] = $errors;
            header("location:../editPost.php?id=$id");
        }



    }else{ 
        header("location:../404.php");

    }

}else{
    header("location:../404.php");
}