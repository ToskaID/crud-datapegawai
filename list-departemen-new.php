<?php
require 'connection.php';

// Proteksi: Jika belum login, tendang ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: login-new.php");
    exit;
}

$query = mysqli_query($connection, "SELECT * FROM departemen");
$departemen = mysqli_fetch_all($query, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Data Departemen</title>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">
    <nav class="bg-white shadow-md border-b border-purple-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-4 text-sm md:text-base">
                <a href="index.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-home mr-1"></i> Home
                </a>
                <span class="text-gray-400">|</span>
                <a href="profile.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-user mr-1"></i> Profile
                </a>
                <span class="text-gray-400">|</span>
                <a href="list-pegawai-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-users mr-1"></i> Pegawai
                </a>
                <?php if ($_SESSION['level'] == 'admin') : ?>
                    <span class="text-gray-400">|</span>
                    <a href="list-departemen-new.php" class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1">
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
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Data Departemen</h1>
                <p class="text-gray-600">Manajemen Data Departemen Toska Corp.</p>
                <div class="mt-4">
                    <a href="departemen-new.php" class="inline-block px-4 py-2 bg-amber-500 text-amber-950 font-semibold rounded-xl hover:bg-amber-600 transition-all duration-300 shadow-xl">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Departemen
                    </a>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="w-full md:w-64 relative">
                <form method="GET" action="#" class="relative">
                    <input type="text" name="keyword" placeholder="Cari departemen..." 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-purple-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm">
                    <div class="absolute top-3.5 left-3 text-indigo-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Departemen</p>
                        <p class="text-2xl font-bold text-purple-600"><?= count($departemen) ?></p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fa-solid fa-building text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Aktif</p>
                        <p class="text-2xl font-bold text-green-600"><?= count($departemen) ?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fa-solid fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Terbaru</p>
                        <p class="text-2xl font-bold text-blue-600">
                            <?php 
                            $latest = end($departemen);
                            echo substr($latest['nama_departemen'] ?? '', 0, 10) . '...';
                            ?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fa-solid fa-clock text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                <div class="flex items-center justify-between text-white">
                    <h2 class="text-lg font-semibold">
                        <i class="fa-solid fa-list mr-2"></i>Daftar Departemen
                    </h2>
                    <div class="text-sm">
                        Total: <?= count($departemen) ?> data
                    </div>
                </div>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Departemen</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($departemen)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fa-solid fa-inbox text-4xl mb-2 block text-gray-300"></i>
                                    Belum ada data departemen
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($departemen as $index => $d): ?>
                                <tr class="border-b border-gray-100 hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-gray-700"><?= $index + 1 ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold">
                                                <?= strtoupper(substr($d['nama_departemen'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800"><?= htmlspecialchars($d['nama_departemen']) ?></div>
                                                <div class="text-xs text-gray-500">ID: #<?= $d['id'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="edit-departemen-new.php?id=<?= $d['id'] ?>" 
                                               class="px-3 py-1.5 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200 transition-colors duration-200 text-sm font-medium">
                                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                            </a>
                                            <a href="delete-departemen.php?id=<?= $d['id'] ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus departemen <?= htmlspecialchars($d['nama_departemen']) ?>?')"
                                               class="px-3 py-1.5 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-200 transition-colors duration-200 text-sm font-medium">
                                                <i class="fa-solid fa-trash mr-1"></i> Hapus
                                            </a>
                                        </div>
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
            <p>Total <?= count($departemen) ?> data departemen</p>
        </div>
    </div>
</body>
</html>
