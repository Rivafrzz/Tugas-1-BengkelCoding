<?php
session_start();
include 'db.php'; // File untuk koneksi database

// Inisialisasi variabel
$username = "Admin";
$password = "Admin";

// Cek jika form login disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Validasi username dan password
    if ($input_username === $username && $input_password === $password) {
        $_SESSION['loggedin'] = true;
        header("Location: admin.php"); // Redirect ke halaman admin
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}

// Ambil jumlah dokter, pasien, dan poli
$query_dokter = "SELECT COUNT(*) as total FROM dokter";
$result_dokter = $conn->query($query_dokter);
$total_dokter = $result_dokter->fetch_assoc()['total'];

$query_pasien = "SELECT COUNT(*) as total FROM pasien";
$result_pasien = $conn->query($query_pasien);
$total_pasien = $result_pasien->fetch_assoc()['total'];

$query_poli = "SELECT COUNT(*) as total FROM poli";
$result_poli = $conn->query($query_poli);
$total_poli = $result_poli->fetch_assoc()['total'];

// Cek apakah admin sudah login
if (!isset($_SESSION['loggedin'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Header Section Starts -->
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
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
    <!-- Header Section End -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare | Manage Medicines</title>
    <link rel="stylesheet" href="./css/style.css">
        <title>Login Admin</title>
        
    </head>
    <body>
        <div class="login-container">
            <h2>Login Admin</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    // Jika sudah login, tampilkan dashboard dan menu

    // Ambil data Dokter dan Pasien
    $query_dokter_data = "SELECT nama, alamat FROM dokter";
    $result_dokter_data = $conn->query($query_dokter_data);

    $query_pasien_data = "SELECT nama, alamat FROM pasien";
    $result_pasien_data = $conn->query($query_pasien_data);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- Header Section Starts -->
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
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
    <!-- Header Section End -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare | Manage Medicines</title>
    <link rel="stylesheet" href="./css/style.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Admin</title>
         
    </head>
    <body>
        <div class="dashboard-container">
            <h2>Dashboard Admin</h2>
            <p>Total Poli: <?php echo $total_poli; ?></p>
            <p>Total Dokter: <?php echo $total_dokter; ?></p>
            <p>Total Pasien: <?php echo $total_pasien; ?></p>

            <h3>Data Dokter</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_dokter_data->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h3>Data Pasien</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_pasien_data->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h3>Manage Data</h3>
            <ul>
                <li><a href="manageDoctors.php">Manage Doctors</a></li>
                <li><a href="managePatients.php">Manage Patients</a></li>
                <li><a href="manageMeds.php">Manage Medicines</a></li>
                <li><a href="managePoli.php">Manage Poli</a></li>
            </ul>

            <form method="POST" action="">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
        
        <!-- Footer section starts -->
        <section class="footer">
            <div class="box-container">
                <div class="box">
                    <h3>Quick Links</h3>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Home</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Services</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> About</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Doctors</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Book</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Review</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Blogs</a>
                </div>
                <div class="box">
                    <h3>Our Services</h3>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Free Checkups</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> 24/7 Ambulance</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Expert Doctors</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> Pharmacy Services</a>
                    <a href="#"> <i class="fas fa-chevron-right"></i> In-Patient Care</a>
                </div>
                <div class="box">
                    <h3>Contact Info</h3>
                    <a href="#"> <i class="fas fa-phone"></i> +123-456-7890</a>
                    <a href="#"> <i class="fas fa-phone"></i> +111-222-3333</a>
                    <a href="#"> <i class="fas fa-envelope"></i> info@medcare.com</a>
                    <a href="#"> <i class="fas fa-map-marker-alt"></i> Action Area 1, Newtown, India </a>
                </div>
            </div>
            <div class="credit"> © 2024 Medcare. All Rights Reserved.</div>
        </section>
        <!-- Footer section End -->

    </body>
    </html>
    <?php
}

// Proses Logout
if (isset($_POST['logout'])) {
    session_unset(); // Menghapus semua variabel sesi
    session_destroy(); // Menghancurkan sesi
    header("Location: admin.php"); // Redirect ke halaman login
    exit;
}
?>
