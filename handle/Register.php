<?php
require_once '../inc/conn.php';

if( isset($_SESSION['user_id'])){
    header("location:../index.php");
    exit();
}

if(isset($_POST['submit'])){

    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));
    $phone = trim(htmlspecialchars($_POST['phone']));

    $errors = [];
    // validation
    if(empty($name)){
        $errors[] = "name is requried";
    }   elseif(is_numeric($name)){
        $errors[] = "name must be string";
    }

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

    if(empty($phone)){
        $errors[] = "phone is requried";
    }   elseif(! is_numeric($phone)){
        $errors[] = "phone must be number";
    }

    $password = password_hash($password,PASSWORD_DEFAULT);

    if(empty($errors)){

        $query = "insert into users(`name`,`email`,`password`,`phone`) values('$name','$email','$password','phone')";
        $runQuery = mysqli_query($conn,$query);
        if($runQuery) {
            $_SESSION['success'] = "you registerd successfuly";
            header("location:../login.php");

        }else{
            $_SESSION['errors'] = ['error while register'];
            header("location:../Register.php");
        }

    }else{
        $_SESSION['errors'] = $errors;
    header("location:../Register.php");

        
    }
    // insert 

    //  index or login 

}else{
    header("location:../Register.php");
}