<?php
session_start();
require_once 'db.php'; // Ganti dengan file koneksi database Anda

// Cek apakah admin sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: admin.php");
    exit;
}

// Inisialisasi variabel
$edit_mode = false;
$edit_data = [];

// Tambah Obat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_medicine'])) {
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssi", $nama_obat, $kemasan, $harga);
        if (!$stmt->execute()) {
            die("Error saat menambahkan data obat: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error saat menyiapkan query: " . $conn->error);
    }
}

// Edit Obat (Ambil data untuk diedit)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM obat WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $edit_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $edit_data = $result->fetch_assoc();
            $edit_mode = true; // Aktifkan mode edit
        } else {
            die("Error saat mengambil data obat: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error saat menyiapkan query: " . $conn->error);
    }
}

// Update Obat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_medicine'])) {
    $id = $_POST['id'];
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];

    $query = "UPDATE obat SET nama_obat = ?, kemasan = ?, harga = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssii", $nama_obat, $kemasan, $harga, $id);
        if (!$stmt->execute()) {
            die("Error saat mengupdate data obat: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error saat menyiapkan query: " . $conn->error);
    }

    header("Location: manageMeds.php");
    exit;
}

// Hapus Obat
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $query = "DELETE FROM obat WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            die("Error saat menghapus data obat: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error saat menyiapkan query: " . $conn->error);
    }
}

// Data Obat
$query_obat = "SELECT * FROM obat";
$result_obat = $conn->query($query_obat);

if (!$result_obat) {
    die("Error saat mengambil data obat: " . $conn->error);
}
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
</head>
<body>
    <div class="content">
        <h2>Manage Medicines</h2>

        <!-- Tambah/Edit Obat -->
        <div class="form-container">
            <h3><?= $edit_mode ? "Edit Obat" : "Tambah Obat" ?></h3>
            <form method="POST">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <?php endif; ?>
                <input type="text" name="nama_obat" placeholder="Nama Obat" value="<?= $edit_data['nama_obat'] ?? '' ?>" required>
                <input type="text" name="kemasan" placeholder="Kemasan Obat" value="<?= $edit_data['kemasan'] ?? '' ?>" required>
                <input type="number" name="harga" placeholder="Harga Obat" value="<?= $edit_data['harga'] ?? '' ?>" required>
                <button type="submit" name="<?= $edit_mode ? "edit_medicine" : "add_medicine" ?>" class="btn">
                    <?= $edit_mode ? "Update Obat" : "Tambah Obat" ?>
                </button>
            </form>
        </div>

        <!-- Daftar Obat -->
        <div class="table-container">
            <h3>Daftar Obat</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Obat</th>
                        <th>Kemasan</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_obat->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nama_obat'] ?></td>
                            <td><?= $row['kemasan'] ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td>
                                <a href="manageMeds.php?edit_id=<?= $row['id'] ?>" class="btn">Edit</a>
                                <a href="manageMeds.php?delete_id=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus?')" class="btn">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
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
</body>
</html>
