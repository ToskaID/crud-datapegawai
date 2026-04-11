<?php

try{
  $connection = mysqli_connect("localhost","root","","datapegawai");
}catch(Exception $e){
  echo "Failed Connect : " . $e->getMessage();
}
?>