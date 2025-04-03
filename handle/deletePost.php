<?php
require_once '../inc/conn.php';
if(!isset($_SESSION['user_id'])){
  header("location:login.php");
  exit();
}

if(isset($_POST['submit']) && isset($_GET['id'])){
    $id = $_GET['id'];

    $query = "select image from posts where id=$id";
    $runQeruy = mysqli_query($conn,$query);
    if(mysqli_num_rows($runQeruy)==1){

      $oldImage =   mysqli_fetch_assoc($runQeruy)['image'];

      $query = "delete from posts where id=$id";
      $runQeruy = mysqli_query($conn,$query);

      if($runQeruy) {

        if(! empty($oldImage)){
            unlink("../uploads/$oldImage");
        }
        $_SESSION['success'] = "post deleted successfuly";

        header("location:../index.php");

      }else{
        $_SESSION['errors'] = "error while delete";
        header("location:../index.php");
      }


    }else{
    header("location:../404.php");
    }

}else{
    header("location:../404.php");
}