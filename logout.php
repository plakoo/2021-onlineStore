<?php
    session_start();
    header('Content-type:text/html;charset=utf-8');
    if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
            session_destroy();
            header('location:login.php');
        }else{
            echo "<script> alert('Log out failed, please try again.');window.history.go(-1);</script>";
        }
?>