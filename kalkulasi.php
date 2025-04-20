<?php
session_start();

// Fungsi untuk membersihkan input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $merk_kendaraan = $_POST['merk_kendaraan'];
    $tipe_kendaraan = $_POST['tipe_kendaraan'];
    $tahun_pembuatan = $_POST['tahun_pembuatan'];
    $nilai_kendaraan = $_POST['nilai_kendaraan'];
    $jenis_asuransi = $_POST['jenis_asuransi'];
    $lokasi_penggunaan = $_POST['lokasi_penggunaan'];
    $perluasan = isset($_POST['perluasan']) ? $_POST['perluasan'] : [];

    // Validasi
    $errors = [];
    if (empty($merk_kendaraan)) $errors[] = "Merk kendaraan harus dipilih";
    if (empty($tipe_kendaraan)) $errors[] = "Tipe kendaraan harus dipilih";
    if ($tahun_pembuatan <= 0) $errors[] = "Tahun pembuatan tidak valid";
    if ($nilai_kendaraan < 1000000) $errors[] = "Nilai kendaraan minimal Rp 1.000.000";
    if (empty($jenis_asuransi)) $errors[] = "Jenis asuransi harus dipilih";
    if (empty($lokasi_penggunaan)) $errors[] = "Wilayah penggunaan harus dipilih";

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php");
        exit;
    }

    // Klasifikasi jenis kendaraan
// Daftar tipe kendaraan yang dikategorikan sebagai bus/truck
$tipe_truck = ['pick up', 'truk', 'truck', 'dyna', 'canter', 'colt diesel', 'elf', 'l300'];
$tipe_bus = ['bus'];

if (in_array(strtolower($tipe_kendaraan), $tipe_truck)) {
    $jenis_kendaraan = 'truck';
} elseif (in_array(strtolower($tipe_kendaraan), $tipe_bus)) {
    $jenis_kendaraan = 'bus';
} elseif (in_array(strtolower($tipe_kendaraan), ['motor', 'sepeda motor'])) {
    $jenis_kendaraan = 'roda_2';
} else {
    $jenis_kendaraan = 'non_bus_truck';
}

    // Rate dasar
    $rate = 0;

    // Hitung rate sesuai jenis kendaraan dan jenis asuransi
    if ($jenis_kendaraan == 'non_bus_truck') {
        if ($jenis_asuransi == 'comprehensive') {
            if ($nilai_kendaraan <= 125000000) {
                $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0382 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0326 : 0.0253);
            } elseif ($nilai_kendaraan <= 200000000) {
                $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0267 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0247 : 0.0269);
            } elseif ($nilai_kendaraan <= 400000000) {
                $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0218 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0208 : 0.0179);
            } elseif ($nilai_kendaraan <= 800000000) {
                $rate = 0.0120;
            } else {
                $rate = 0.0105;
            }
        } elseif ($jenis_asuransi == 'tlo') {
            if ($nilai_kendaraan <= 125000000) {
                $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0047 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0065 : 0.0051);
            } elseif ($nilai_kendaraan <= 200000000) {
                $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0063 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0044 : 0.0044);
            } elseif ($nilai_kendaraan <= 400000000) {
                $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0041 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0038 : 0.0029);
            } elseif ($nilai_kendaraan <= 800000000) {
                $rate = 0.0025;
            } else {
                $rate = 0.002;
            }
        }

    } elseif ($jenis_kendaraan == 'truck') {
        if ($jenis_asuransi == 'comprehensive') {
            $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0242 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0239 : 0.0223);
        } elseif ($jenis_asuransi == 'tlo') {
            $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0088 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0168 : 0.0081);
        }
    } elseif ($jenis_kendaraan == 'bus') {
        if ($jenis_asuransi == 'comprehensive') {
            $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0104 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0104 : 0.0088);
        } elseif ($jenis_asuransi == 'tlo') {
            $rate = ($lokasi_penggunaan == 'wilayah_1') ? 0.0023 : (($lokasi_penggunaan == 'wilayah_2') ? 0.0023 : 0.0018);
        }

    } elseif ($jenis_kendaraan == 'roda_2') {
        $rate = 0.02; // Flat rate untuk motor
    }

    $premi_dasar = $nilai_kendaraan * $rate;
     // Dapatkan usia kendaraan
    $tahun_sekarang = date("Y");
    $usia_kendaraan = $tahun_sekarang - $tahun_pembuatan;

    // Hitung premi dasar tanpa loading
    $rate_asuransi = 0.0218;
    $premi_dasar_awal = $rate_asuransi * $nilai_kendaraan;

    // Hitung loading jika usia > 5
    $loading_rate = 0;
    if ($usia_kendaraan > 5) {
        $tahun_kena_loading = $usia_kendaraan - 5;
        $loading_rate = $tahun_kena_loading * 0.05 * $rate_asuransi * $nilai_kendaraan;
    }

