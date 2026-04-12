<?php

require 'connection.php';
$id = $_GET["id"];

if(isset($id)){
    try{
        mysqli_query($connection,"DELETE FROM pegawai WHERE id = '$id'");
        header("Location:list-pegawai.php");
    }catch(Exception $e){
        echo "Failed delete data " .$e -> getMessage();
    }
}


 




?>