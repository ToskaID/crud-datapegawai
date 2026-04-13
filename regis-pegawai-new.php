<?php
require 'connection.php';

//Proteksi: Jika belum login dan bukan admin di tendang ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
    header("Location: login-new.php");
    exit;
}

// Fungsi sederhana untuk membuat UUID v4
function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// ambil data departemen untuk field dropdown departement 
$departemen = mysqli_query($connection, "SELECT * FROM departemen");

// ambil data jabatan untuk field dropdowm jabatan
$jabatan = mysqli_query($connection, "SELECT * FROM jabatan");

// Variabel untuk menampung pesan error
$error_msg = ""; 

if (isset($_POST['register'])) {
    
//  buat variabel yang menampung dari UUID
    $new_uuid = generate_uuid();

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_hp = $_POST['no_handphone'];
    $status_pernikahan = $_POST['status_pernikahan'];
    $departemen_id = $_POST['departemen_id'];
    $jabatan_id = $_POST['jabatan_id'];


    try{

        // Cek dulu apakah username sudah ada 
        $check_user = mysqli_query($connection, "SELECT username FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check_user) > 0) {
            throw new Exception("Username '$username' sudah terdaftar! Gunakan yang lain.");
        }

        // B. INPUT KE TABEL USERS (Masukkan UUID yang sudah kita buat tadi)
        $sql_user = "INSERT INTO users (id, username, password, level) VALUES 
        ('$new_uuid', '$username', '$password', 'user')";
        mysqli_query($connection, $sql_user);

        // C. INPUT KE TABEL PEGAWAI
        $sql_pegawai = "INSERT INTO pegawai (user_id,nama, jenis_kelamin, alamat, tempat_lahir, 
                    tanggal_lahir, no_handphone, status_pernikahan, departemen_id, jabatan_id)
                    VALUES ('$new_uuid','$nama','$jenis_kelamin','$alamat','$tempat_lahir','$tanggal_lahir',
                    '$no_hp','$status_pernikahan','$departemen_id','$jabatan_id') ";
                    mysqli_query($connection, $sql_pegawai);
                    echo "<script>alert('Data Berhasil Disimpan!'); window.location='list-pegawai-new.php';</script>";
    }catch(Exception $e){
         $error_msg = $e->getMessage();
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
    <title>Tambah Pegawai Baru</title>
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
                <a href="list-pegawai-new.php" class="text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1">
                    <i class="fa-solid fa-users mr-1"></i> Pegawai
                </a>
                <?php if ($_SESSION['level'] == 'admin') : ?>
                    <span class="text-gray-400">|</span>
                    <a href="list-departemen.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
                        <i class="fa-solid fa-building mr-1"></i> Departemen
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="list-jabatan.php" class="text-gray-600 hover:text-indigo-600 transition-colors font-medium">
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
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Tambah Pegawai Baru</h1>
                <p class="text-gray-600">Registrasi data pegawai baru</p>
            </div>
            <div class="flex gap-2">
                <a href="list-pegawai-new.php" 
                   class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-xl">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Error Message -->
        <?php if($error_msg): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-red-100 p-2 rounded-lg">
                        <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-red-800">Registrasi Gagal</h3>
                        <p class="text-red-600 text-sm"><?= $error_msg; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-purple-100">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-user-plus text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-xl font-bold">Form Registrasi Pegawai</h2>
                        <p class="text-indigo-100">Isi data lengkap pegawai baru</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6">
                <form method="POST" class="space-y-6">
                    <!-- Account Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                       
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-id-card text-indigo-400 mr-1"></i> Nama Lengkap
                                </label>
                                <input type="text" name="nama" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Masukkan nama lengkap" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-user-tag text-indigo-400 mr-1"></i> Username
                                </label>
                                <input type="text" name="username" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Masukkan username" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-lock text-indigo-400 mr-1"></i> Password
                                </label>
                                <input type="password" name="password" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Masukkan password" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-venus-mars text-indigo-400 mr-1"></i> Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
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
                                    <option value="">-- Pilih Departemen --</option>
                                    <?php 
                                    mysqli_data_seek($departemen, 0); // Kembalikan kursor ke baris awal
                                    while($d = mysqli_fetch_assoc($departemen)) : ?>
                                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama_departemen']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-user-tie text-indigo-400 mr-1"></i> Jabatan
                                </label>
                                <select name="jabatan_id" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php 
                                    mysqli_data_seek($jabatan, 0); // Kembalikan kursor ke baris awal
                                    while($j = mysqli_fetch_assoc($jabatan)) : ?>
                                        <option value="<?= $j['id'] ?>"><?= htmlspecialchars($j['nama_jabatan']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                    
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-phone text-indigo-400 mr-1"></i> No. Handphone
                                </label>
                                <input type="text" name="no_handphone" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-ring text-indigo-400 mr-1"></i> Status Pernikahan
                                </label>
                                <select name="status_pernikahan" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                    <option value="belum menikah">Belum Menikah</option>
                                    <option value="menikah">Menikah</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-map-marker-alt text-indigo-400 mr-1"></i> Alamat
                                </label>
                                <textarea name="alamat" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                          placeholder="Masukkan alamat lengkap"></textarea>
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
                                <input type="text" name="tempat_lahir" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Kota kelahiran">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fa-solid fa-calendar-days text-indigo-400 mr-1"></i> Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" 
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
                        <button type="submit" name="register" 
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-md">
                            <i class="fa-solid fa-user-plus mr-2"></i> Registrasi Pegawai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
