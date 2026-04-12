<?php

require 'connection.php';

$query = mysqli_query($connection, "SELECT * FROM jabatan");
$jabatan = mysqli_fetch_all($query,MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="index.php">Home</a> | 
    <a href="list-pegawai.php">Pegawai</a> | 
    <a href="list-departemen.php">Departemen</a> | 
    <a href="list-jabatan.php">Jabatan</a>

    <h2>List Data Jabatan</h2>
    <a href="jabatan.php">Tambah Data</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jabatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php foreach($jabatan as $index => $j) : ?>
            <tr>
                <td><?=$index+1 ?></td>
                <td><?=$j["nama_jabatan"] ?></td>
                <td>
                    <a href="edit-jabatan.php?id=<?=$j["id"]?>">Edit</a> | 
                    <a href="delete.php?id=<?=$j["id"]?>">Delete</a>
                </td>
                
            </tr>
        <?php endforeach ?>

    </table>
</body>
</html>