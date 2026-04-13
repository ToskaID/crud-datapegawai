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
                header("Location: list-pegawai-new.php");
            } else {
                header("Location: list-pegawai-new.php");
            }
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login - Sistem Data Pegawai</title>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen flex items-center justify-center px-4">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full shadow-2xl mb-4">
                <i class="fa-solid fa-users text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Data Pegawai
            </h1>
            <p class="text-gray-600">Sistem Manajemen Pegawai Toska Corp.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <div class="text-center text-white">
                    <i class="fa-solid fa-sign-in-alt text-3xl mb-2"></i>
                    <h2 class="text-xl font-bold">Login Sistem</h2>
                    <p class="text-indigo-100 text-sm">Masukkan kredensial Anda</p>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <?php if(isset($error)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-exclamation-circle text-red-500"></i>
                            <p class="text-red-700 text-sm font-medium">Username/Password salah!</p>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <!-- Username Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-user text-indigo-500 mr-1"></i> Username
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="username" 
                                   placeholder="Masukkan username" 
                                   required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <div class="absolute top-3.5 left-3 text-indigo-400">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-lock text-indigo-500 mr-1"></i> Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   placeholder="Masukkan password" 
                                   required
                                   class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            <div class="absolute top-3.5 left-3 text-indigo-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <button type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute top-3.5 right-3 text-gray-400 hover:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                            name="login" 
                            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-md transform hover:scale-[1.02]">
                        <i class="fa-solid fa-sign-in-alt mr-2"></i> Login
                    </button>
                </form>

            </div>

            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="text-center text-sm text-gray-600">
                    <p>&copy; 2024 Toska Corp. All rights reserved.</p>
                    <div class="mt-2 flex justify-center gap-4">
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                            <i class="fa-solid fa-question-circle mr-1"></i> Bantuan
                        </a>
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                            <i class="fa-solid fa-shield-alt mr-1"></i> Kebijakan Privasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

    
    </div>

    <!-- JavaScript for Password Toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="username"]').focus();
        });
    </script>
</body>
</html>