$premi_dasar = $premi_dasar_awal + $loading_rate;


    // Perluasan
    $biaya_perluasan = 0;
    $perluasan_detail = [];

    switch ($lokasi_penggunaan) {
        case 'wilayah_1':
            $banjir = 0.00075;
            $gempa = 0.001;
            $kerusuhan = 0.0005;
            $terorisme = 0.0005;
            $tjh = 100000;
            break;
        case 'wilayah_2':
            $banjir = 0.0012;
            $gempa = 0.001;
            $kerusuhan = 0.0005;
            $terorisme = 0.0005;
            $tjh = 100000;
            break;
        case 'wilayah_3':
            $banjir = 0.00075;
            $gempa = 0.00075;
            $kerusuhan = 0.0005;
            $terorisme = 0.0005;
            $tjh = 100000;
            break;
        default:
            $banjir = $gempa = $kerusuhan = $terorisme = 0;
            $tjh = 0;
    }

    foreach ($perluasan as $p) {
        switch ($p) {
            case 'banjir':
                $biaya = $nilai_kendaraan * $banjir;
                $biaya_perluasan += $biaya;
                $perluasan_detail['Banjir'] = $biaya;
                break;
            case 'gempa_bumi':
                $biaya = $nilai_kendaraan * $gempa;
                $biaya_perluasan += $biaya;
                $perluasan_detail['Gempa Bumi'] = $biaya;
                break;
            case 'kerusuhan':
                $biaya = $nilai_kendaraan * $kerusuhan;
                $biaya_perluasan += $biaya;
                $perluasan_detail['Kerusuhan'] = $biaya;
                break;
         case 'terorisme':
                $biaya = $nilai_kendaraan * $terorisme;
                $biaya_perluasan += $biaya;
                $perluasan_detail['Terorisme'] = $biaya;
                break;
            case 'tanggung_jawab_hukum':
                $biaya_perluasan += $tjh;
                $perluasan_detail['Tanggung Jawab Hukum'] = $tjh;
                break;
        }
    }

    $premi_gross = $premi_dasar + $biaya_perluasan;
$diskon = 0.25 * $premi_gross;
$biaya_admin = 30000;
$premi_total = $premi_gross - $diskon + $biaya_admin;

$_SESSION['hasil_kalkulasi'] = $premi_total;
$_SESSION['detail_kalkulasi'] = array_merge([

    'Premi Gross' => $premi_gross,
    'Diskon 25%' => -$diskon,
    'Biaya Administrasi' => $biaya_admin,
    'Premi Total' => $premi_total
]);

$_SESSION['data_kendaraan'] = [
    'Merk' => $merk_kendaraan,
    'Tipe' => $tipe_kendaraan,
    'Tahun' => $tahun_pembuatan,
    'Nilai Kendaraan' => $nilai_kendaraan,
    'Jenis Asuransi' => $jenis_asuransi,
    'Wilayah' => $lokasi_penggunaan
];

header("Location: index.php");
exit;

}
?>


