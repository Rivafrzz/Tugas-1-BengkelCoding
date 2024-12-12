<?php
// config.php - Koneksi ke database
$host = 'localhost'; 
$db   = 'bk_poliklinik_2024';
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $no_em = $_POST['no_em'];
    $password = $_POST['password'];

    // Masukkan data ke database
    $sql = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_em, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nama, $alamat, $no_ktp, $no_hp, $no_em, $password])) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='signup.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan data pengguna
    $sql = "SELECT * FROM pasien WHERE no_em = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password
    if ($user && $password === $user['password']) {
        session_start();
        $_SESSION['user'] = $user;
        header("Location: daftarPoli.php");
        exit();
    } else {
        echo "<script>alert('Email atau password tidak cocok.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/styles.css" />
    <title>Registrasi / Login Pasien</title>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- Login Form -->
                <form class="sign-in-form" id="sign-in-form" method="POST" action="">
                    <h2 class="title">Login</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <input type="submit" value="Login" class="btn solid" name="login" />
                    <p class="social-text">Atau login dengan platform sosial</p>
                </form>

                <!-- Registration Form -->
                <form action="" method="POST" class="sign-up-form" id="sign-up-form">
                    <h2 class="title">Registrasi Pasien</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nama" placeholder="Nama Lengkap" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="no_em" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-address-card"></i>
                        <input type="text" name="alamat" placeholder="Alamat" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-id-card"></i>
                        <input type="number" name="no_ktp" placeholder="Nomor KTP" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-phone"></i>
                        <input type="number" name="no_hp" placeholder="Nomor HP" required />
                    </div>
                    <input type="submit" class="btn" value="Registrasi" name="register" />
                    <p class="social-text">Atau registrasi dengan platform sosial</p>
                </form>
            </div>
        </div>

        <!-- Panels -->
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Baru di sini?</h3>
                    <p>Mulai dengan melakukan registrasi untuk mendapatkan akses.</p>
                    <button class="btn transparent" id="sign-up-btn">Registrasi</button>
                </div>
                <img src="./image/login.svg" class="image" alt="Gambar Registrasi" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Sudah menjadi anggota?</h3>
                    <p>Klik di sini untuk login.</p>
                    <button class="btn transparent" id="sign-in-btn">Login</button>
                </div>
                <img src="./image/reg.svg" class="image" alt="Gambar Login" />
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script>
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });
    </script>
</body>
</html>
