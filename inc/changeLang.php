<?php

session_start();
if(isset($_GET['lang'])){

    $lang = $_GET['lang'];
    
    if($lang == "ar"){
        $_SESSION['lang'] = "ar";
    }else{
        $_SESSION['lang'] = "en";

    }
}else{

    $_SESSION['lang'] = "en";
}


header("location:".$_SERVER["HTTP_REFERER"]);

// echo "<pre>";
// var_dump($_SERVER);