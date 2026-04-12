<?php

require 'connection.php';

// Kita gabungkan (JOIN) tabel pegawai,departemen,jabatan di sini
// Pastikan nama kolom 'departemen_id' di tabel pegawai dan 'id' di tabel departemen sudah benar
$sql = "SELECT p.*, d.nama_departemen, j.nama_jabatan
        FROM pegawai AS p
        LEFT JOIN departemen AS d ON p.departemen_id = d.id
        LEFT JOIN jabatan AS j ON p.jabatan_id = j.id";

$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_all($query, MYSQLI_ASSOC);
?>
;

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>List Data Pegawai</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php foreach($data_pegawai as $index => $p) : ?>
            <tr>
                <td><?=$index+1 ?></td>
                <td><?=$p["nama"] ?></td>
                <td><?=$p["nama_departemen"] ?></td>
                <td><?=$p["nama_jabatan"] ?></td>
                <td><?=$p["jenis_kelamin"] ?></td>   
               
            </tr>
        <?php endforeach; ?>

    </table>
</body>
</html>