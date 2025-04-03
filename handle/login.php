<?php
require_once '../inc/conn.php';

if( isset($_SESSION['user_id'])){
    header("location:../index.php");
    exit();
}
if(isset($_POST['submit'])){

    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));

    $errors = [];
    if(empty($email)){
        $errors[] = "email is requried";
    }   elseif(! filter_var($email , FILTER_VALIDATE_EMAIL)){
        $errors[] = "email not correct";
    }

    if(empty($password)){
        $errors[] = "password is requried";
    }   elseif(strlen($password)<6){
        $errors[] = "password less than 6";
    }

    if(empty($errors)){

    $query = "select id, name , password from users where email ='$email' ";
    $runQeury = mysqli_query($conn,$query);
    if(mysqli_num_rows($runQeury)==1){
       $user =  mysqli_fetch_assoc($runQeury);
       $user_id = $user['id'];
       $hashPassword = $user['password'];
       $name = $user['name'];

       $is_verify =  password_verify($password,$hashPassword);

       if($is_verify){
        $_SESSION['user_id'] = $user_id;
        $_SESSION['success'] = "welcome $name";
        header("location:../index.php");

       }else{
        $_SESSION['errors'] = ["credintials not correct"];
        header("location:../login.php");
       }
    }else{
        $_SESSION['errors'] = ["credintials not correct"];
        header("location:../login.php");
        
    }
}else{
    $_SESSION['errors'] = $errors;
    header("location:../login.php");

}



}else{
    header("location:../login.php");
}