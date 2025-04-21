<?php
session_start();
$isLoggedIn = isset($_SESSION['user']); // Cek apakah user sudah login

if (isset($_GET['reset'])) {
    unset($_SESSION['hasil_kalkulasi']);
    unset($_SESSION['detail_kalkulasi']);
    unset($_SESSION['data_kendaraan']);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASRI - Asuransi Astra Safe Risk Indonesia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('assets/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .header {
      background-color: #0d6efd;
      color: white;
      padding: 30px;
      margin-bottom: 30px;
    }

    .logo {
      height: 80px;
      width: auto;
      margin-right: 60px;
    }

    .footer {
      background-color: #0d6efd;
      padding: 18px 0;
      position: relative;
      bottom: 0;
      width: 100%;
      color: white;
    }

    .footer-left {
      text-align: left;
      margin-left: 20px;
      padding-left: 450px !important;
      font-size: 20px;
    }
  </style>
</head>
<body class="container py-4">

<!-- Header -->
<div class="header d-flex align-items-center justify-content-center">
    <img src="assets/logo.png" alt="Logo ASRI" class="logo me-3">
    <div class="text-center">
        <h1 class="mb-1">ASRI - Asuransi Astra Safe Risk Indonesia</h1>
        <p class="mb-0">Hitung estimasi premi asuransi kendaraan Anda dengan mudah</p>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Beranda</a>
      <div class="d-flex">
        <?php if ($isLoggedIn): ?>
          <a class="btn btn-outline-danger" href="logout.php">Logout</a>
        <?php else: ?>
          <a class="btn btn-outline-primary me-2" href="login.php">Login</a>
          <a class="btn btn-outline-success" href="register.php">Daftar</a>
        <?php endif; ?>
      </div>
    </div>
</nav>

<!-- Konten -->
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <!-- Tampilkan Hasil Perhitungan -->
            <?php if (isset($_SESSION['hasil_kalkulasi'])): ?>
                <div class="result-box mb-4">
                    <h3>Hasil Perhitungan</h3>
                    <p>Perkiraan premi asuransi kendaraan Anda adalah:</p>
                    <h2 class="text-center text-primary">Rp <?= number_format($_SESSION['hasil_kalkulasi'], 0, ',', '.') ?> / tahun</h2>
                    
                    <?php if (isset($_SESSION['data_kendaraan'])): ?>
                    <div class="mt-3">
                        <h5>Data Kendaraan:</h5>
                        <ul>
                            <li>Merk: <?= ucfirst($_SESSION['data_kendaraan']['Merk']) ?></li>
                            <li>Tipe: <?= ucfirst($_SESSION['data_kendaraan']['Tipe']) ?></li>
                            <li>Tahun: <?= $_SESSION['data_kendaraan']['Tahun'] ?></li>
                            <li>Nilai Kendaraan: Rp <?= number_format($_SESSION['data_kendaraan']['Nilai Kendaraan'], 0, ',', '.') ?></li>
                            <li>Jenis Asuransi: <?= strtoupper($_SESSION['data_kendaraan']['Jenis Asuransi']) ?></li>
                            <li>Wilayah: <?= ucfirst(str_replace('_', ' ', $_SESSION['data_kendaraan']['Wilayah'])) ?></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['detail_kalkulasi'])): ?>
                    <div class="mt-3">
                        <h5>Rincian Perhitungan:</h5>
                        <ul>
                            <?php foreach ($_SESSION['detail_kalkulasi'] as $key => $value): ?>
                                <?php if (is_array($value)): ?>
                                    <li><?= $key ?>:
                                        <ul>
                                            <?php foreach ($value as $subKey => $subValue): ?>
                                                <li><?= $subKey ?>: Rp <?= number_format($subValue, 0, ',', '.') ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php else: ?>
                                    <li><?= $key ?>: Rp <?= number_format($value, 0, ',', '.') ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <?php
                    unset($_SESSION['hasil_kalkulasi']);
                    unset($_SESSION['detail_kalkulasi']);
                ?>
            <?php endif; ?>

            <!-- Form Input -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Data Kendaraan</h4>
                </div>
                <div class="card-body">
                    <form action="kalkulasi.php" method="post">
                        <!-- Merk dan Tipe -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="merk_kendaraan" class="form-label">Merk Kendaraan</label>
                                <select class="form-select" id="merk_kendaraan" name="merk_kendaraan" required>
                                    <option value="">Pilih Merk Kendaraan</option>
                                    <option value="toyota">Toyota</option>
                                    <option value="honda">Honda</option>
                                    <option value="daihatsu">Daihatsu</option>
                                    <option value="suzuki">Suzuki</option>
                                    <option value="mitsubishi">Mitsubishi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipe_kendaraan" class="form-label">Tipe Kendaraan</label>
                                <select class="form-select" id="tipe_kendaraan" name="tipe_kendaraan" required>
                                    <option value="">Pilih Tipe Kendaraan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tahun Pembuatan -->
                        <div class="mb-3">
                            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                            <select class="form-select" id="tahun_pembuatan" name="tahun_pembuatan" required>
                                <option value="">Pilih Tahun</option>
                                <?php
                                $tahun_sekarang = date("Y");
                                for ($tahun = $tahun_sekarang; $tahun >= $tahun_sekarang - 20; $tahun--) {
                                    echo "<option value='$tahun'>$tahun</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Nilai Kendaraan -->
                        <div class="mb-3">
                            <label for="nilai_kendaraan" class="form-label">Nilai Kendaraan (Rp)</label>
                            <input type="number" class="form-control" id="nilai_kendaraan" name="nilai_kendaraan" placeholder="Contoh: 200000000" required min="1000000">
                        </div>

                        <!-- Jenis Asuransi -->
                        <div class="mb-3">
                            <label for="jenis_asuransi" class="form-label">Jenis Asuransi</label>
                            <select class="form-select" id="jenis_asuransi" name="jenis_asuransi" required>
                                <option value="">Pilih Jenis Asuransi</option>
                                <option value="comprehensive">All Risk (Comprehensive)</option>
                                <option value="tlo">Total Loss Only (TLO)</option>
                            </select>
                        </div>

                        <!-- Wilayah Penggunaan -->
                        <div class="mb-3">
                            <label for="lokasi_penggunaan" class="form-label">Wilayah Penggunaan</label>
                            <select class="form-select" id="lokasi_penggunaan" name="lokasi_penggunaan" required>
                                <option value="">Pilih Wilayah</option>
                                <option value="wilayah_1">Wilayah 1 (Sumatera dan sekitarnya)</option>
                                <option value="wilayah_2">Wilayah 2 (Jakarta, Jabar, Banten)</option>
                                <option value="wilayah_3">Wilayah 3 (Lainnya)</option>
                            </select>
                        </div>

                        <!-- Perluasan Asuransi -->
                        <div class="mb-3">
                            <label class="form-label">Perluasan Asuransi</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="banjir" name="perluasan[]" value="banjir">
                                <label class="form-check-label" for="banjir">Perlindungan Banjir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gempa_bumi" name="perluasan[]" value="gempa_bumi">
                                <label class="form-check-label" for="gempa_bumi">Perlindungan Gempa Bumi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="kerusuhan" name="perluasan[]" value="kerusuhan">
                                <label class="form-check-label" for="kerusuhan">Perlindungan Huru Hara dan Kerusuhan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terorisme" name="perluasan[]" value="terorisme">
                                <label class="form-check-label" for="terorisme">Perlindungan Terorisme dan Sabotase</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tanggung_jawab_hukum" name="perluasan[]" value="tanggung_jawab_hukum">
                                <label class="form-check-label" for="tanggung_jawab_hukum">Tanggung Jawab Hukum Pihak Ketiga</label>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Hitung Premi Asuransi</button>
                            <a href="?reset=1" class="btn btn-outline-danger">Reset Form</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p class="footer-left">&copy; 2025 Kalkulator Asuransi Kendaraan Indonesia</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const tipeKendaraan = {
        toyota: ["Agya", "Avanza", "Camry", "Innova", "Dyna" ],
        honda: ["Brio", "Jazz", "Civic", "HRV"],
        daihatsu: ["Ayla", "Sigra", "Terios"],
        suzuki: ["Ertiga", "XL7", "Baleno"],
        mitsubishi: ["Fuso", "L300", "XPander"],
    };

    document.getElementById("merk_kendaraan").addEventListener("change", function () {
        const merk = this.value;
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
    });
</script>

</body>
</html>

