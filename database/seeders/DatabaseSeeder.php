<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IkmProfile;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\ProductionRecord;
use App\Models\Order;
use App\Models\ProductionStage;
use App\Models\ProcessFlow;
use App\Models\WasteRecord;
use App\Models\WasteUtilization;
use App\Models\SystemNeed;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Akun Pengguna Resmi (Admin, Operator, Buyer)
        User::updateOrCreate(
            ['email' => 'admin@marmer.com'],
            [
                'name' => 'Administrator IKM Marmer',
                'role' => 'admin',
                'telepon' => '081234567890',
                'alamat' => 'Sentra IKM Campurdarat Tulungagung',
                'password' => Hash::make('admin'),
                'is_active' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=Admin+Marmer&background=0d9488&color=fff',
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@marmer.com'],
            [
                'name' => 'Operator Bengkel Bubut',
                'role' => 'operator',
                'telepon' => '081234567891',
                'alamat' => 'Workshop UD Cahaya Onix Gamping',
                'password' => Hash::make('operator'),
                'is_active' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=Operator+Bengkel&background=d97706&color=fff',
            ]
        );

        User::updateOrCreate(
            ['email' => 'buyer@marmer.com'],
            [
                'name' => 'Pelanggan / Buyer Setia',
                'role' => 'buyer',
                'telepon' => '081234567892',
                'alamat' => 'Jl. Danau Tamblingan No. 88, Sanur, Denpasar',
                'password' => Hash::make('buyer'),
                'is_active' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=Buyer+Marmer&background=0284c7&color=fff',
            ]
        );

        // 1. Profil IKM
        $cahayaOnix = IkmProfile::updateOrCreate(
            ['nama_usaha' => 'UD Cahaya Onix'],
            [
                'nama_pemilik' => 'M. Ilham Nur Amali',
                'fokus_produksi' => 'Pengolahan Batu Marmer & Onix',
                'lokasi' => 'Desa Gamping, Kec. Campurdarat, Kab. Tulungagung',
                'kekuatan_produksi' => '1 hari -> 14 Biji -> 7 mesin bubut',
                'deskripsi' => 'Sentra pengolahan batu marmer Tulungagung unggulan yang mengolah marmer Besole putih, marmer hitam, merah, TR, dan cappuccino menjadi produk interior bernilai seni tinggi.',
                'telepon' => '0812-3456-7890',
                'email' => 'cahayaonix.tulungagung@gmail.com',
            ]
        );

        $putraAbadi = IkmProfile::updateOrCreate(
            ['nama_usaha' => 'UD. Putra Abadi'],
            [
                'nama_pemilik' => 'Efri Saputra',
                'fokus_produksi' => 'Pengolahan Batu Kali & Andesit Alam',
                'lokasi' => 'Desa Gamping, Kec. Campurdarat, Kab. Tulungagung',
                'kekuatan_produksi' => 'Spesialis stepping stone taman, wastafel batu kali natural, dan kap lampu artistik',
                'deskripsi' => 'Pengrajin batu alam sungai alami pilihan yang mempertahankan tekstur eksotis alami dengan kombinasi finishing poles halus pada bagian fungsional.',
                'telepon' => '0821-9876-5432',
                'email' => 'putraabadi.naturalstone@gmail.com',
            ]
        );

        // 2. Produk (Menggunakan Foto Asli yang Diunggah Pengguna)
        $p1 = Product::updateOrCreate(
            ['nama_produk' => 'Wastafel (Sink Marmer/Marble)'],
            [
                'ikm_profile_id' => $cahayaOnix->id,
                'jenis_batu' => 'Marmer Besole Putih / Hitam',
                'deskripsi' => 'Wastafel mewah corak serat alami marmer pegunungan Tulungagung dengan ketahanan tinggi dan kilau Hi-Glossy.',
                'harga' => 450000,
                'gambar_path' => 'products/1793247630_6ae2c98e22856.jpg',
                'is_active' => true,
            ]
        );

        $p2 = Product::updateOrCreate(
            ['nama_produk' => 'Pedestal Marmer'],
            [
                'ikm_profile_id' => $cahayaOnix->id,
                'jenis_batu' => 'Marmer Tulungagung',
                'deskripsi' => 'Wastafel berdiri (pedestal) elegan dengan konstruksi kokoh dan polesan detail tanpa pori.',
                'harga' => 1250000,
                'gambar_path' => 'products/1793247838_6ae2ca5e48140.jpg',
                'is_active' => true,
            ]
        );

        $p3 = Product::updateOrCreate(
            ['nama_produk' => 'Dispenser Sabun Marmer'],
            [
                'ikm_profile_id' => $cahayaOnix->id,
                'jenis_batu' => 'Marmer Alami & Onix',
                'deskripsi' => 'Aksesoris kamar mandi mewah dari marmer padat dan pompa tahan karat.',
                'harga' => 85000,
                'gambar_path' => 'products/1793248046_6ae2cb2e3fa8c.jpg',
                'is_active' => true,
            ]
        );

        $p4 = Product::updateOrCreate(
            ['nama_produk' => 'Stepping Batu Kali'],
            [
                'ikm_profile_id' => $putraAbadi->id,
                'jenis_batu' => 'Batu Kali Sungai Alami',
                'deskripsi' => 'Batu pijakan taman (stepping stone) dengan permukaan potong rata 3 cm dan pinggiran bulat aman.',
                'harga' => 35000,
                'gambar_path' => 'products/1793248170_6ae2cbaa67a7d.jpg',
                'is_active' => true,
            ]
        );

        $p5 = Product::updateOrCreate(
            ['nama_produk' => 'Wastafel Batu Kali'],
            [
                'ikm_profile_id' => $putraAbadi->id,
                'jenis_batu' => 'Batu Kali Boulder',
                'deskripsi' => 'Wastafel estetik nuansa zen / resort dengan bagian luar kulit batu asli dan bagian dalam terpoles kilap.',
                'harga' => 350000,
                'gambar_path' => 'products/1793248240_6ae2cbf0ec4b1.jpg',
                'is_active' => true,
            ]
        );

        $p6 = Product::updateOrCreate(
            ['nama_produk' => 'Kap Lampu Batu Kali'],
            [
                'ikm_profile_id' => $putraAbadi->id,
                'jenis_batu' => 'Batu Kali Alami',
                'deskripsi' => 'Kap lampu taman tahan cuaca ekstrem yang memancarkan pendar cahaya hangat di malam hari.',
                'harga' => 175000,
                'gambar_path' => 'products/1793248370_6ae2cc721d43d.jpg',
                'is_active' => true,
            ]
        );

        // 3. Bahan Baku UD Cahaya Onix (2026)
        $cahayaMaterials = [
            ['Januari', 'Batu Marmer Putih', '20 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Januari', 'Batu Marmer Hitam', '10 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['Februari', 'Batu Marmer Putih', '22 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Februari', 'Batu Marmer Hitam', '11 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['Maret', 'Batu Marmer Putih', '18 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Maret', 'Batu Marmer Hitam', '9 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['April', 'Batu Marmer Putih', '21 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['April', 'Batu Marmer Hitam', '10 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['Mei', 'Batu Marmer Putih', '23 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Mei', 'Batu Marmer Hitam', '11 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['Juni', 'Batu Marmer Putih', '20 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Juni', 'Batu Marmer Hitam', '10 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['Juli', 'Batu Marmer Putih', '22 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Juli', 'Batu Marmer Hitam', '11 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
            ['Agustus', 'Batu Marmer Putih', '19 Balok', '2m x 150cm x 80-90cm', 'Putih Besole'],
            ['Agustus', 'Batu Marmer Hitam', '10 Balok', '2m x 50cm x 50cm', 'Hitam Serat'],
        ];

        foreach ($cahayaMaterials as $m) {
            RawMaterial::create([
                'ikm_profile_id' => $cahayaOnix->id,
                'tahun' => 2026,
                'bulan' => $m[0],
                'jenis_batu' => $m[1],
                'jumlah_pembelian' => $m[2],
                'dimensi' => $m[3],
                'warna' => $m[4],
                'status' => 'tersedia',
            ]);
        }

        // 4. Bahan Baku UD Putra Abadi (2026)
        $putraMaterials = [
            ['Januari', '640 biji'], ['Januari', '640 biji'], ['Januari', '800 biji'],
            ['Februari', '640 biji'], ['Februari', '640 biji'], ['Februari', '640 biji'],
            ['Maret', '800 biji'], ['Maret', '640 biji'], ['Maret', '640 biji'],
            ['April', '640 biji'], ['April', '640 biji'], ['April', '800 biji'],
            ['Mei', '640 biji'], ['Mei', '800 biji'], ['Mei', '640 biji'],
            ['Juni', '640 biji'], ['Juni', '640 biji'], ['Juni', '800 biji'],
            ['Juli', '640 biji'], ['Juli', '640 biji'], ['Juli', '800 biji'],
            ['Agustus', '640 biji'], ['Agustus', '640 biji'], ['Agustus', '800 biji'],
        ];

        foreach ($putraMaterials as $pm) {
            RawMaterial::create([
                'ikm_profile_id' => $putraAbadi->id,
                'tahun' => 2026,
                'bulan' => $pm[0],
                'jenis_batu' => 'Batu Kali',
                'jumlah_pembelian' => $pm[1],
                'dimensi' => 'Diameter 40-50 cm s/d 80-90 cm',
                'warna' => 'Abu-abu Alami & Cokelat Sungai',
                'status' => 'tersedia',
            ]);
        }

        // 5. Rekap Produksi UD Cahaya Onix
        $cahayaProd = [
            ['Januari', 'Wastafel (Sink Marmer/Marble)', '625', $p1->id],
            ['Januari', 'Pedestal Marmer', '60', $p2->id],
            ['Januari', 'Dispenser Sabun', '250', $p3->id],
            ['Februari', 'Wastafel (Sink Marmer/Marble)', '700', $p1->id],
            ['Februari', 'Pedestal Marmer', '60', $p2->id],
            ['Februari', 'Dispenser Sabun', '250', $p3->id],
            ['Maret', 'Wastafel (Sink Marmer/Marble)', '550', $p1->id],
            ['Maret', 'Pedestal Marmer', '60', $p2->id],
            ['Maret', 'Dispenser Sabun', '250', $p3->id],
            ['April', 'Wastafel (Sink Marmer/Marble)', '675', $p1->id],
            ['April', 'Pedestal Marmer', '60', $p2->id],
            ['April', 'Dispenser Sabun', '250', $p3->id],
            ['Mei', 'Wastafel (Sink Marmer/Marble)', '750', $p1->id],
            ['Mei', 'Pedestal Marmer', '60', $p2->id],
            ['Mei', 'Dispenser Sabun', '250', $p3->id],
            ['Juni', 'Wastafel (Sink Marmer/Marble)', '600', $p1->id],
            ['Juni', 'Pedestal Marmer', '60', $p2->id],
            ['Juni', 'Dispenser Sabun', '250', $p3->id],
            ['Juli', 'Wastafel (Sink Marmer/Marble)', '725', $p1->id],
            ['Juli', 'Pedestal Marmer', '60', $p2->id],
            ['Juli', 'Dispenser Sabun', '250', $p3->id],
            ['Agustus', 'Wastafel (Sink Marmer/Marble)', '575', $p1->id],
            ['Agustus', 'Pedestal Marmer', '60', $p2->id],
            ['Agustus', 'Dispenser Sabun', '250', $p3->id],
        ];

        foreach ($cahayaProd as $cp) {
            ProductionRecord::create([
                'ikm_profile_id' => $cahayaOnix->id,
                'product_id' => $cp[3],
                'tahun' => 2026,
                'bulan' => $cp[0],
                'jenis_produk' => $cp[1],
                'jumlah_produksi' => $cp[2],
            ]);
        }

        // 6. Rekap Produksi UD Putra Abadi
        $putraProd = [
            ['Januari', 'Stepping Batu Kali', '2.750 Pcs', $p4->id],
            ['Januari', 'Wastafel Batu Kali', '250 Pcs', $p5->id],
            ['Januari', 'Kap Lampu', '350 Pcs', $p6->id],
            ['Februari', 'Stepping Batu Kali', '2.850 Pcs', $p4->id],
            ['Februari', 'Wastafel Batu Kali', '275 Pcs', $p5->id],
            ['Februari', 'Kap Lampu', '375 Pcs', $p6->id],
            ['Maret', 'Stepping Batu Kali', '2.900 Pcs', $p4->id],
            ['Maret', 'Wastafel Batu Kali', '300 Pcs', $p5->id],
            ['Maret', 'Kap Lampu', '400 Pcs', $p6->id],
            ['April', 'Stepping Batu Kali', '2.650 Pcs', $p4->id],
            ['April', 'Wastafel Batu Kali', '225 Pcs', $p5->id],
            ['April', 'Kap Lampu', '325 Pcs', $p6->id],
            ['Mei', 'Stepping Batu Kali', '3.000 Pcs', $p4->id],
            ['Mei', 'Wastafel Batu Kali', '290 Pcs', $p5->id],
            ['Mei', 'Kap Lampu', '390 Pcs', $p6->id],
            ['Juni', 'Stepping Batu Kali', '2.800 Pcs', $p4->id],
            ['Juni', 'Wastafel Batu Kali', '260 Pcs', $p5->id],
            ['Juni', 'Kap Lampu', '360 Pcs', $p6->id],
            ['Juli', 'Stepping Batu Kali', '2.950 Pcs', $p4->id],
            ['Juli', 'Wastafel Batu Kali', '280 Pcs', $p5->id],
            ['Juli', 'Kap Lampu', '380 Pcs', $p6->id],
            ['Agustus', 'Stepping Batu Kali', '2.700 Pcs', $p4->id],
            ['Agustus', 'Wastafel Batu Kali', '240 Pcs', $p5->id],
            ['Agustus', 'Kap Lampu', '340 Pcs', $p6->id],
        ];

        foreach ($putraProd as $pp) {
            ProductionRecord::create([
                'ikm_profile_id' => $putraAbadi->id,
                'product_id' => $pp[3],
                'tahun' => 2026,
                'bulan' => $pp[0],
                'jenis_produk' => $pp[1],
                'jumlah_produksi' => $pp[2],
            ]);
        }

        // 7. Observasi Waste UD Cahaya Onix
        $wasteCahaya = [
            ['03 Agustus 2026', 'Pembongkaran dan penempatan bongkahan marmer', '07:30', '08:00', 'Motion', 'Bongkahan marmer perlu dipindahkan dan diatur kembali agar posisi sesuai dengan area kerja dan memudahkan proses berikutnya.'],
            ['03 Agustus 2026', 'Pembelahan awal bongkahan marmer secara manual', '08:00', '09:00', 'Overprocessing & Motion', 'Bongkahan besar perlu dibelah terlebih dahulu secara manual agar dapat masuk ke mesin slep. Proses membutuhkan tenaga dan waktu tambahan.'],
            ['03 Agustus 2026', 'Pemotongan bongkahan menggunakan mesin slep', '09:00', '10:15', 'Defect & Material Waste', 'Proses pemotongan menghasilkan lumpur dari campuran air dan serbuk marmer serta potongan marmer berukuran kecil.'],
            ['03 Agustus 2026', 'Pemindahan batu hasil potongan menuju mesin bubut', '10:15', '10:35', 'Transportation & Motion', 'Material dipindahkan dari area pemotongan menuju area mesin bubut secara manual sehingga terdapat aktivitas handling tambahan.'],
            ['03 Agustus 2026', 'Pembubutan dan pembentukan model wastafel', '10:35', '12:00', 'Waiting & Material Waste', 'Terdapat sisa potongan kecil dari proses pembentukan. Dalam kondisi tertentu sisa marmer dikumpulkan untuk dimanfaatkan sebagai material urukan pondasi.'],
            ['03 Agustus 2026', 'Penghalusan dan proses poles wastafel', '13:00', '14:30', 'Overprocessing', 'Penghalusan dilakukan melalui beberapa tahapan tingkat kekasaran perlas sampai mendapatkan permukaan yang sesuai.'],
            ['03 Agustus 2026', 'Finishing dan pemeriksaan kualitas', '14:30', '15:30', 'Defect & Rework', 'Produk yang ditemukan retak atau gempil perlu diperbaiki/ditambal sebelum dapat melanjutkan ke proses berikutnya.'],
            ['03 Agustus 2026', 'Pengumpulan sisa produksi dan pemindahan produk jadi', '15:30', '16:30', 'Material Waste & Transportation', 'Lumpur bekas pemotongan dikumpulkan sebagai limbah proses, sedangkan potongan marmer kecil dimanfaatkan untuk urukan pondasi rumah. Produk jadi dipindahkan ke area penyimpanan/pengiriman.'],
        ];

        foreach ($wasteCahaya as $wc) {
            WasteRecord::create([
                'ikm_profile_id' => $cahayaOnix->id,
                'tanggal' => '2026-08-03',
                'aktivitas_diamati' => $wc[1],
                'waktu_mulai' => $wc[2],
                'waktu_selesai' => $wc[3],
                'jenis_waste' => $wc[4],
                'catatan' => $wc[5],
            ]);
        }

        // 8. Observasi Waste UD Putra Abadi
        $wastePutra = [
            ['2026-08-06', 'Pemilahan sisa batu kali setelah proses pemotongan', '08:00', '08:35', 'Inventory', 'Sisa batu dikumpulkan terlebih dahulu sebelum ditentukan pemanfaatannya.'],
            ['2026-08-07', 'Pengumpulan limbah batu kali di area produksi', '09:00', '09:40', 'Motion', 'Operator melakukan perpindahan beberapa kali untuk mengumpulkan sisa material dari area produksi.'],
            ['2026-08-08', 'Pemilahan sisa batu yang masih dapat digunakan', '08:30', '09:15', 'Overprocessing', 'Sisa batu diperiksa secara manual untuk menentukan apakah masih sesuai ukuran untuk diproses kembali.'],
            ['2026-08-10', 'Pemotongan ulang sisa batu untuk wall cladding', '10:00', '11:00', 'Rework', 'Sisa batu yang masih memenuhi ukuran dipotong kembali untuk dijadikan wall cladding/hiasan dinding.'],
            ['2026-08-11', 'Pemindahan sisa batu yang tidak sesuai ukuran ke area limbah', '13:00', '13:35', 'Transportation', 'Limbah yang tidak dapat digunakan kembali dipindahkan dari area produksi untuk dimanfaatkan sebagai urukan pondasi.'],
            ['2026-08-12', 'Penumpukan sisa batu sebelum digunakan untuk urukan pondasi', '09:30', '10:15', 'Inventory', 'Sisa material disimpan sementara sehingga membutuhkan area penyimpanan tambahan.'],
            ['2026-08-13', 'Pemanfaatan limbah batu kali sebagai material urukan pondasi', '08:00', '09:00', 'Transportation', 'Limbah batu dipindahkan ke lokasi yang membutuhkan material urukan pondasi.'],
            ['2026-08-14', 'Pemeriksaan dan pemanfaatan kembali sisa material untuk produk wall cladding', '10:00', '11:10', 'Overprocessing / Rework', 'Sisa batu yang masih layak diproses kembali agar memiliki nilai guna sebagai hiasan dinding.'],
        ];

        foreach ($wastePutra as $wp) {
            WasteRecord::create([
                'ikm_profile_id' => $putraAbadi->id,
                'tanggal' => $wp[0],
                'aktivitas_diamati' => $wp[1],
                'waktu_mulai' => $wp[2],
                'waktu_selesai' => $wp[3],
                'jenis_waste' => $wp[4],
                'catatan' => $wp[5],
            ]);
        }

        // 9. Value Stream / Alur Proses UD Cahaya Onix
        $flowCahaya = [
            [1, 'Penerimaan dan pemeriksaan order bahan baku', 'Pemilik/pengelola usaha, bagian pembelian, pemasok bahan baku', 'Jenis marmer, ukuran bongkahan, jumlah, kualitas, kebutuhan produksi, jadwal kebutuhan bahan', 'Order bahan baku marmer bongkahan besar dilakukan berdasarkan kebutuhan produksi dan pesanan yang masuk. Informasi ukuran dan kebutuhan bahan disampaikan kepada pemasok.', 'Belum terdapat sistem pencatatan kebutuhan bahan baku yang terstruktur dan standar pemeriksaan bahan saat pemesanan.', 'Waiting, Defect, Overprocessing', 'Waktu: koordinasi dan pengecekan berulang. Biaya: risiko pembelian bahan yang tidak sesuai. Kualitas: bahan tidak sesuai karakteristik produk dapat menyebabkan proses berikutnya terganggu.'],
            [2, 'Penerimaan dan penempatan bahan baku di lokasi pengrajin', 'Pemasok, pengrajin/operator, pemilik', 'Surat/order pembelian, jumlah bahan, ukuran bongkahan, kondisi bahan, lokasi penyimpanan', 'Bongkahan marmer datang langsung ke lokasi pengrajin dan kemudian ditempatkan untuk diproses. Pemeriksaan kondisi bahan dilakukan secara visual.', 'Belum ada standar penerimaan dan pencatatan kondisi bahan secara sistematis. Penempatan bahan masih menyesuaikan ruang yang tersedia.', 'Motion, Waiting, Defect', 'Waktu: proses pemindahan dan pencarian bahan dapat bertambah. Biaya: risiko kerusakan saat pemindahan. Kualitas: retak atau cacat awal dapat terlambat diketahui.'],
            [3, 'Pembelahan awal bongkahan marmer', 'Operator/pengrajin', 'Ukuran awal bongkahan, ukuran hasil pembelahan, kondisi retakan, posisi pembelahan', 'Bongkahan besar dibelah secara manual menjadi dua bagian agar lebih mudah ditempatkan pada mesin slep yang tersedia.', 'Pembelahan masih dilakukan manual dan sangat bergantung pada keterampilan operator. Belum terdapat standar ukuran atau metode pembelahan yang terdokumentasi.', 'Motion, Overprocessing, Waiting', 'Waktu: proses relatif lama dan membutuhkan tenaga. Biaya: penggunaan tenaga kerja lebih tinggi dan potensi material terbuang. Kualitas: hasil pembelahan dapat tidak seragam atau berpotensi menyebabkan retakan.'],
            [4, 'Pembelahan dan pemotongan awal menggunakan slep', 'Operator mesin slep, pengrajin', 'Ukuran batu yang dibutuhkan, kapasitas mesin, ketebalan potongan, spesifikasi produk/order', 'Batu yang telah dibelah dimasukkan ke slep dengan batas tinggi sekitar 80 cm, kemudian dibelah kembali menjadi ukuran yang lebih mudah diproses.', 'Ukuran bongkahan harus disesuaikan terlebih dahulu dengan kapasitas mesin. Belum terlihat adanya standar ukuran bahan baku sebelum masuk slep untuk meminimalkan proses penyesuaian.', 'Waiting, Motion, Overprocessing', 'Waktu: operator membutuhkan waktu untuk menyesuaikan posisi batu. Biaya: konsumsi listrik dan penggunaan mesin meningkat jika proses harus diulang. Kualitas: kesalahan pemotongan awal dapat menghasilkan material yang tidak dapat digunakan.'],
            [5, 'Pemotongan sesuai pesanan dan pembentukan model awal wastafel', 'Operator pemotongan, operator bubut, pemilik/pengawas', 'Order pelanggan, ukuran wastafel, desain/model, toleransi ukuran, jenis marmer, jumlah produk', 'Batu dipotong sesuai order atau produk yang akan dibuat, kemudian dibawa ke tempat bubut untuk dibuat model wastafel sesuai ukuran dan kebutuhan.', 'Informasi order menjadi acuan utama, tetapi belum terlihat adanya dokumen kerja/detail ukuran yang terstandar di setiap tahap. Perpindahan batu dari pemotongan ke tempat bubut juga dilakukan secara fisik.', 'Transportation, Motion, Overprocessing', 'Waktu: pemindahan material dan penyesuaian ukuran membutuhkan waktu. Biaya: material dapat terbuang apabila ukuran awal tidak tepat. Kualitas: ketidaktepatan ukuran dapat memengaruhi bentuk akhir wastafel.'],
            [6, 'Pembubutan, penghalusan awal, dan pemeriksaan kualitas pertama', 'Operator bubut, pengrajin, QC/pemilik', 'Model wastafel, ukuran standar, kondisi permukaan, toleransi, kriteria cacat/retak/gempil', 'Setelah model wastafel selesai dibubut menggunakan mesin bubut, proses dilanjutkan dengan penghalusan menggunakan batu ungkal. Setelah itu dilakukan QC pertama. Jika terdapat retak atau gempil, dilakukan penambalan menggunakan batu lain, resin, dan katalis.', 'QC dilakukan setelah proses pembubutan, sehingga cacat yang sebenarnya dapat diketahui lebih awal berpotensi baru ditangani setelah proses pembentukan selesai. Perbaikan masih bersifat reaktif.', 'Defect, Rework, Overprocessing', 'Waktu: produk cacat membutuhkan pengerjaan ulang. Biaya: tambahan resin, katalis, batu, tenaga, dan waktu mesin. Kualitas: hasil tambalan berpotensi berbeda dengan tekstur atau warna batu asli.'],
            [7, 'Poles akhir, pembuatan lubang afur, finishing, dan QC kedua', 'Operator poles, operator finishing, QC/pemilik', 'Standar kehalusan, urutan ukuran perlas, posisi dan ukuran lubang afur, standar kilap, standar produk sesuai order', 'Produk masuk proses poles menggunakan beberapa ukuran perlas, dari yang kasar sampai halus, umumnya sekitar lima tahapan. Setelah itu dibuat lubang afur untuk drainase, kemudian finishing menggunakan Hi-Glossy. Setelah finishing dilakukan QC kedua berdasarkan standar order.', 'Proses finishing terdiri dari beberapa tahapan dan masih bergantung pada keterampilan operator. Belum terdapat standar tertulis mengenai tingkat kehalusan, durasi poles, maupun parameter hasil finishing.', 'Overprocessing, Defect, Rework', 'Waktu: proses poles dan finishing cukup panjang. Biaya: konsumsi bahan poles, Hi-Glossy, listrik, dan tenaga kerja. Kualitas: hasil kilap dan kehalusan dapat tidak konsisten antarproduk.'],
            [8, 'Packing dan pemindahan produk jadi ke gudang/tempat pengiriman', 'Operator packing, pengelola gudang, bagian pengiriman', 'Ukuran produk, jenis kemasan, standar perlindungan, tujuan pengiriman, jumlah produk, identitas/order pelanggan', 'Produk yang telah lolos QC kedua dikemas menggunakan kardus sesuai ukuran, diberi sterofoam agar tidak bergeser, kemudian ditutup menggunakan lakban. Produk selanjutnya dipindahkan ke tempat pengiriman/gudang.', 'Belum terlihat adanya standar packing tertulis dan checklist sebelum produk dipindahkan. Pemindahan produk jadi masih menambah aktivitas handling karena produk harus dipindahkan dari area produksi ke gudang/tempat kirim.', 'Transportation, Motion, Waiting', 'Waktu: tambahan waktu untuk packing dan pemindahan. Biaya: penggunaan kardus, sterofoam, lakban, serta tenaga handling. Kualitas: risiko produk tergores, pecah, atau rusak apabila proses handling dan packing tidak optimal.'],
        ];

        foreach ($flowCahaya as $fc) {
            ProcessFlow::create([
                'ikm_profile_id' => $cahayaOnix->id,
                'nomor_urut' => $fc[0],
                'tahap_proses' => $fc[1],
                'aktor_terlibat' => $fc[2],
                'informasi_seharusnya' => $fc[3],
                'kondisi_saat_ini' => $fc[4],
                'gap_teridentifikasi' => $fc[5],
                'jenis_waste' => $fc[6],
                'dampak' => $fc[7],
                'jenis_produk_konteks' => 'Wastafel Marmer',
            ]);
        }

        // 10. Value Stream UD Putra Abadi (Stepping)
        $flowStepping = [
            [1, 'Pengadaan bahan baku batu kali', 'Pemilik/Pengrajin, Pemasok', 'Jenis batu, jumlah kebutuhan, ukuran, kualitas, harga, dan jadwal pengiriman', 'Pengadaan dilakukan berdasarkan kebutuhan produksi dan pengalaman pengrajin', 'Belum terdapat pencatatan kebutuhan dan standar kualitas bahan baku secara terstruktur', 'Waiting, Overprocessing', 'Waktu: proses pemesanan dapat berulang. Biaya: berisiko membeli bahan yang tidak sesuai. Kualitas: kualitas batu tidak seragam'],
            [2, 'Bahan baku datang di lokasi pengrajin', 'Pengrajin, Pemasok', 'Data penerimaan, jumlah batu, kondisi batu, tanggal dan waktu kedatangan', 'Batu datang dan langsung ditempatkan di lokasi produksi tanpa pencatatan penerimaan secara terstruktur', 'Tidak ada sistem pencatatan stok dan pemeriksaan bahan baku yang terdokumentasi', 'Inventory, Defect', 'Waktu: pemeriksaan dilakukan ulang saat produksi. Biaya: risiko bahan rusak/tidak sesuai. Kualitas: bahan yang tidak layak dapat masuk produksi'],
            [3, 'Bahan baku masuk proses produksi untuk dibuat slep maksimal 50 cm', 'Operator/Pengrajin', 'Ukuran bahan, ukuran slep maksimal 50 cm, urutan produksi, dan target jumlah produksi', 'Batu yang datang langsung diproses menjadi slep dengan ukuran maksimal 50 cm berdasarkan kebiasaan operator', 'Belum terdapat standar kerja dan pencatatan ukuran/hasil produksi secara sistematis', 'Overprocessing, Motion', 'Waktu: pengukuran dan penyesuaian dilakukan manual. Biaya: penggunaan bahan dan energi dapat tidak optimal. Kualitas: ukuran slep berpotensi tidak seragam'],
            [4, 'Batu kali dipotong dengan ketebalan 3 cm; sisa pemotongan dimanfaatkan untuk dinding/wall cladding', 'Operator/Pengrajin', 'Ketebalan potongan 3 cm, ukuran produk, jumlah hasil potongan, dan jumlah sisa yang dapat dimanfaatkan', 'Pemotongan dilakukan sesuai pengalaman operator. Sisa potongan dimanfaatkan untuk produk dinding/wall cladding', 'Belum ada pencatatan hasil potongan utama dan pemanfaatan sisa material', 'Defect, Overprocessing', 'Waktu: diperlukan penyesuaian potongan. Biaya: material berpotensi terbuang. Kualitas: ketebalan produk dapat tidak konsisten'],
            [5, 'Penggerindaan bagian pinggir produk agar rapi dan tidak lancip', 'Operator/Pengrajin', 'Standar kerataan, tingkat kehalusan, keamanan sudut, dan kriteria produk layak', 'Setiap produk digerinda secara manual untuk menghilangkan bagian pinggir yang lancip', 'Belum ada standar hasil finishing dan pencatatan produk yang selesai digerinda', 'Overprocessing, Defect', 'Waktu: proses finishing membutuhkan waktu tambahan. Biaya: penggunaan listrik dan alat gerinda. Kualitas: hasil finishing dapat berbeda antarproduk'],
            [6, 'Packing produk menggunakan kotak kayu, setiap kotak berisi 5 produk stepping', 'Operator/Pengrajin', 'Jumlah produk per kotak, jenis kemasan, kondisi produk, label, dan data pengiriman', 'Produk dimasukkan ke kotak kayu dengan kapasitas 5 produk stepping per kotak', 'Belum ada pencatatan jumlah produk yang telah dipacking dan informasi identitas produk pada kemasan', 'Waiting, Inventory', 'Waktu: pengecekan jumlah dilakukan manual. Biaya: risiko kesalahan packing dan penggunaan material kemasan. Kualitas: produk berisiko rusak jika penataan/packing tidak seragam'],
        ];

        foreach ($flowStepping as $fs) {
            ProcessFlow::create([
                'ikm_profile_id' => $putraAbadi->id,
                'nomor_urut' => $fs[0],
                'tahap_proses' => $fs[1],
                'aktor_terlibat' => $fs[2],
                'informasi_seharusnya' => $fs[3],
                'kondisi_saat_ini' => $fs[4],
                'gap_teridentifikasi' => $fs[5],
                'jenis_waste' => $fs[6],
                'dampak' => $fs[7],
                'jenis_produk_konteks' => 'Stepping Batu Kali',
            ]);
        }

        // 11. Kebutuhan Sistem UD Cahaya Onix
        $sysCahaya = [
            ['2026-08-03', 'Pencatatan dan pengecekan order pelanggan', '07:30–08:00', 'Buku catatan / WhatsApp', 'Data order masih tersebar pada catatan dan komunikasi, sehingga berpotensi terjadi kesalahan dalam mencatat jumlah dan spesifikasi produk.'],
            ['2026-08-03', 'Pengecekan kebutuhan bahan baku marmer', '08:00–08:30', 'Catatan manual dan pengecekan fisik stok', 'Belum tersedia sistem stok bahan baku yang dapat menunjukkan jumlah marmer putih/hitam yang tersedia dan kebutuhan pembelian.'],
            ['2026-08-03', 'Penerimaan bahan baku dari pemasok', '08:30–09:00', 'Catatan manual dan pengecekan visual', 'Belum ada pencatatan digital mengenai tanggal penerimaan, jumlah balok, jenis marmer, dan kondisi bahan saat diterima.'],
            ['2026-08-03', 'Pembelahan dan pemotongan bahan baku', '09:00–10:30', 'Mesin slep dan alat ukur manual', 'Informasi ukuran hasil pemotongan belum terdokumentasi secara sistematis. Sisa potongan dan hasil pemotongan juga belum dicatat.'],
            ['2026-08-03', 'Pembagian bahan ke mesin bubut', '10:30–11:00', '7 mesin bubut dan pencatatan manual', 'Belum terdapat sistem yang mencatat pembagian pekerjaan ke masing-masing mesin dan status pengerjaan setiap produk.'],
            ['2026-08-03', 'Proses pembubutan wastafel', '11:00–12:00', 'Mesin bubut', 'Progress produksi masih dipantau secara langsung oleh pengrajin/pengelola sehingga sulit mengetahui status setiap order secara cepat.'],
            ['2026-08-03', 'Poles dan finishing produk', '13:00–14:30', 'Mesin poles, perlas, Hi-Glossy', 'Belum terdapat pencatatan tahapan proses secara terstruktur sehingga sulit mengetahui produk sudah sampai tahap poles keberapa atau masih membutuhkan proses lanjutan.'],
            ['2026-08-03', 'Pemeriksaan kualitas produk', '14:30–15:30', 'Pemeriksaan visual dan catatan manual', 'Hasil QC belum terdokumentasi dalam sistem secara terstruktur, termasuk jenis cacat, jumlah produk yang diperbaiki, dan status kelulusan QC.'],
            ['2026-08-03', 'Packing produk sesuai order', '15:30–16:00', 'Kardus, sterofoam, lakban, catatan manual', 'Belum ada checklist digital untuk memastikan ukuran, jumlah, spesifikasi, dan kelengkapan packing sesuai dengan order.'],
            ['2026-08-03', 'Pemindahan produk jadi ke gudang/tempat pengiriman', '16:00–16:30', 'Area penyimpanan dan catatan manual', 'Belum tersedia sistem monitoring posisi/status produk sehingga informasi apakah produk berada di proses produksi, gudang, atau siap dikirim masih harus dikonfirmasi secara manual.'],
        ];

        foreach ($sysCahaya as $sc) {
            SystemNeed::create([
                'ikm_profile_id' => $cahayaOnix->id,
                'tanggal' => $sc[0],
                'aktivitas' => $sc[1],
                'waktu' => $sc[2],
                'alat_sistem' => $sc[3],
                'masalah_kendala' => $sc[4],
            ]);
        }

        // 12. Kebutuhan Sistem UD Putra Abadi
        $sysPutra = [
            ['2026-08-06', 'Pencatatan order produk wastafel batu kali', '08:00–08:30', 'Buku catatan / WhatsApp', 'Informasi order masih tersebar dan belum terdokumentasi dalam satu sistem.'],
            ['2026-08-07', 'Pencatatan dan pengecekan bahan baku batu kali yang datang', '09:00–09:40', 'Buku catatan', 'Jumlah bahan baku dan riwayat penerimaan belum tercatat secara terstruktur.'],
            ['2026-08-08', 'Pencatatan proses pemotongan batu sesuai ukuran produk', '08:30–09:30', 'Meteran, alat potong, catatan manual', 'Ukuran dan hasil pemotongan masih dicatat secara manual sehingga berisiko terjadi kesalahan.'],
            ['2026-08-10', 'Pemantauan proses produksi wastafel', '10:00–11:30', 'Mesin gerinda, perlas, catatan manual', 'Belum tersedia sistem untuk mengetahui status dan progres setiap produk berdasarkan order.'],
            ['2026-08-11', 'Pencatatan penggunaan material dan proses pemolesan', '09:00–10:00', 'Mesin gerinda, perlas, catatan manual', 'Penggunaan material dan tahapan pemolesan belum terdokumentasi secara rinci.'],
            ['2026-08-12', 'Pemeriksaan kualitas produk pada QC pertama', '13:00–14:00', 'Pemeriksaan visual, catatan manual', 'Data produk retak/gempil dan produk yang diperbaiki belum tersimpan secara sistematis.'],
            ['2026-08-13', 'Pemeriksaan hasil akhir pada QC kedua', '09:00–10:00', 'Checklist/manual', 'Hasil pemeriksaan masih menggunakan pencatatan manual sehingga sulit melakukan penelusuran riwayat QC.'],
            ['2026-08-14', 'Pencatatan produk yang masuk proses packing', '10:00–10:45', 'Buku catatan, kardus, sterofoam, lakban', 'Belum ada informasi terpusat mengenai produk yang sudah lolos QC dan siap dikemas.'],
            ['2026-08-15', 'Pemindahan produk jadi ke gudang/tempat pengiriman', '13:00–13:45', 'Buku catatan/komunikasi manual', 'Lokasi dan status produk setelah packing belum dapat dipantau secara real-time.'],
            ['2026-08-15', 'Pemilahan dan pencatatan sisa batu untuk wall cladding/urukan', '14:00–15:00', 'Area penyimpanan, catatan manual', 'Belum ada pencatatan jumlah, jenis, dan pemanfaatan limbah sehingga potensi material yang masih bernilai sulit dipantau.'],
        ];

        foreach ($sysPutra as $sp) {
            SystemNeed::create([
                'ikm_profile_id' => $putraAbadi->id,
                'tanggal' => $sp[0],
                'aktivitas' => $sp[1],
                'waktu' => $sp[2],
                'alat_sistem' => $sp[3],
                'masalah_kendala' => $sp[4],
            ]);
        }

        // 13. Pesanan & Real-time WIP Tracking
        $ordersData = [
            ['UD Cahaya Onix', 'ORD-20260801-001', 'Bpk. Hendra Gunawan', '081233445566', 'Sanur, Denpasar, Bali', 'Wastafel (Sink Marmer/Marble)', 15, 'Marmer putih Besole bersih tanpa urat hitam, diameter 40cm, bibir halus', 'ready_to_ship', '2026-08-01'],
            ['UD Cahaya Onix', 'ORD-20260804-002', 'PT Bali Luxury Villa', '081988776655', 'Ubud, Gianyar, Bali', 'Pedestal Marmer', 8, 'Tinggi 85cm, marmer cappuccino langka, finish Hi-Glossy', 'finishing', '2026-08-04'],
            ['UD Cahaya Onix', 'ORD-20260810-003', 'Ibu Maya Lestari', '081399881122', 'Kebayoran Baru, Jakarta Selatan', 'Dispenser Sabun', 50, 'Marmer hitam kombinasi onix bening untuk souvenir korporat', 'bubut_gerinda', '2026-08-10'],
            ['UD Cahaya Onix', 'ORD-20260818-004', 'Arsitek Donny Pratama', '081722334455', 'Seminyak, Bali', 'Wastafel (Sink Marmer/Marble)', 20, 'Marmer putih corak serat abu-abu alami lembut, lubang afur 4.5cm', 'order_received', '2026-08-18'],
            ['UD. Putra Abadi', 'ORD-20260805-005', 'CV Taman Asri Landscape', '085233441122', 'Surabaya Barat, Jawa Timur', 'Stepping Batu Kali', 250, 'Diameter rata-rata 35-40cm, tebal flat 3cm, pinggir halusan', 'shipped', '2026-08-05'],
            ['UD. Putra Abadi', 'ORD-20260812-006', 'Resort Nusa Lembongan', '087811223344', 'Klungkung, Bali', 'Wastafel Batu Kali', 12, 'Batu kali bentuk oval alami, mangkok dalam digosok grade 5 perlas', 'qc_kedua', '2026-08-12'],
            ['UD. Putra Abadi', 'ORD-20260820-007', 'Kafe Joglo Heritage', '081255667788', 'Sleman, D.I. Yogyakarta', 'Kap Lampu Batu Kali', 30, 'Batu kali lubang silang artistic 25cm', 'cutting', '2026-08-20'],
        ];

        foreach ($ordersData as $od) {
            $ikm = ($od[0] === 'UD Cahaya Onix') ? $cahayaOnix : $putraAbadi;
            $order = Order::create([
                'ikm_profile_id' => $ikm->id,
                'kode_order' => $od[1],
                'nama_pelanggan' => $od[2],
                'telepon_pelanggan' => $od[3],
                'alamat_pengiriman' => $od[4],
                'jenis_produk' => $od[5],
                'jumlah' => $od[6],
                'spesifikasi' => $od[7],
                'status' => $od[8],
                'tanggal_order' => $od[9],
            ]);

            // Add stages
            ProductionStage::create([
                'order_id' => $order->id,
                'tahap' => 'penerimaan_order',
                'status' => 'completed',
                'waktu_mulai' => $order->tanggal_order,
                'waktu_selesai' => $order->tanggal_order,
                'operator' => 'Admin Pemesanan',
                'catatan' => 'Pesanan terkonfirmasi melalui sistem',
            ]);

            if (in_array($order->status, ['cutting', 'bubut_gerinda', 'resin_polishing', 'qc_pertama', 'finishing', 'qc_kedua', 'packing', 'ready_to_ship', 'shipped'])) {
                ProductionStage::create([
                    'order_id' => $order->id,
                    'tahap' => 'pemotongan',
                    'status' => 'completed',
                    'waktu_mulai' => now()->subDays(5),
                    'waktu_selesai' => now()->subDays(4),
                    'operator' => 'Operator Slep (Pak Samidi)',
                    'catatan' => 'Bongkahan dipotong sesuai ukuran pesanan',
                ]);
            }

            if (in_array($order->status, ['bubut_gerinda', 'resin_polishing', 'qc_pertama', 'finishing', 'qc_kedua', 'packing', 'ready_to_ship', 'shipped'])) {
                ProductionStage::create([
                    'order_id' => $order->id,
                    'tahap' => 'pembubutan',
                    'status' => 'completed',
                    'waktu_mulai' => now()->subDays(4),
                    'waktu_selesai' => now()->subDays(3),
                    'operator' => 'Pengrajin Bubut (Pak Joko)',
                    'catatan' => 'Pembentukan model mangkok wastafel',
                ]);
            }

            if (in_array($order->status, ['finishing', 'qc_kedua', 'packing', 'ready_to_ship', 'shipped'])) {
                ProductionStage::create([
                    'order_id' => $order->id,
                    'tahap' => 'poles_finishing',
                    'status' => 'completed',
                    'waktu_mulai' => now()->subDays(2),
                    'waktu_selesai' => now()->subDays(1),
                    'operator' => 'Tim Poles (Cahyo)',
                    'catatan' => 'Poles 5 tahap perlas & finishing Hi-Glossy',
                ]);
            }

            if (in_array($order->status, ['ready_to_ship', 'shipped'])) {
                ProductionStage::create([
                    'order_id' => $order->id,
                    'tahap' => 'packing',
                    'status' => 'completed',
                    'waktu_mulai' => now()->subDay(),
                    'waktu_selesai' => now(),
                    'operator' => 'Bagian Packing & Ekspedisi',
                    'catatan' => 'Kardus, sterofoam tebal, crate kayu pengiriman luar pulau',
                ]);
            }
        }

        // 14. Pemanfaatan Limbah (Circular Supply Chain)
        $w1 = WasteRecord::first();
        WasteUtilization::create([
            'waste_record_id' => $w1?->id,
            'ikm_profile_id' => $cahayaOnix->id,
            'jenis_limbah' => 'Brongkolan Marmer Putih',
            'volume_estimasi' => '3.5 Ton / Bulan',
            'pemanfaatan' => 'Bahan Kerajinan Mozaik & Tempat Sabun Mini',
            'status' => 'terjual',
            'harga_jual' => 1750000,
            'pembeli' => 'Pengrajin Mozaik Ngentrong Campurdarat',
            'catatan' => 'Potongan marmer bongkahan kecil disortir dan dijual kembali untuk akselerasi hilirisasi.',
        ]);

        WasteUtilization::create([
            'waste_record_id' => $w1?->id,
            'ikm_profile_id' => $cahayaOnix->id,
            'jenis_limbah' => 'Serbuk & Pasir Bubutan Marmer',
            'volume_estimasi' => '5 Rit Truk',
            'pemanfaatan' => 'Material Urukan Pondasi Bangunan',
            'status' => 'dimanfaatkan',
            'harga_jual' => 600000,
            'pembeli' => 'Kontraktor Perumahan Lokal',
            'catatan' => 'Endapan serbuk marmer padat sangat baik untuk stabilitas tanah pondasi.',
        ]);

        WasteUtilization::create([
            'waste_record_id' => null,
            'ikm_profile_id' => $putraAbadi->id,
            'jenis_limbah' => 'Sisa Potongan Sisi Batu Kali',
            'volume_estimasi' => '450 Pcs Flat',
            'pemanfaatan' => 'Wall Cladding / Dinding Tempel Hiasan Taman',
            'status' => 'tersedia',
            'harga_jual' => 850000,
            'pembeli' => null,
            'catatan' => 'Sisa belahan batu kali yang memiliki 1 sisi rata dipotong rapi untuk dinding rustic natural.',
        ]);
    }
}
