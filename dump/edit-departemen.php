<?php
require 'connection.php';

//Proteksi: Jika belum login dan bukan admin di tendang ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
// Kita gabungkan (JOIN) tabel pegawai,departemen,jabatan di sini
// Pastikan nama kolom 'departemen_id' di tabel pegawai dan 'id' di tabel departemen sudah benar
$sql = "SELECT nama_departemen FROM departemen WHERE id = '$id'";

$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $nama_departemen = $_POST['nama_departemen'];
    try{
        mysqli_query(
            $connection, "UPDATE  departemen SET nama_departemen ='$nama_departemen' WHERE id= '$id'"
         );
          echo "<script>alert('Data Berhasil Diedit!'); window.location='list-departemen.php';</script>";
    }catch(Exception $e){
    }catch(Exception $e){
        echo "Gagal Inset ke database " . $e->getMessage();
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
    <a href="index.php">Home</a> | 
    <a href="list-pegawai.php">Pegawai</a> | 
    <a href="list-departemen.php">Departemen</a> | 
    <a href="list-jabatan.php">Jabatan</a>

      <h2>Edit Nama Departemen</h2>

    <form action="" method="POST">
        <label>Nama Departemen</label><br>
        <input type="text" name="nama_departemen" value="<?=$data_pegawai["nama_departemen"]?>"  />
        <br><br>
        <button type="submit" name="submit">Edit Data</button>
    </form>
   
</body>
</html>