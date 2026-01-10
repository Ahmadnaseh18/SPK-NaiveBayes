<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "spk_kelulusan";

$koneksi = mysqli_connect($host,$user,$pass,$db);

if(!$koneksi){
    die("Koneksi gagal");
}
?>
