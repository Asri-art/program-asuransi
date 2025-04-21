<?php
session_start();
require_once 'database.php'; // Koneksi ke database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $merk = $_POST['merk_kendaraan'];
    $tipe = $_POST['tipe_kendaraan'];
    $tahun = $_POST['tahun_pembuatan'];
    $nilai = $_POST['nilai_kendaraan'];
    $jenis_asuransi = $_POST['jenis_asuransi'];
    $wilayah = $_POST['wilayah_penggunaan'];
    $perluasan = isset($_POST['perluasan']) ? implode(',', $_POST['perluasan']) : null;
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['nomor_telepon'];
    $no_polisi = $_POST['no_polisi'];
    $no_rangka = $_POST['no_rangka'];
    $no_mesin = $_POST['no_mesin'];
    $warna = $_POST['warna_kendaraan'];

    // Upload files
    $upload_dir = 'uploads/';
    $foto_stnk = $upload_dir . basename($_FILES['foto_stnk']['name']);
    $foto_kendaraan = $upload_dir . basename($_FILES['foto_kendaraan']['name']);
    $foto_sim_ktp = $upload_dir . basename($_FILES['foto_sim_ktp']['name']);

    if (move_uploaded_file($_FILES['foto_stnk']['tmp_name'], $foto_stnk) &&
        move_uploaded_file($_FILES['foto_kendaraan']['tmp_name'], $foto_kendaraan) &&
        move_uploaded_file($_FILES['foto_sim_ktp']['tmp_name'], $foto_sim_ktp)) {

        // Simpan data ke database
        $stmt = $conn->prepare("INSERT INTO kendaraan (pengguna_id, merk, tipe, tahun, nilai, jenis_asuransi, wilayah_penggunaan, perluasan_asuransi, no_polisi, no_rangka, no_mesin, warna, stnk_path, kendaraan_photo_path, sim_ktp_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississsssssssss", $_SESSION['user_id'], $merk, $tipe, $tahun, $nilai, $jenis_asuransi, $wilayah, $perluasan, $no_polisi, $no_rangka, $no_mesin, $warna, $foto_stnk, $foto_kendaraan, $foto_sim_ktp);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Pendaftaran berhasil! Menunggu persetujuan kantor pusat.</div>";
        } else {
            echo "<div class='alert alert-danger'>Terjadi kesalahan. Silakan coba lagi.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Gagal mengunggah dokumen. Pastikan file yang diunggah valid.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const tipeKendaraan = {
            toyota: ["Agya", "Avanza", "Camry", "Innova", "Dyna"],
            honda: ["Brio", "Jazz", "Civic", "HRV", "CRV"],
            daihatsu: ["Ayla", "Sigra", "Terios", "Xenia"],
            suzuki: ["Ertiga", "XL7", "Baleno", "Carry"],
            mitsubishi: ["Fuso", "L300", "Xpander", "Outlander"]
        };

        function updateTipeKendaraan() {
            const merk = document.getElementById("merk_kendaraan").value;
            const tipeSelect = document.getElementById("tipe_kendaraan");
            tipeSelect.innerHTML = '<option value="">Pilih Tipe Kendaraan</option>';

            if (merk in tipeKendaraan) {
                tipeKendaraan[merk].forEach(function (tipe) {
                    const option = document.createElement("option");
                    option.value = tipe.toLowerCase();
                    option.textContent = tipe;
                    tipeSelect.appendChild(option);
                });
            }
        }
    </script>
