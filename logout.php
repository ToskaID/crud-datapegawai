<?php
session_start(); // Wajib jalankan session_start() dulu untuk mengakses session yang aktif

// 1. Hapus semua variabel session
$_SESSION = [];

// 2. Hancurkan session-nya
session_unset();
session_destroy();

// 3. (Opsional) Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Redirect ke halaman login
echo "<script>
        alert('Anda telah berhasil logout.');
        window.location='login-new.php';
      </script>";
exit;
?>