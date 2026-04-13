<?php
include("connection.php");

$query = mysqli_query($connection, "SELECT * FROM pegawai");
$result = mysqli_fetch_all($query,MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>
    <body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- header -->
            <div class="flex flex-col md:flex-row justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">Data Pegawai</h1>
                    <p class="text-gray-600">Data Pegawai Toska Corp.</p>
                    <div>
                        <a href="form-insert.php" class="inline-block px-3 py-1.5 mt-10 bg-amber-500 text-amber-950 font-semibold rounded-xl hover:bg-amber-600 transition-all duration-300 shadow-xl">
                        <i class="fa-solid fa-pen-to-square mr-1.5"></i> Tambah Data Pegawai</a>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="w-full md:w-64 mt-4 md:mt-0 relative">
                    <form method="GET" action="search.php">
                        <input type="text" name="keyword" placeholder="Cari nama...." class="w-full pl-10 pr-4 py-3 rounded-xl border border-purple-200
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm">

                        <div class="absolute top-3 left-3.5 text-indigo-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end header -->

             <!-- Table Container with shadow and rounded corners    -->
              <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-purple-100">
                <!-- table head -->
                 <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                            <th class="px-6 py-4 text-left font-semibold text-lg">No</th>
                            <th class="px-6 py-4 text-left font-semibold text-lg">Nama</th>
                            <th class="px-6 py-4 text-left font-semibold text-lg">Jenis Kelamin</th>
                            <th class="px-6 py-4 text-left font-semibold text-lg">Alamat</th>
                            <th class="px-6 py-4 text-left font-semibold text-lg">Aksi</th>
                        </tr>
                    </thead>
                    <!-- end table head -->

                    <!-- start table body -->
                    <tbody>
                           <?php foreach($result as $index => $pegawai) : ?>
                            <tr class="border-b border-purple-100 hover:bg-indigo-50 transition-colors duration-200">
                                <td class="px-6 py-4"><?= $index+ 1?></td>
                                <td class="px-6 py-4"><a href="profile.php?id=<?=$pegawai["id"]?>"><?=$pegawai["nama"]?></a></td>
                                <td class="px-6 py-4"><?php echo $pegawai["jenis_kelamin"]?></td>
                                <td class="px-6 py-4"><?php echo $pegawai["alamat"] ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-4">
                                        <a href="form-edit.php?id=<?=$pegawai["id"]?>" class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200 transition-colors
                                        duration-200"> <i class="fa-solid fa-pen-to-square mr-1"></i> Edit</a>

                                        <a href="delete.php?id=<?=$pegawai["id"]?>" class="px-3 py-1 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-200 transition-colors
                                        duration-200"><i class="fa-solid fa-trash mr-1"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach ?>
                    </tbody>
                    <!-- end table body -->
                     
                 </table>
              </div>
        </div>
    </body>
</html>

