<?php
require 'connection.php';

$id = $_GET["id"];
// Kita gabungkan (JOIN) tabel pegawai,departemen,jabatan di sini
$sql = "SELECT p.*, d.nama_departemen, j.nama_jabatan, u.username,u.id as user_id
        FROM pegawai AS p
        LEFT JOIN departemen AS d ON p.departemen_id = d.id
        LEFT JOIN users AS u ON p.user_id = u.id
        LEFT JOIN jabatan AS j ON p.jabatan_id = j.id WHERE p.id = '$id'";

// 1. Ambil data spesifik pegawai yang mau diedit
$query = mysqli_query($connection, $sql);
$data_pegawai = mysqli_fetch_assoc($query);

if (!$data_pegawai) {
    echo "<script>alert('Data pegawai tidak ditemukan!'); window.location='list-pegawai-new.php';</script>";
    exit;
}

// 2. Ambil SEMUA data departemen untuk dropdown
$query_dept = mysqli_query($connection, "SELECT * FROM departemen");

// 3. Ambil SEMUA data jabatan untuk dropdown
$query_jabatan = mysqli_query($connection, "SELECT * FROM jabatan");

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_handphone = $_POST['no_handphone'];
    $status_pernikahan = $_POST['status_pernikahan'];
    $departemen_id = $_POST['departemen_id'];
    $jabatan_id = $_POST['jabatan_id'];
    $user_id = $data_pegawai['user_id'];

    try{
        //update tabel usr (untuk username)
        mysqli_query($connection, "UPDATE users SET username = '$username' WHERE id = '$user_id'");

        //update tabel pegawai
        mysqli_query(
            $connection, "UPDATE pegawai SET
            nama ='$nama',
            jenis_kelamin ='$jenis_kelamin',
            alamat ='$alamat',
            tempat_lahir ='$tempat_lahir',
            tanggal_lahir ='$tanggal_lahir',
            no_handphone ='$no_handphone',
            status_pernikahan ='$status_pernikahan',
            departemen_id ='$departemen_id',
            jabatan_id ='$jabatan_id'
             WHERE id= '$id'"
         );
         echo "<script>alert('Data Berhasil Diedit!'); window.location='list-pegawai-new.php';</script>";
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
    <title>Edit Pegawai - <?= htmlspecialchars($data_pegawai['nama']) ?></title>
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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Edit Data Pegawai</h1>
                <p class="text-gray-600">Perbarui informasi pegawai</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-xl font-bold">Edit Data: <?= htmlspecialchars($data_pegawai['nama']) ?></h2>
                        
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6">
                <form action="" method="POST" class="space-y-6">
                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                       
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-id-card text-indigo-400 mr-1"></i> Nama Pegawai
                                </label>
                                <input type="text" name="nama" value="<?= htmlspecialchars($data_pegawai['nama']) ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-user-tag text-indigo-400 mr-1"></i> Username
                                </label>
                                <input type="text" name="username" value="<?= htmlspecialchars($data_pegawai['username']) ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-venus-mars text-indigo-400 mr-1"></i> Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <option value="L" <?= ($data_pegawai['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>
                                        <i class="fa-solid fa-person"></i> Laki-laki
                                    </option>
                                    <option value="P" <?= ($data_pegawai['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>
                                        <i class="fa-solid fa-person-dress"></i> Perempuan
                                    </option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-ring text-indigo-400 mr-1"></i> Status Pernikahan
                                </label>
                                <select name="status_pernikahan" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <option value="belum menikah" <?= ($data_pegawai['status_pernikahan'] == 'belum menikah') ? 'selected' : '' ?>>
                                        Belum Menikah
                                    </option>
                                    <option value="menikah" <?= ($data_pegawai['status_pernikahan'] == 'menikah') ? 'selected' : '' ?>>
                                        Menikah
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Work Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                      
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-building text-indigo-400 mr-1"></i> Departemen
                                </label>
                                <select name="departemen_id" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <?php 
                                    mysqli_data_seek($query_dept, 0); // Reset pointer
                                    while($d = mysqli_fetch_assoc($query_dept)) : ?>
                                        <option value="<?= $d['id'] ?>" <?= ($d['id'] == $data_pegawai['departemen_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($d['nama_departemen']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-user-tie text-indigo-400 mr-1"></i> Jabatan
                                </label>
                                <select name="jabatan_id" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <?php 
                                    mysqli_data_seek($query_jabatan, 0); // Reset pointer
                                    while($j = mysqli_fetch_assoc($query_jabatan)) : ?>
                                        <option value="<?= $j['id'] ?>" <?= ($j['id'] == $data_pegawai['jabatan_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($j['nama_jabatan']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-phone text-indigo-400 mr-1"></i> No. Handphone
                                </label>
                                <input type="text" name="no_handphone" value="<?= htmlspecialchars($data_pegawai['no_handphone']) ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-map-marker-alt text-indigo-400 mr-1"></i> Alamat
                                </label>
                                <input type="text" name="alamat" value="<?= htmlspecialchars($data_pegawai['alamat']) ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Jl. ...">
                            </div>
                        </div>
                    </div>

                    <!-- Birth Information Section -->
                    <div class="pb-6">
                        
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-map-pin text-indigo-400 mr-1"></i> Tempat Lahir
                                </label>
                                <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($data_pegawai['tempat_lahir']) ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Kota">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-calendar-days text-indigo-400 mr-1"></i> Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" value="<?= $data_pegawai['tanggal_lahir'] ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="list-pegawai-new.php" 
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
    </div>
</body>
</html>
