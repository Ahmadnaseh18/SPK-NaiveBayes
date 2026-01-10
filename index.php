<?php
session_start();
if(isset($_SESSION['login'])){
    header("location:dashboard.php");
}
header("location:auth/login.php");
?>
