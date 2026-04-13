<?php
require 'connection.php';

//Proteksi: Jika belum login dan bukan admin di tendang ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fungsi sederhana untuk membuat UUID v4
function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// ambil data departemen untuk field dropdown departement 
$departemen = mysqli_query($connection, "SELECT * FROM departemen");

// ambil data jabatan untuk field dropdowm jabatan
$jabatan = mysqli_query($connection, "SELECT * FROM jabatan");

// Variabel untuk menampung pesan error
$error_msg = ""; 

if (isset($_POST['register'])) {
    
//  buat variabel yang menampung dari UUID
    $new_uuid = generate_uuid();

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_handphone'];
    $status_pernikahan = $_POST['status_pernikahan'];
    $departemen_id = $_POST['departemen_id'];
    $jabatan_id = $_POST['jabatan_id'];


    try{

        // Cek dulu apakah username sudah ada 
        $check_user = mysqli_query($connection, "SELECT username FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check_user) > 0) {
            throw new Exception("Username '$username' sudah terdaftar! Gunakan yang lain.");
        }

        // B. INPUT KE TABEL USERS (Masukkan UUID yang sudah kita buat tadi)
        $sql_user = "INSERT INTO users (id, username, password, level) VALUES 
        ('$new_uuid', '$username', '$password', 'user')";
        mysqli_query($connection, $sql_user);

        // C. INPUT KE TABEL PEGAWAI
        $sql_pegawai = "INSERT INTO pegawai (user_id,nama, jenis_kelamin, alamat, tempat_lahir, 
                    tanggal_lahir, no_handphone, status_pernikahan, departemen_id, jabatan_id)
                    VALUES ('$new_uuid','$nama','$jenis_kelamin','$alamat','$tempat_lahir','$tanggal_lahir',
                    '$no_hp','$status_pernikahan','$departemen_id','$jabatan_id') ";
                    mysqli_query($connection, $sql_pegawai);
                    echo "<script>alert('Data Berhasil Disimpan!'); window.location='list-pegawai.php';</script>";
    }catch(Exception $e){
         $error_msg = $e->getMessage();
    }

   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Tambah Pegawai</h2>
    <?php if($error_msg): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px;">
            <strong>Gagal:</strong> <?= $error_msg; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label for="">Nama</label><br>
        <input type="text" name="nama"><br><br>

        <label>Username</label><br>
    <input type="text" name="username" required><br><br>
    
    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

        <label for="">Jenis Kelamin</label><br>
        <select name="jenis_kelamin">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select><br><br>

        <!--  DROPDOWN DEPARTEMEN -->
        <label for="">Departemen</label><br>
        <select name="departemen_id">
            <option value="">-- Pilih Departemen --</option>
            <?php 
            mysqli_data_seek($departemen, 0); // Kembalikan kursor ke baris awal
            while($d = mysqli_fetch_assoc($departemen)) : 
            ?>
                <option value="<?= $d['id'] ?>"><?= $d['nama_departemen'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <!--  DROPDOWN JABATAN -->
        <label for="">Jabatan</label><br>
        <select name="jabatan_id">
            <option value="">-- Pilih Jabatan --</option>
            <?php 
            mysqli_data_seek($jabatan, 0); // Kembalikan kursor ke baris awal
            while($j = mysqli_fetch_assoc($jabatan)) : 
            ?>
                <option value="<?= $j['id'] ?>"><?= $j['nama_jabatan'] ?></option>
            <?php endwhile; ?>
        </select><br><br>
        
        <label for="">Alamat</label><br>
        <textarea name="alamat"></textarea><br><br>

        <label for="">Tempat Lahir</label><br>
        <input type="text" name="tempat_lahir"><br><br>

        <label for="">Tanggal Lahir</label><br>
        <input type="date" name="tanggal_lahir"><br><br>

        <label for="">No HP</label><br>
        <input type="text" name="no_handphone"><br><br>

        <label for="">Status Pernikahan</label><br>
        <select name="status_pernikahan">
            <option value="belum menikah">Belum Menikah</option>
            <option value="menikah">Menikah</option>
        </select><br><br>

    

        <button type="submit" name="register">Register Data</button> 
        <button><a href="list-pegawai.php">kembali</a></button> 

    </form>
</body>
</html>