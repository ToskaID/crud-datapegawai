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
    <h1>List Data Jabatan</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jabatan</th>
            </tr>
        </thead>
        <?php foreach($jabatan as $index => $j) : ?>
            <tr>
                <td><?=$index+1 ?></td>
                <td><?=$j["nama_jabatan"] ?></td>
            </tr>
        <?php endforeach ?>

    </table>
</body>
</html>