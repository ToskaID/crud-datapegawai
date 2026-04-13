<?php
require 'connection.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($connection, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Cek apakah password cocok dengan hash di DB
        if (password_verify($password, $row['password'])) {
            
            // Set session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['level'] = $row['level']; // Simpan level (admin/user)
            $_SESSION['id'] = $row['id']; // Simpan UUID user di sini

            // Redirect berdasarkan level
            if ($row['level'] == 'admin') {
                header("Location: index.php");
            } else {
                header("Location: list-pegawai.php");
            }
            exit;
        }
    }
    $error = true;
}
?>

<form method="POST">
    <h2>Login Sistem</h2>
    <?php if(isset($error)) echo "<p style='color:red'>Username/Password salah!</p>"; ?>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>