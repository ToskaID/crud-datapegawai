<?php

require 'connection.php';

if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Enkripsi password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Level langsung kita tulis 'user' di dalam query
    $sql = "INSERT INTO users (nama_lengkap,username, password, level) 
            VALUES ('$fullname', '$username','$password_hashed', 'user')";
    
    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($connection);
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
    <form method="POST">
    <h2>Register Baru</h2>
    
    <label>Fullname</label><br>
    <input type="text" name="fullname"  required><br><br>
    
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>
    
    <label>Password</label><br>
    <input type="password" name="password"  required><br><br>
    <button type="submit" name="register">Daftar</button>
    </form>
</body>
</html>