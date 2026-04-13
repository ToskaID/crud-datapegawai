<?php

require 'connection.php';
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];

if(isset($id)){
    try{
        mysqli_query($connection,"DELETE FROM pegawai WHERE id = '$id'");
        echo "<script>alert('Data Berhasil Dihapus!'); window.location='list-pegawai-new.php';</script>";
    }catch(Exception $e){
        echo "Failed delete data " .$e -> getMessage();
    }
}


 




?>