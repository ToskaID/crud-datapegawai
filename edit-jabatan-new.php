<?php
require 'connection.php';

//Proteksi: Jika belum login dan bukan admin di tendang ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login-new.php");
    exit;
}

$id = $_GET["id"];
$sql = "SELECT nama_jabatan FROM jabatan WHERE id = '$id'";

$query = mysqli_query($connection, $sql);
$data_jabatan = mysqli_fetch_assoc($query);

if (!$data_jabatan) {
    echo "<script>alert('Data jabatan tidak ditemukan!'); window.location='list-jabatan-new.php';</script>";
    exit;
}

if (isset($_POST['submit'])) {
    $nama_jabatan = $_POST['nama_jabatan'];
    try{
        mysqli_query(
            $connection, "UPDATE jabatan SET nama_jabatan ='$nama_jabatan' WHERE id= '$id'"
         );
          echo "<script>alert('Data Berhasil Diedit!'); window.location='list-jabatan-new.php';</script>";
    }catch(Exception $e){
        echo "Gagal Update ke database " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Edit Jabatan - <?= htmlspecialchars($data_jabatan['nama_jabatan']) ?></title>
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
                <a href="list-pegawai-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                    <i class="fa-solid fa-users mr-1"></i> Pegawai
                </a>
                <?php if ($_SESSION['level'] == 'admin') : ?>
                    <span class="text-gray-400">|</span>
                    <a href="list-departemen-new.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        <i class="fa-solid fa-building mr-1"></i> Departemen
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="list-jabatan-new.php" class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1">
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
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Edit Data Jabatan</h1>
                <p class="text-gray-600">Perbarui informasi jabatan</p>
            </div>
            <div class="flex gap-2">
                <a href="list-jabatan-new.php" 
                   class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-xl">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100 max-w-2xl mx-auto">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-xl font-bold">Edit Jabatan: <?= htmlspecialchars($data_jabatan['nama_jabatan']) ?></h2>
                        <p class="text-indigo-100">ID Jabatan: #<?= $id ?></p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6">
                <form action="" method="POST" class="space-y-6">
                    <!-- Current Info -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-indigo-100 p-2 rounded-lg">
                                <i class="fa-solid fa-info-circle text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-indigo-800">Data Saat Ini</h4>
                                <p class="text-indigo-700 text-sm">Nama jabatan: <strong><?= htmlspecialchars($data_jabatan['nama_jabatan']) ?></strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fa-solid fa-briefcase text-indigo-500 mr-1"></i> Nama Jabatan Baru
                            </label>
                            <input type="text" 
                                   name="nama_jabatan" 
                                   value="<?= htmlspecialchars($data_jabatan['nama_jabatan']) ?>"
                                   placeholder="Masukkan nama jabatan baru" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-lg">
                        </div>
                        
                        <!-- Warning Info -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-amber-100 p-2 rounded-lg mt-1">
                                    <i class="fa-solid fa-exclamation-triangle text-amber-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-amber-800 mb-1">Perhatian</h4>
                                    <p class="text-amber-700 text-sm">
                                        Perubahan nama jabatan akan mempengaruhi data pegawai yang terkait. Pastikan perubahan sudah benar sebelum menyimpan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="list-jabatan-new.php" 
                           class="px-6 py-2.5 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all duration-300 shadow-md">
                            <i class="fa-solid fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" name="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-md">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Related Info -->
        <div class="mt-8 bg-white rounded-xl p-6 shadow-md border border-purple-100 max-w-2xl mx-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Informasi Terkait</h3>
                    <p class="text-gray-600 text-sm">Jabatan ini digunakan oleh data pegawai</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <i class="fa-solid fa-link text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
