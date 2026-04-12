<?php

require 'connection.php';
$id = $_GET["id"];

if(isset($id)){
    try{
        mysqli_query($connection,"DELETE FROM jabatan WHERE id = '$id'");
        header("Location:list-jabatan.php");
    }catch(Exception $e){
        echo "Failed delete data " .$e -> getMessage();
    }
}


 




?>