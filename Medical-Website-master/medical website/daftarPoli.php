<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi login
    $sql = "SELECT * FROM pasien WHERE email = ? AND password = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Simpan data user ke sesi
        $_SESSION['id_pasien'] = $user['id'];
        $_SESSION['nama_pasien'] = $user['nama'];

        // Redirect ke halaman daftar poli
        header('Location: daftarPoli.php');
        exit();
    } else {
        echo "<script>alert('Email atau password salah!');</script>";
    }


// Cek apakah user sudah login
if (!isset($_SESSION['id_pasien'])) {
    header("Location: signup.php"); // Redirect ke halaman registrasi/login jika belum login
    exit();
}

// Koneksi ke database
$host = 'localhost'; // Host database
$db   = 'bk_poliklinik_2024'; // Nama database
$user = 'root'; // Username database
$pass = ''; // Password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

// Mendapatkan daftar poli
$sqlPoli = "SELECT * FROM poli";
$stmtPoli = $pdo->prepare($sqlPoli);
$stmtPoli->execute();
$poli = $stmtPoli->fetchAll(PDO::FETCH_ASSOC);


// Debugging - Cek jumlah poli yang diambil
if (count($polis) === 0) {
    echo "<script>alert('Tidak ada poli yang ditemukan.');</script>";
}


// Variabel untuk data dokter dan jadwal

$jadwalDokter = [];
$poli_id = null;
$dokter_id = null;

$poli_id = null;
$jadwal_id = null;

$query = "SELECT id, nama FROM dokter";
$result = mysqli_query($conn, $query);
$dokter_id = isset($_POST['dokter_id']) ? $_POST['dokter_id'] : '';

if (!$result) {
    echo "Error fetching doctors: " . mysqli_error($conn);
    $dokters = []; // Set to empty array to avoid undefined variable notice
} else {
    $dokters = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Proses ketika poli dipilih
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $poli_id = isset($_POST['poli_id']) ? $_POST['poli_id'] : null;
    $dokter_id = isset($_POST['dokter_id']) ? $_POST['dokter_id'] : null;
    $jadwal_id = isset($_POST['jadwal_id']) ? $_POST['jadwal_id'] : null;

    if ($poli_id) {
        // Mendapatkan dokter berdasarkan poli yang dipilih
        $sqlDokter = "SELECT * FROM dokter WHERE id_poli = :poli_id";
        $stmtDokter = $pdo->prepare($sqlDokter);
        $stmtDokter->execute(['poli_id' => $poli_id]);
        $dokters = $stmtDokter->fetchAll(PDO::FETCH_ASSOC);

        // Mendapatkan jadwal dokter jika dokter dipilih
        if ($dokter_id) {
            $sqlJadwal = "SELECT * FROM jadwal_periksa WHERE id_dokter = :dokter_id";
            $stmtJadwal = $pdo->prepare($sqlJadwal);
            $stmtJadwal->execute(['dokter_id' => $dokter_id]);
            $jadwalDokter = $stmtJadwal->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Menangani pendaftaran
    if (isset($_POST['daftar'])) {
        $keluhan = $_POST['keluhan'];
        // Simpan data ke database di sini
        // ...
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<section class="home" id="home">
        <div class="image">
            <img src="./image/home-img.svg" alt="home-img.svg">
        </div>
     <div class="header">
        <a href="#" class="logo"><i class="fas fa-heartbeat"></i> medicare.</a>
        <nav class="navbar">
            <a href="index.html">home</a>
            <a href="#services">services</a>
            <a href="#about">about</a>
            <a href="#doctors">doctors</a>
            <a href="#book">book</a>
            <a href="admin.php" class="btn">Admin Login</a>
            <a href="profile.html">Profile</a>
            <link rel="stylesheet" href="./css/style.css">
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Poli</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1>Pendaftaran Poli</h1>

    <!-- Nomor Rekam Medis -->
    <div class="form-group">
        <label for="rekam_medis">Nomor Rekam Medis:</label>
        <input type="text" id="rekam_medis" name="rekam_medis" class="form-control" value="<?php echo isset($_SESSION['id_pasien']) ? $_SESSION['id_pasien'] : ''; ?>" readonly>
    </div>

    <!-- Pilih Poli -->
    <form action="" method="POST" class="mb-4">
        <div class="form-group">
            <label for="poli_id">Pilih Poli:</label>
            <select name="poli_id" id="poli_id" class="form-control" onchange="this.form.submit()" required>
                <option value="">-- Pilih Poli --</option>
                <?php foreach ($poli as $item): ?>
    <option value="<?php echo $item['id']; ?>" <?php echo ($item['id'] == $poli_id) ? 'selected' : ''; ?>>
        <?php echo htmlspecialchars($item['nama_poli']); ?>
    </option>
<?php endforeach; ?>

            </select>
        </div>
    </form>

    <!-- Pilih Jadwal Dokter -->
        <h2>Pilih Dokter</h2>
        <form action="" method="POST" class="mb-4">
    <input type="hidden" name="poli_id" value="<?php echo htmlspecialchars($poli_id); ?>">
    <div class="form-group">
        <label for="dokter_id">Dokter:</label>
        <select name="dokter_id" id="dokter_id" class="form-control" onchange="this.form.submit()" required>
            <option value="">-- Pilih Dokter --</option>
            <?php if (!empty($dokters)): ?>
                <?php foreach ($dokters as $dokter): ?>
                    <option value="<?php echo $dokter['id']; ?>" <?php echo ($dokter['id'] == $dokter_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dokter['nama']); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Tidak ada dokter tersedia</option>
            <?php endif; ?>
        </select>
    </div>
</form>


    <!-- Text Box Keluhan -->
  
        <h3>Keluhan</h3>
        <form action="" method="POST">
            <input type="hidden" name="poli_id" value="<?php echo htmlspecialchars($poli_id); ?>">
            <input type="hidden" name="dokter_id" value="<?php echo htmlspecialchars($dokter_id); ?>">
            <div class="form-group">
                <label for="keluhan">Keluhan:</label>
                <textarea name="keluhan" id="keluhan" class="form-control" required></textarea>
            </div>
            <button type="submit" name="daftar" class="btn btn-primary">Daftar</button>
        </form>
    


    <!-- Riwayat Daftar Poli -->
    <h2 class="mt-5">Riwayat Daftar Poli</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Jadwal</th>
                <th>Keluhan</th>
                <th>No Antrian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Asumsikan data riwayat sudah diambil di sesi PHP dan disimpan di $riwayatPoli
            if (!empty($riwayatPoli)) {
                foreach ($riwayatPoli as $index => $riwayat): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($riwayat['nama_poli']); ?></td>
                        <td><?php echo htmlspecialchars($riwayat['nama_dokter']); ?></td>
                        <td><?php echo htmlspecialchars($riwayat['jadwal']); ?></td>
                        <td><?php echo htmlspecialchars($riwayat['keluhan']); ?></td>
                        <td><?php echo htmlspecialchars($riwayat['no_antrian']); ?></td>
                    </tr>
                <?php endforeach;
            } else {
                echo '<tr><td colspan="6" class="text-center">Belum ada riwayat pendaftaran.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>


