<?php

require 'connection.php';

$id = $_GET["id"];
// Kita gabungkan (JOIN) tabel pegawai,departemen,jabatan di sini
// Pastikan nama kolom 'departemen_id' di tabel pegawai dan 'id' di tabel departemen sudah benar
$sql = "SELECT p.*, d.nama_departemen, j.nama_jabatan
        FROM pegawai AS p
        LEFT JOIN departemen AS d ON p.departemen_id = d.id
        LEFT JOIN jabatan AS j ON p.jabatan_id = j.id WHERE p.id = '$id'";

// 1. Ambil data spesifik pegawai yang mau diedit
$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_assoc($query);

// 2. Ambil SEMUA data departemen untuk dropdown
$query_dept = mysqli_query($connection, "SELECT * FROM departemen");

// 3. Ambil SEMUA data jabatan untuk dropdown
$query_jabatan = mysqli_query($connection, "SELECT * FROM jabatan");


if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_handphone = $_POST['no_handphone'];
    $status_pernikahan = $_POST['status_pernikahan'];
    $departemen_id = $_POST['departemen_id'];
    $jabatan_id = $_POST['jabatan_id'];

    try{
        mysqli_query(
            $connection, "UPDATE pegawai SET
            nama ='$nama',
            jenis_kelamin ='$jenis_kelamin',
            alamat ='$alamat',
            tempat_lahir ='$tanggal_lahir',
            no_handphone ='$no_handphone',
            status_pernikahan ='$status_pernikahan',
            departemen_id ='$departemen_id',
            jabatan_id ='$jabatan_id'
             WHERE id= '$id'"
         );
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
    <a href="index.php">Home</a> | 
    <a href="list-pegawai.php">Pegawai</a> | 
    <a href="list-departemen.php">Departemen</a> | 
    <a href="list-jabatan.php">Jabatan</a>

    <h2>Edit Nama Pegawai</h2>
    <form action="" method="POST">
        <label>Nama Pegawai</label><br>
        <input type="text" name="nama" value="<?=$data_pegawai["nama"]?>"  />
        <br><br>

        <label>Nama Departemen</label><br>
        <select name="departemen_id">
            <?php while($d = mysqli_fetch_assoc($query_dept)) : ?>
                <option value="<?= $d['id'] ?>" <?= ($d['id'] == $data_pegawai['departemen_id']) ? 'selected' : '' ?>>
                    <?= $d['nama_departemen'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        
        <label>Nama Jabatan</label><br>
        <select name="jabatan_id">
            <?php while($j = mysqli_fetch_assoc($query_jabatan)) : ?>
                <option value="<?= $j['id'] ?>" <?= ($j['id'] == $data_pegawai['jabatan_id']) ? 'selected' : '' ?>>
                    <?= $j['nama_jabatan'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Jenis Kelamin</label><br>
        <select name="jenis_kelamin">
            <option value="L" <?= ($data_pegawai['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>
                 Laki-laki
            </option>
            <option value="P" <?= ($data_pegawai['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>
                Perempuan
            </option>
        </select>
        <br><br>

        <label>No HandPhone</label><br>
        <input type="text" name="no_handphone" value="<?=$data_pegawai["no_handphone"]?>"  />
        <br><br>

        <label>Tempat Lahir</label><br>
        <input type="text" name="tempat_lahir" value="<?=$data_pegawai["tempat_lahir"]?>"  />
        <br><br>

        <label>Tanggal Lahir</label><br>
        <input type="date" name="tanggal_lahir" value="<?=$data_pegawai["tanggal_lahir"]?>"  />
        <br><br>

        <label>Alamat</label><br>
        <input type="text" name="alamat" value="<?=$data_pegawai["alamat"]?>"  />
        <br><br>

        <label>Status Pernikahan</label><br>
        <select name="status_pernikahan">
            <option value="belum menikah" <?= ($data_pegawai['status_pernikahan'] == 'belum menikah') ? 'selected' : '' ?>>
                 Belum Menikah
            </option>
            <option value="menikah" <?= ($data_pegawai['status_pernikahan'] == 'menikah') ? 'selected' : '' ?>>
                Menikah
            </option>
        </select>
        <br><br>

        <button type="submit" name="submit">Edit Data</button>

    </form>
</body>
</html>