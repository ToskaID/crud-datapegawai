<?php

require 'connection.php';

$id = $_GET["id"];
// Kita gabungkan (JOIN) tabel pegawai,departemen,jabatan di sini
// Pastikan nama kolom 'departemen_id' di tabel pegawai dan 'id' di tabel departemen sudah benar
$sql = "SELECT p.*, d.nama_departemen, j.nama_jabatan
        FROM pegawai AS p
        LEFT JOIN departemen AS d ON p.departemen_id = d.id
        LEFT JOIN jabatan AS j ON p.jabatan_id = j.id WHERE p.id = '$id'";

$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2><?=$data_pegawai["nama"]?></h2>
    <table border="0">
        <tr>
            <td>Nama</td>
            <td> : <?=$data_pegawai["nama"] ?></td>
        </tr>
        <tr>
            <td>Nama Departemen</td>
            <td> : <?=$data_pegawai["nama_departemen"] ?></td>
        </tr>
         <tr>
            <td>Nama Jabatan</td>
            <td> : <?=$data_pegawai["nama_jabatan"] ?></td>
        </tr>
         <tr>
            <td>Jenis Kelamin</td>
            <td> : <?=$data_pegawai["jenis_kelamin"] ?></td>
        </tr>
        <tr>
            <td>No HandPhone</td>
            <td> : <?=$data_pegawai["no_handphone"] ?></td>
        </tr>
         <tr>
            <td>Alamat</td>
            <td> : <?=$data_pegawai["tempat_lahir"] ?></td>
        </tr>
         <tr>
            <td>Jenis Kelamin</td>
            <td> : <?=$data_pegawai["tanggal_lahir"] ?></td>
        </tr>
         <tr>
            <td>Jenis Kelamin</td>
            <td> : <?=$data_pegawai["alamat"] ?></td>
        </tr>
         <tr>
            <td>Status Pernikahan</td>
            <td> : <?=$data_pegawai["status_pernikahan"] ?></td>
        </tr>
        
  
    </table>
   
    
               
   
</body>
</html>