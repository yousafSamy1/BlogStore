<?php
// connection
require_once '../inc/conn.php';

if(!isset($_SESSION['user_id'])){
    header("location:login.php");
  }

// cehck 
if(isset($_POST['submit'])){
    // catch  , filter 
    $title = trim(htmlspecialchars($_POST['title']));
    $body = trim(htmlspecialchars($_POST['body']));

    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size']/(1024*1024); // mb
    $image_error = $image['error'];
    $image_ext = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));

    $errors = [];
// validation data
    // title
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

    // image

        if($image_error !=0) {
            $errors[] = "image not correct";
        }elseif($image_size >1) {
            $errors[] = "image large size";
        }elseif(! in_array($image_ext ,['png','jpg','jpeg'])){
            $errors[] = "choose correct image";
        }

        $newName = uniqid().".$image_ext";


// echeck errors
if(empty($errors)) {
    $query = "insert into posts(`title`,`body`,`image`,`user_id`) values('$title','$body','$newName',1)";
    $runQeury = mysqli_query($conn,$query);
    if($runQeury) {
        
        // insert -> move 
        move_uploaded_file($image_tmp_name,"../uploads/$newName");
        $_SESSION['success'] = "post inserted successfuly";
        header("location:../index.php");
    }else{
        $_SESSION['errors'] = ["error while insert"];
        header("location:../addPost.php");   
    }
} else{

    $_SESSION['title'] = $title;
    $_SESSION['errors'] = $errors;
    header("location:../addPost.php");
}
// msg 

}else{
    header("location:../addPost.php");
}

