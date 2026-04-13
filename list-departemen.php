<?php

require 'connection.php';
// // 2. Proteksi: Jika belum login, tendang ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($connection, "SELECT * FROM departemen");
$departemen = mysqli_fetch_all($query,MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav>
        <a href="index.php">Home</a> | 
        <a href="list-pegawai.php">Pegawai</a> | 
        <?php if ($_SESSION['level'] == 'admin') : ?>
        <a href="list-departemen.php">Departemen</a> | 
        <a href="list-jabatan.php">Jabatan</a> |
         <?php endif; ?>
        <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout (<?= $_SESSION['username']; ?>)</a>
    </nav>

    <h2>List Data Departemen</h2>
    <a href="departemen.php">Tambah Data</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Departemen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php foreach($departemen as $index => $d) : ?>
            <tr>
                <td><?=$index+1 ?></td>
                <td><?=$d["nama_departemen"] ?></td>
                <td>
                    <a href="edit-departemen.php?id=<?=$d["id"]?>">Edit</a> | 
                    <a href="delete-departemen.php?id=<?=$d["id"]?>">Delete</a>
                </td>
              
            </tr>
        <?php endforeach ?>

    </table>
</body>
</html>