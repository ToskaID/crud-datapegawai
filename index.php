<?php
require 'connection.php';
session_start();

// Jika belum login ATAU bukan admin, tendang ke login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// echo "Selamat Datang Admin, " . $_SESSION['username']



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
        <a href="list-departemen.php">Departemen</a> | 
        <a href="list-jabatan.php">Jabatan</a>|
        <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout (<?= $_SESSION['username']; ?>)</a>
    </nav>

    

    <h2>Welcome</h2>
</body>
</html>