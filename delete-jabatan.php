<?php

require 'connection.php';
//Proteksi: Jika belum login dan bukan admin di tendang ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$id = $_GET["id"];

if(isset($id)){
    try{
        mysqli_query($connection,"DELETE FROM jabatan WHERE id = '$id'");
        echo "<script>alert('Data Berhasil Dihapus!'); window.location='list-jabatan.php';</script>";
    }catch(Exception $e){
        echo "Failed delete data " .$e -> getMessage();
    }
}


 




?>