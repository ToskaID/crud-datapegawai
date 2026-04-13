<?php
require 'connection.php';

// Proteksi halaman: Jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login-new.php");
    exit;
}

// Ambil UUID dari session
$user_id_session = $_SESSION['id'];

// Query JOIN untuk mengambil data lengkap pegawai milik user yang login
$sql = "SELECT u.username, p.*, d.nama_departemen, j.nama_jabatan 
        FROM users AS u
        LEFT JOIN pegawai AS p ON u.id = p.user_id
        LEFT JOIN departemen AS d ON p.departemen_id = d.id
        LEFT JOIN jabatan AS j ON p.jabatan_id = j.id
        WHERE u.id = '$user_id_session'";

$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Profil Saya - <?= htmlspecialchars($data_pegawai['nama'] ?? $_SESSION['username']) ?></title>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">
    <!-- NavBat -->
    <nav class="bg-white shadow-md border-b border-purple-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-4 text-sm md:text-base">
                <a href="home-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-home mr-1"></i> Home
                </a>
                <span class="text-gray-400">|</span>
                <a href="profile-new.php" class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1">
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
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Profil Saya</h1>
                <p class="text-gray-600">Informasi lengkap profil pengguna</p>
            </div>
           
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100 mb-8">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            <?= strtoupper(substr($data_pegawai['nama'] ?? $_SESSION['username'], 0, 1)) ?>
                        </div>
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold"><?= htmlspecialchars($data_pegawai['nama'] ?? $_SESSION['username']) ?></h2>
                        <p class="text-indigo-100">@<?= htmlspecialchars($data_pegawai['username']) ?></p>
                        <div class="flex gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-medium">
                                <i class="fa-solid fa-id-badge"></i>
                                <?= $_SESSION['level'] == 'admin' ? 'Administrator' : 'User' ?>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-medium">
                                <i class="fa-solid fa-building"></i>
                                <?= htmlspecialchars($data_pegawai['nama_departemen'] ?? 'Belum Ditentukan') ?>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-medium">
                                <i class="fa-solid fa-briefcase"></i>
                                <?= htmlspecialchars($data_pegawai['nama_jabatan'] ?? 'Belum Ditentukan') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Nama Lengkap</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($data_pegawai['nama'] ?? '-') ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Username</span>
                        <span class="font-semibold text-gray-800">@<?= htmlspecialchars($data_pegawai['username']) ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Jenis Kelamin</span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                            <?= ($data_pegawai['jenis_kelamin'] ?? '') == 'L' 
                                ? 'bg-blue-100 text-blue-700' 
                                : 'bg-pink-100 text-pink-700' ?>">
                            <i class="fa-solid fa-<?= ($data_pegawai['jenis_kelamin'] ?? '') == 'L' ? 'person' : 'person-dress' ?>"></i>
                            <?= ($data_pegawai['jenis_kelamin'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Status Pernikahan</span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                            <?= ($data_pegawai['status_pernikahan'] ?? '') == 'menikah' 
                                ? 'bg-emerald-100 text-emerald-700' 
                                : 'bg-amber-100 text-amber-700' ?>">
                            <i class="fa-solid fa-<?= ($data_pegawai['status_pernikahan'] ?? '') == 'menikah' ? 'ring' : 'circle' ?>"></i>
                            <?= ucfirst($data_pegawai['status_pernikahan'] ?? 'belum menikah') ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">No. Handphone</span>
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-phone text-green-500 text-sm"></i>
                            <span class="font-semibold text-gray-800"><?= htmlspecialchars($data_pegawai['no_handphone'] ?? '-') ?></span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Alamat</span>
                        <span class="font-semibold text-gray-800 text-right max-w-xs truncate"><?= htmlspecialchars($data_pegawai['alamat'] ?? '-') ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Departemen</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($data_pegawai['nama_departemen'] ?? '-') ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Jabatan</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($data_pegawai['nama_jabatan'] ?? '-') ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Tempat Lahir</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($data_pegawai['tempat_lahir'] ?? '-') ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="text-gray-600 text-sm font-medium">Tanggal Lahir</span>
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-calendar-days text-indigo-500 text-sm"></i>
                            <span class="font-semibold text-gray-800">
                                <?= $data_pegawai['tanggal_lahir'] ? date('d M Y', strtotime($data_pegawai['tanggal_lahir'])) : '-' ?>
                            </span>
                        </div>
                    </div>
                    
                    
                
                    
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>Terakhir login: <?= date('d M Y H:i') ?></p>
        </div>
    </div>
</body>
</html>
