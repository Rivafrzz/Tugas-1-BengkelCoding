<?php 
session_start();
require_once 'db.php'; // Ganti dengan file koneksi database Anda

// Cek apakah admin sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: admin.php");
    exit;
}

// Tambah Pasien
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_patient'])) {
    $nama_pasien = $_POST['nama_pasien'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $no_em = $_POST['no_em'];

    $query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_em) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssiii", $nama_pasien, $alamat, $no_ktp, $no_hp, $no_em);

    if (!$stmt->execute()) {
        die("Error saat menambahkan data: " . $stmt->error);
    }
    $stmt->close();
}

// Edit Pasien
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_patient'])) {
    $id = $_POST['id'];
    $nama_pasien = $_POST['nama_pasien'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $no_em = $_POST['no_em'];

    $query = "UPDATE pasien SET nama = ?, alamat = ?, no_ktp = ?, no_hp = ?, no_em = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssiiii", $nama_pasien, $alamat, $no_ktp, $no_hp, $no_em, $id);

    if (!$stmt->execute()) {
        die("Error saat mengedit data: " . $stmt->error);
    }
    $stmt->close();
}

// Hapus Pasien
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $query = "DELETE FROM pasien WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Error saat menghapus data: " . $stmt->error);
    }
    $stmt->close();
}

// Data Pasien
$query_pasien = "SELECT * FROM pasien";
$result_pasien = $conn->query($query_pasien);

if (!$result_pasien) {
    die("Error saat mengambil data pasien: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare | Manage Patients</title>
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
            <h2>Manage Patients</h2>

            <!-- Tambah Pasien -->
            <div class="form-container">
                <h3>Tambah Pasien</h3>
                <form method="POST">
                    <input type="text" name="nama_pasien" placeholder="Nama Pasien" required>
                    <input type="text" name="alamat" placeholder="Alamat Pasien" required>
                    <input type="number" name="no_ktp" placeholder="Nomor KTP" required>
                    <input type="number" name="no_hp" placeholder="Nomor HP" required>
                    <input type="text" name="no_em" placeholder="Nomor EM" required>
                    <button type="submit" name="add_patient" class="btn">Tambah Pasien</button>
                </form>
            </div>

            <!-- Daftar Pasien -->
            <div class="table-container">
                <h3>Daftar Pasien</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pasien</th>
                            <th>Alamat</th>
                            <th>Nomor KTP</th>
                            <th>Nomor HP</th>
                            <th>Nomor EM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_pasien->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['alamat'] ?></td>
                                <td><?= $row['no_ktp'] ?></td>
                                <td><?= $row['no_hp'] ?></td>
                                <td><?= $row['no_em'] ?></td>
                                <td>
                                    <button class="btn edit-btn" onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama']) ?>', '<?= htmlspecialchars($row['alamat']) ?>', <?= $row['no_ktp'] ?>, <?= $row['no_hp'] ?>, '<?= htmlspecialchars($row['no_em']) ?>')">Edit</button>
                                    <a href="managePatients.php?delete_id=<?= $row['id'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus?')" class="btn">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal untuk Edit Pasien -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Edit Pasien</h3>
            <form method="POST">
                <input type="hidden" name="id" id="editId">
                <input type="text" name="nama_pasien" id="editNama" placeholder="Nama Pasien" required>
                <input type="text" name="alamat" id="editAlamat" placeholder="Alamat Pasien" required>
                <input type="number" name="no_ktp" id="editNoKTP" placeholder="Nomor KTP" required>
                <input type="number" name="no_hp" id="editNoHP" placeholder="Nomor HP" required>
                <input type="text" name="no_em" id="editNoEM" placeholder="Nomor EM" required>
                <button type="submit" name="edit_patient" class="btn">Update Pasien</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, alamat, noKTP, noHP, noEM) {
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editAlamat').value = alamat;
            document.getElementById('editNoKTP').value = noKTP;
            document.getElementById('editNoHP').value = noHP;
            document.getElementById('editNoEM').value = noEM;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('editModal')) {
                closeEditModal();
            }
        }
    </script>

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
