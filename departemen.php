<?php

require 'connection.php';


if (isset($_POST['submit'])) {
    $nama_departemen = $_POST['nama_departemen'];
    try{
        mysqli_query(
            $connection, "INSERT INTO departemen(nama_departemen) VALUES ('$nama_departemen')"
         );
         header("Location: index.php");
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
    <title>add departement</title>
</head>
<body>
    <h1>Tambah Departemen</h1>
    <form method="POST" action="">
        <label for="">Nama Departemen</label><br>
        <input type="text" name="nama_departemen" placeholder="masukan nama departemen"/>
        <br><br>
        <button type="submit" name="submit">Tambah Data</button>
    </form>
</body>
</html>

