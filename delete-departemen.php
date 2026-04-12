<?php

require 'connection.php';
$id = $_GET["id"];

if(isset($id)){
    try{
        mysqli_query($connection,"DELETE FROM departemen WHERE id = '$id'");
        header("Location:list-departemen.php");
    }catch(Exception $e){
        echo "Failed delete data " .$e -> getMessage();
    }
}


 




?>