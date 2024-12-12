<?php
session_start();
include 'db.php'; // File untuk koneksi database

// Cek jika admin sudah login
if (!isset($_SESSION['loggedin'])) {
    header("Location: admin.php");
    exit;
}

// Menangani permintaan tambah dokter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nama_dokter = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_poli = $_POST['id_poli'];

    // Persiapkan dan bind
    $stmt = $conn->prepare("INSERT INTO dokter (nama, alamat, no_hp, id_poli) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $nama_dokter, $alamat, $no_hp, $id_poli);

    // Eksekusi
    if ($stmt->execute()) {
        header("Location: manageDoctors.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Menangani permintaan edit dokter
$dokter = null;
if (isset($_GET['edit'])) {
    $id_dokter = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM dokter WHERE id = ?");
    $stmt->bind_param("i", $id_dokter);
    $stmt->execute();
    $result = $stmt->get_result();
    $dokter = $result->fetch_assoc();
}

// Menangani pembaruan dokter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_dokter = $_POST['id'];
    $nama_dokter = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_poli = $_POST['id_poli'];

    $stmt = $conn->prepare("UPDATE dokter SET nama = ?, alamat = ?, no_hp = ?, id_poli = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $nama_dokter, $alamat, $no_hp, $id_poli, $id_dokter);

    if ($stmt->execute()) {
        header("Location: manageDoctors.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Menangani penghapusan dokter
if (isset($_GET['delete'])) {
    $id_dokter = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM dokter WHERE id = ?");
    $stmt->bind_param("i", $id_dokter);

    if ($stmt->execute()) {
        header("Location: manageDoctors.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Ambil semua poli untuk dropdown
$query_poli = "SELECT * FROM poli";
$result_poli = $conn->query($query_poli);
?>
    <link rel="shortcut icon" href="image/heartbeat-solid.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="./css/style.css">
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare | Manage Medicines</title>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
    
    <link rel="stylesheet" href="./css/style.css">
    
</head>
<body>
    <div class="container">
    
        <h2><?php echo isset($dokter) ? "Edit Doctor" : "Add Doctor"; ?></h2>
        >
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo isset($dokter) ? $dokter['id'] : ''; ?>">
            <input type="text" name="nama" placeholder="Nama Dokter" required value="<?php echo isset($dokter) ? htmlspecialchars($dokter['nama']) : ''; ?>">
            <input type="text" name="alamat" placeholder="Alamat" required value="<?php echo isset($dokter) ? htmlspecialchars($dokter['alamat']) : ''; ?>">
            <input type="number" name="no_hp" placeholder="Nomor HP" required value="<?php echo isset($dokter) ? htmlspecialchars($dokter['no_hp']) : ''; ?>">
            <select name="id_poli" required>
                <option value="">Select Poli</option>
                <?php while ($poli = $result_poli->fetch_assoc()): ?>
                    <option value="<?php echo $poli['id']; ?>" <?php echo isset($dokter) && $dokter['id_poli'] == $poli['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($poli['nama_poli']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="<?php echo isset($dokter) ? 'update' : 'add'; ?>">
                <?php echo isset($dokter) ? 'Update Doctor' : 'Add Doctor'; ?>
            </button>
        </form>

        <h3>List of Doctors</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Poli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT dokter.*, poli.nama_poli FROM dokter JOIN poli ON dokter.id_poli = poli.id";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_poli']); ?></td>
                        <td>
                            <a href="manageDoctors.php?edit=<?php echo $row['id']; ?>">Edit</a>
                            <a href="manageDoctors.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this doctor?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
