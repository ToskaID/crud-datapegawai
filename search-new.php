<?php
require 'connection.php';

// Proteksi halaman: Jika belum login, tendang ke login
if (!isset($_SESSION['login'])) {
    header("Location: login-new.php");
    exit;
}

// Pagination settings
$perPageOptions = [5, 10, 25, 50, 100];
$perPage = isset($_GET['perPage']) && in_array($_GET['perPage'], $perPageOptions) ? (int)$_GET['perPage'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Search functionality
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$whereClause = '';
$params = [];

if (!empty($keyword)) {
    $whereClause = "WHERE (p.nama LIKE ? OR d.nama_departemen LIKE ? OR j.nama_jabatan LIKE ? OR u.username LIKE ?)";
    $searchTerm = "%{$keyword}%";
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
}

// Get total records for pagination
$countQuery = "SELECT COUNT(*) as total 
              FROM pegawai p 
              LEFT JOIN departemen d ON p.departemen_id = d.id 
              LEFT JOIN jabatan j ON p.jabatan_id = j.id 
              LEFT JOIN users u ON p.user_id = u.id 
              $whereClause";

if (!empty($params)) {
    $stmt = $connection->prepare($countQuery);
    $stmt->bind_param('ssss', ...$params);
    $stmt->execute();
    $totalRecords = $stmt->get_result()->fetch_assoc()['total'];
} else {
    $result = mysqli_query($connection, $countQuery);
    $totalRecords = mysqli_fetch_assoc($result)['total'];
}

$totalPages = ceil($totalRecords / $perPage);

// Query dengan JOIN untuk mendapatkan data lengkap pegawai dengan departemen dan jabatan dan pagination
$sql = "SELECT p.*, d.nama_departemen, j.nama_jabatan, u.username 
        FROM pegawai p 
        LEFT JOIN departemen d ON p.departemen_id = d.id 
        LEFT JOIN jabatan j ON p.jabatan_id = j.id 
        LEFT JOIN users u ON p.user_id = u.id 
        $whereClause
        ORDER BY p.created_at DESC
        LIMIT $perPage OFFSET $offset";

if (!empty($params)) {
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssss', ...$params);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $query = mysqli_query($connection, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
}

// Get statistics from entire database
$statsQuery = mysqli_query($connection, "
    SELECT 
        COUNT(*) as total_pegawai,
        SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as total_pria,
        SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as total_wanita,
        COUNT(DISTINCT departemen_id) as total_departemen
    FROM pegawai
");
$stats = mysqli_fetch_assoc($statsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Hasil Pencarian - <?= htmlspecialchars($keyword) ?></title>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">
    <nav class="bg-white shadow-md border-b border-purple-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-4 text-sm md:text-base">
                <a href="home-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-home mr-1"></i> Home
                </a>
                <span class="text-gray-400">|</span>
                <a href="profile-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-user mr-1"></i> Profile
                </a>
                <span class="text-gray-400">|</span>
                <a href="list-pegawai-new.php" class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1">
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
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Hasil Pencarian</h1>
            <p class="text-gray-600">
                <?php if (!empty($keyword)): ?>
                    Menampilkan hasil untuk: <strong>"<?= htmlspecialchars($keyword) ?>"</strong>
                <?php else: ?>
                    Menampilkan semua data pegawai
                <?php endif; ?>
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <!-- Search Bar -->
                <div class="w-full lg:w-80 relative">
                    <form method="GET" action="search-new.php" class="relative">
                        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" 
                               placeholder="Cari nama, departemen, jabatan, atau username..." 
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-purple-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm">
                        <div class="absolute top-3.5 left-4 text-indigo-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <button type="submit" class="absolute top-2 right-2 px-3 py-1.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Per Page Selector -->
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 text-sm whitespace-nowrap">Tampilkan:</span>
                    <form method="GET" action="search-new.php" class="flex items-center gap-2">
                        <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                        <input type="hidden" name="page" value="1">
                        <select name="perPage" 
                                onchange="this.form.submit()"
                                class="px-3 py-2 border border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-sm">
                            <?php foreach ($perPageOptions as $option): ?>
                                <option value="<?= $option ?>" <?= $perPage == $option ? 'selected' : '' ?>>
                                    <?= $option ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-gray-600 text-sm">data/halaman</span>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pegawai</p>
                        <p class="text-2xl font-bold text-indigo-600"><?= $stats['total_pegawai'] ?></p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <i class="fa-solid fa-users text-indigo-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pria</p>
                        <p class="text-2xl font-bold text-blue-600"><?= $stats['total_pria'] ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fa-solid fa-person text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Wanita</p>
                        <p class="text-2xl font-bold text-pink-600"><?= $stats['total_wanita'] ?></p>
                    </div>
                    <div class="bg-pink-100 p-3 rounded-lg">
                        <i class="fa-solid fa-person-dress text-pink-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Departemen</p>
                        <p class="text-2xl font-bold text-purple-600"><?= $stats['total_departemen'] ?></p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fa-solid fa-building text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Results Info -->
        <div class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <i class="fa-solid fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-blue-800 font-medium">
                            <?php if (!empty($keyword)): ?>
                                Ditemukan <strong><?= $totalRecords ?></strong> hasil untuk pencarian "<strong><?= htmlspecialchars($keyword) ?></strong>"
                            <?php else: ?>
                                Menampilkan <strong><?= $totalRecords ?></strong> data pegawai
                            <?php endif; ?>
                        </p>
                        <p class="text-blue-600 text-sm">Pencarian mencakup: nama, departemen, jabatan, dan username</p>
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
                        <i class="fa-solid fa-list mr-2"></i>Daftar Pegawai
                    </h2>
                    <div class="text-sm">
                        Total: <?= $totalRecords ?> data
                    </div>
                </div>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                            <th class="px-6 py-4 text-left font-semibold text-sm lg:text-base">No</th>
                            <th class="px-6 py-4 text-left font-semibold text-sm lg:text-base">Nama</th>
                            <th class="px-6 py-4 text-left font-semibold text-sm lg:text-base">Jenis Kelamin</th>
                            <th class="px-6 py-4 text-left font-semibold text-sm lg:text-base">Departemen</th>
                            <th class="px-6 py-4 text-left font-semibold text-sm lg:text-base">Jabatan</th>
                            <th class="px-6 py-4 text-left font-semibold text-sm lg:text-base">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($result)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fa-solid fa-search text-4xl mb-2 block text-gray-300"></i>
                                    <?php if (!empty($keyword)): ?>
                                        Tidak ada hasil untuk pencarian "<strong><?= htmlspecialchars($keyword) ?></strong>"
                                    <?php else: ?>
                                        Belum ada data pegawai
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($result as $index => $pegawai): ?>
                                <tr class="border-b border-purple-100 hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium"><?= $index + 1 ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                <?= strtoupper(substr($pegawai['nama'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <a href="detail-new.php?id=<?= $pegawai['id'] ?>" class="font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                                    <?= htmlspecialchars($pegawai['nama']) ?>
                                                </a>
                                                <div class="text-xs text-gray-500">@<?= htmlspecialchars($pegawai['username'] ?? '-') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                            <?= $pegawai['jenis_kelamin'] == 'L' 
                                                ? 'bg-blue-100 text-blue-700' 
                                                : 'bg-pink-100 text-pink-700' ?>">
                                            <i class="fa-solid fa-<?= $pegawai['jenis_kelamin'] == 'L' ? 'person' : 'person-dress' ?>"></i>
                                            <?= $pegawai['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        <?= htmlspecialchars($pegawai['nama_departemen'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        <?= htmlspecialchars($pegawai['nama_jabatan'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-1 md:gap-2 flex-wrap">
                                            <a href="detail-new.php?id=<?= $pegawai['id'] ?>" 
                                               class="px-2 py-1.5 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200 text-xs md:text-sm font-medium">
                                                <i class="fa-solid fa-eye mr-1"></i> Detail
                                            </a>
                                            <a href="edit-pegawai-new.php?id=<?= $pegawai['id'] ?>" 
                                               class="px-2 py-1.5 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200 transition-colors duration-200 text-xs md:text-sm font-medium">
                                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                            </a>
                                            <a href="delete-pegawai.php?id=<?= $pegawai['id'] ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= htmlspecialchars($pegawai['nama']) ?>?')"
                                               class="px-2 py-1.5 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-200 transition-colors duration-200 text-xs md:text-sm font-medium">
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

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <div class="flex items-center gap-2 bg-white rounded-lg shadow-md border border-purple-100 p-2">
                <?php if ($page > 1): ?>
                    <a href="?keyword=<?= urlencode($keyword) ?>&page=<?= $page - 1 ?>&perPage=<?= $perPage ?>" 
                       class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <span class="px-4 py-2 text-sm font-semibold text-indigo-600">
                    Halaman <?= $page ?> dari <?= $totalPages ?>
                </span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?keyword=<?= urlencode($keyword) ?>&page=<?= $page + 1 ?>&perPage=<?= $perPage ?>" 
                       class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4 text-center text-gray-500 text-sm">
            <p>Menampilkan <?= (($page - 1) * $perPage) + 1 ?> - <?= min($page * $perPage, $totalRecords) ?> dari <?= $totalRecords ?> data (<?= $perPage ?> per halaman)</p>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="list-pegawai-new.php" 
               class="inline-block px-4 py-2 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-xl">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Pegawai
            </a>
        </div>
    </div>
</body>
</html>
