<?php
session_start();
require_once 'db.php'; // Ganti dengan file koneksi database Anda

// Cek apakah admin sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: admin.php");
    exit;
}

// Tambah Poli
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_poli'])) {
    $nama_poli = $_POST['nama_poli'];

    $query = "INSERT INTO poli (nama_poli) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nama_poli);

    if (!$stmt->execute()) {
        die("Error saat menambahkan data poli: " . $stmt->error);
    }
    $stmt->close();
}

// Hapus Poli
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $query = "DELETE FROM poli WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Error saat menghapus data poli: " . $stmt->error);
    }
    $stmt->close();
}

// Data Poli
$query_poli = "SELECT * FROM poli";
$result_poli = $conn->query($query_poli);

if (!$result_poli) {
    die("Error saat mengambil data poli: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare | Manage Poli</title>
    <link rel="shortcut icon" href="image/heartbeat-solid.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- Header Section Starts -->
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

    <section class="home" id="home">
        <div class="content">
            <h2>Manage Poli</h2>

            <!-- Tambah Poli -->
            <div class="form-container">
                <h3>Tambah Poli</h3>
                <form method="POST">
                    <input type="text" name="nama_poli" placeholder="Nama Poli" required>
                    <button type="submit" name="add_poli" class="btn">Tambah Poli</button>
                </form>
            </div>

            <!-- Daftar Poli -->
            <div class="table-container">
                <h3>Daftar Poli</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Poli</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_poli->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['nama_poli'] ?></td>
                                <td>
                                    <a href="editPoli.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                                    <a href="managePoli.php?delete_id=<?= $row['id'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus?')" class="btn">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer Section Starts -->
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
        <div class="credit">© 2024 Medicare. All Rights Reserved.</div>
    </section>
    <!-- Footer Section Ends -->
</body>
</html>
