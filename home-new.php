<?php
require 'connection.php';

// Jika belum login ATAU bukan admin, tendang ke login
if (!isset($_SESSION['login'])) {
    header("Location: login-new.php");
    exit;
}

// Get statistics data
$query_pegawai = mysqli_query($connection, "SELECT * FROM pegawai");
$pegawai = mysqli_fetch_all($query_pegawai, MYSQLI_ASSOC);

$query_departemen = mysqli_query($connection, "SELECT * FROM departemen");
$departemen = mysqli_fetch_all($query_departemen, MYSQLI_ASSOC);

$query_jabatan = mysqli_query($connection, "SELECT * FROM jabatan");
$jabatan = mysqli_fetch_all($query_jabatan, MYSQLI_ASSOC);

// Calculate statistics
$total_pegawai = count($pegawai);
$total_pria = count(array_filter($pegawai, fn($p) => $p['jenis_kelamin'] == 'L'));
$total_wanita = count(array_filter($pegawai, fn($p) => $p['jenis_kelamin'] == 'P'));
$total_departemen = count($departemen);
$total_jabatan = count($jabatan);

// Get recent employees (last 5)
$query_recent = mysqli_query($connection, "SELECT p.nama, p.created_at, d.nama_departemen, j.nama_jabatan 
                                          FROM pegawai p 
                                          LEFT JOIN departemen d ON p.departemen_id = d.id 
                                          LEFT JOIN jabatan j ON p.jabatan_id = j.id 
                                          ORDER BY p.id DESC LIMIT 5");
$recent_pegawai = mysqli_fetch_all($query_recent, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Dashboard - Sistem Data Pegawai</title>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">

<!-- NavBar -->
    <nav class="bg-white shadow-md border-b border-purple-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-4 text-sm md:text-base">
                <a href="home-new.php" class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1">
                    <i class="fa-solid fa-home mr-1"></i> Home
                </a>
                <span class="text-gray-400">|</span>
                <a href="profile-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-user mr-1"></i> Profile
                </a>
                <span class="text-gray-400">|</span>
                <a href="list-pegawai-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-users mr-1"></i> Pegawai
                </a>
                <?php if ($_SESSION['level'] == 'admin') : ?>
                    <span class="text-gray-400">|</span>
                    <a href="list-departemen-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        <i class="fa-solid fa-building mr-1"></i> Departemen
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="list-jabatan-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        <i class="fa-solid fa-briefcase mr-1"></i> Jabatan
                    </a>
                <?php endif; ?>
                <span class="text-gray-400">|</span>
                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')" 
                   class="text-red-600 hover:text-red-700 transition-colors font-medium">
                    <i class="fa-solid fa-sign-out-alt mr-1"></i> Logout (<?= $_SESSION['username']; ?>)
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-white">
                            <h1 class="text-3xl md:text-4xl font-bold mb-2">Selamat Datang, <?= $_SESSION['username'] ?>!</h1>
                            <p class="text-indigo-100 text-lg">Dashboard Sistem Data Pegawai Toska Corp.</p>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full text-sm">
                                    <i class="fa-solid fa-crown mr-1"></i> Administrator
                                </span>
                                <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full text-sm">
                                    <i class="fa-solid fa-calendar mr-1"></i> <?= date('d M Y') ?>
                                </span>
                            </div>
                        </div>
                        <div class="w-24 h-24 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-white text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Pegawai -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-purple-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-users text-white text-xl"></i>
                    </div>
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fa-solid fa-arrow-up mr-1"></i>Active
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800"><?= $total_pegawai ?></h3>
                <p class="text-gray-600 text-sm">Total Pegawai</p>
                <div class="mt-3 flex gap-4 text-xs">
                    <span class="text-blue-600">
                        <i class="fa-solid fa-person mr-1"></i><?= $total_pria ?> Pria
                    </span>
                    <span class="text-pink-600">
                        <i class="fa-solid fa-person-dress mr-1"></i><?= $total_wanita ?> Wanita
                    </span>
                </div>
            </div>

            <!-- Total Departemen -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-purple-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building text-white text-xl"></i>
                    </div>
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fa-solid fa-arrow-up mr-1"></i>Active
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800"><?= $total_departemen ?></h3>
                <p class="text-gray-600 text-sm">Total Departemen</p>
                <div class="mt-3 text-xs text-gray-500">
                    <i class="fa-solid fa-sitemap mr-1"></i>Struktur Organisasi
                </div>
            </div>

            <!-- Total Jabatan -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-purple-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-briefcase text-white text-xl"></i>
                    </div>
                    <span class="text-green-500 text-sm font-medium">
                        <i class="fa-solid fa-arrow-up mr-1"></i>Active
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-gray-800"><?= $total_jabatan ?></h3>
                <p class="text-gray-600 text-sm">Total Jabatan</p>
                <div class="mt-3 text-xs text-gray-500">
                    <i class="fa-solid fa-user-tie mr-1"></i>Posisi Pekerjaan
                </div>
            </div>

        </div>

        

        <!-- Recent Employees Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                <div class="flex items-center justify-between text-white">
                    <h2 class="text-lg font-semibold">
                        <i class="fa-solid fa-clock mr-2"></i>Pegawai Terbaru
                    </h2>
                    <a href="list-pegawai-new.php" class="text-sm text-indigo-100 hover:text-white transition-colors">
                        Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Departemen</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jabatan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_pegawai)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fa-solid fa-inbox text-4xl mb-2 block text-gray-300"></i>
                                    Belum ada data pegawai
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($recent_pegawai as $pegawai): ?>
                                <tr class="border-b border-gray-100 hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                <?= strtoupper(substr($pegawai['nama'], 0, 1)) ?>
                                            </div>
                                            <span class="font-medium text-gray-800"><?= htmlspecialchars($pegawai['nama']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($pegawai['nama_departemen'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($pegawai['nama_jabatan'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <?= date('d M Y H:i', strtotime($pegawai['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>&copy; 2024 Toska Corp. Sistem Data Pegawai v2.0</p>
        </div>
    </div>
</body>
</html>
