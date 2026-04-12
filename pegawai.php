<?php
require 'connection.php';

// ambil data departemen untuk field dropdown departement 
$departemen = mysqli_query($connection, "SELECT * FROM departemen");

// ambil data jabatan untuk field dropdowm jabatan
$jabatan = mysqli_query($connection, "SELECT * FROM jabatan");

if (isset($_POST['submit'])) {
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
        mysqli_query($connection, "
        INSERT INTO pegawai 
        (nama, jenis_kelamin, alamat, tempat_lahir, tanggal_lahir, no_handphone, status_pernikahan, departemen_id, jabatan_id)
        VALUES 
        ('$nama','$jenis_kelamin','$alamat','$tempat_lahir','$tanggal_lahir','$no_hp','$status_pernikahan','$departemen_id','$jabatan_id')
    ");
    header("Location: list-pegawai.php");
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
    <h2>Tambah Pegawai</h2>

    <form method="POST">
        <label for="">Nama</label><br>
        <input type="text" name="nama"><br><br>

        <label for="">Jenis Kelamin</label><br>
        <select name="jenis_kelamin">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
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

        <!-- 🔥 DROPDOWN DEPARTEMEN -->
        <label for="">Departemen</label><br>
        <select name="departemen_id">
            <option value="">-- Pilih Departemen --</option>
            <?php while($d = mysqli_fetch_assoc($departemen)) { ?>
                <option value="<?= $d['id'] ?>">
                    <?= $d['nama_departemen'] ?>
                </option>
            <?php } ?>
        </select><br><br>

        <!-- 🔥 DROPDOWN JABATAN -->
       <label for="">Jabatan</label><br>
        <select name="jabatan_id">
            <option value="">-- Pilih Jabatan --</option>
            <?php while($j = mysqli_fetch_assoc($jabatan)) { ?>
                <option value="<?= $j['id'] ?>">
                    <?= $j['nama_jabatan'] ?>
                </option>
            <?php } ?>
        </select><br><br>

        <button type="submit" name="submit">Tambah Data</button>

    </form>
</body>
</html>