<?php
require 'connection.php';

// Proteksi halaman: Jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil UUID dari session
$user_id_session = $_SESSION['id'];

// Query JOIN untuk mengambil data lengkap pegawai milik user yang login
$sql = "SELECT u.username, p.*, d.nama_departemen, j.nama_jabatan 
        FROM users AS u
        LEFT JOIN pegawai AS p ON u.id = p.user_id
        LEFT JOIN departemen AS d ON p.departemen_id = d.id
        LEFT JOIN jabatan AS j ON p.jabatan_id = j.id
        WHERE u.id = '$user_id_session'";

$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan (misal: user baru yang belum diisi data pegawainya)
// if (!$data_pegawai) {
//     echo "Data profil belum lengkap. Silahkan hubungi admin.";
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <style>
        .profile-card { border: 1px solid #ccc; padding: 20px; width: 400px; border-radius: 8px; }
        .label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Home</a> | 
        <a href="profile.php">Profile</a> | 
        <a href="list-pegawai.php">Pegawai</a> | 
        <?php if ($_SESSION['level'] == 'admin') : ?>
        <a href="list-departemen.php">Departemen</a> | 
        <a href="list-jabatan.php">Jabatan</a> |
         <?php endif; ?>
        <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout (<?= $_SESSION['username']; ?>)</a>
    </nav>
    <h2>Profil Pengguna</h2>

    <table border="0">
        <tr>
            <td>Nama</td>
            <td> : <?=$data_pegawai["nama"] ?></td>
        </tr>
        <tr>
            <td>Username</td>
            <td> : <?=$data_pegawai["username"] ?></td>
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
            <td>Tempat Lahir</td>
            <td> : <?=$data_pegawai["tempat_lahir"] ?></td>
        </tr>
         <tr>
            <td>Tanggal Lahir</td>
            <td> : <?=$data_pegawai["tanggal_lahir"] ?></td>
        </tr>
         <tr>
            <td>Alamat</td>
            <td> : <?=$data_pegawai["alamat"] ?></td>
        </tr>
         <tr>
            <td>Status Pernikahan</td>
            <td> : <?=$data_pegawai["status_pernikahan"] ?></td>
        </tr>
        
    </table>
    
    >
</body>
</html>