</head>
<body class="container py-5">
    <h2>Pendaftaran Kendaraan</h2>
    <form method="POST" enctype="multipart/form-data">
        <!-- Data Kendaraan -->
        <h4>Data Kendaraan</h4>
        <div class="mb-3">
            <label for="merk_kendaraan" class="form-label">Merk Kendaraan</label>
            <select name="merk_kendaraan" id="merk_kendaraan" class="form-select" onchange="updateTipeKendaraan()" required>
                <option value="">Pilih Merk Kendaraan</option>
                <option value="toyota">Toyota</option>
                <option value="honda">Honda</option>
                <option value="daihatsu">Daihatsu</option>
                <option value="suzuki">Suzuki</option>
                <option value="mitsubishi">Mitsubishi</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="tipe_kendaraan" class="form-label">Tipe Kendaraan</label>
            <select name="tipe_kendaraan" id="tipe_kendaraan" class="form-select" required>
                <option value="">Pilih Tipe Kendaraan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
            <input type="number" name="tahun_pembuatan" id="tahun_pembuatan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nilai_kendaraan" class="form-label">Nilai Kendaraan (Rp)</label>
            <input type="number" name="nilai_kendaraan" id="nilai_kendaraan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="jenis_asuransi" class="form-label">Jenis Asuransi</label>
            <select name="jenis_asuransi" id="jenis_asuransi" class="form-select" required>
                <option value="comprehensive">All Risk (Comprehensive)</option>
                <option value="tlo">Total Loss Only (TLO)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="wilayah_penggunaan" class="form-label">Wilayah Penggunaan</label>
            <select name="wilayah_penggunaan" id="wilayah_penggunaan" class="form-select" required>
                <option value="wilayah_1">Wilayah 1 (Sumatera dan sekitarnya)</option>
                <option value="wilayah_2">Wilayah 2 (Jakarta, Jawa Barat, Banten)</option>
                <option value="wilayah_3">Wilayah 3 (Lainnya)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="perluasan_asuransi" class="form-label">Perluasan Asuransi</label>
            <div class="form-check">
                <input type="checkbox" name="perluasan[]" value="banjir" class="form-check-input" id="banjir">
                <label for="banjir" class="form-check-label">Perlindungan Banjir</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="perluasan[]" value="gempa_bumi" class="form-check-input" id="gempa_bumi">
                <label for="gempa_bumi" class="form-check-label">Perlindungan Gempa Bumi</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="perluasan[]" value="kerusuhan" class="form-check-input" id="kerusuhan">
                <label for="kerusuhan" class="form-check-label">Perlindungan Huru Hara dan Kerusuhan</label>
                </div>
            <div class="form-check">
            <input type="checkbox" name="perluasan[]" value="terorisme" class="form-check-input" id="terorisme">
            <label for="terorisme" class="form-check-label">Perlindungan Terorisme dan Sabotase</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="perluasan[]" value="tanggung_jawab_hukum" class="form-check-input" id="tanggung_jawab_hukum">
            <label for="tanggung_jawab_hukum" class="form-check-label">Tanggung Jawab Hukum Pihak Ketiga</label>
        </div>


        <!-- Data Pribadi -->
        <h4>Data Pribadi</h4>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" required>
        </div>

        <!-- Data Kendaraan Tambahan -->
        <h4>Data Kendaraan Tambahan</h4>
        <div class="mb-3">
            <label for="no_polisi" class="form-label">Nomor Polisi</label>
            <input type="text" name="no_polisi" id="no_polisi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="no_rangka" class="form-label">Nomor Rangka</label>
            <input type="text" name="no_rangka" id="no_rangka" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="no_mesin" class="form-label">Nomor Mesin</label>
            <input type="text" name="no_mesin" id="no_mesin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="warna_kendaraan" class="form-label">Warna Kendaraan</label>
            <input type="text" name="warna_kendaraan" id="warna_kendaraan" class="form-control" required>
        </div>

        <!-- Unggah Dokumen -->
        <h4>Unggah Dokumen</h4>
        <div class="mb-3">
            <label for="foto_stnk" class="form-label">Foto STNK</label>
            <input type="file" name="foto_stnk" id="foto_stnk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="foto_kendaraan" class="form-label">Foto Kendaraan</label>
            <input type="file" name="foto_kendaraan" id="foto_kendaraan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="foto_sim_ktp" class="form-label">Foto SIM/KTP</label>
            <input type="file" name="foto_sim_ktp" id="foto_sim_ktp" class="form-control" required>
        </div>
         <!-- Keterangan Tambahan -->
         <div class="mb-3">
            <label for="keterangan_tambahan" class="form-label">Keterangan Tambahan</label>
            <textarea name="keterangan_tambahan" id="keterangan_tambahan" class="form-control" maxlength="200" placeholder="Isi keterangan tambahan jika diperlukan (maksimal 200 karakter)"></textarea>
        </div>
         <!-- Perhitungan Nilai Premi -->
         <div class="mb-3">
            <label for="nilai_premi" class="form-label">Estimasi Nilai Premi (Rp)</label>
            <input type="text" name="nilai_premi" id="nilai_premi" class="form-control" readonly value="<?= number_format($nilai_premi, 0, ',', '.') ?>">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>
</html>
