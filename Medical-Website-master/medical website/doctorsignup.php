<?php 
session_start();
require 'db.php'; // Koneksi ke database

// Variabel untuk menyimpan input
$username = "";
$password = "";
$errors = [];

// Menangani form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $errors[] = "Username dan password harus diisi.";
    }

    // Cek kredensial di database
    $stmt = $pdo->prepare("SELECT * FROM dokter WHERE nama = ? AND alamat = ?");
    $stmt->execute([$username, $password]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($doctor) {
        // Set session untuk dokter
        $_SESSION['id_dokter'] = $doctor['id'];
        $_SESSION['nama_dokter'] = $doctor['nama'];
        echo "<script>alert('Login berhasil!'); window.location.href = 'doctorHomepage.php';</script>";
    } else {
        $errors[] = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Doctor</title>
    <link rel="stylesheet" href="./css/stylee.css">
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form class="sign-in-form" action="" method="POST">
                    <h2 class="title">Doctor Sign In</h2>
                    <?php if (!empty($errors)): ?>
                        <div style="color: red;">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Doctor Name" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Address (Password)" required>
                    </div>
                    <input type="submit" value="Login" class="btn solid">
                </form>
            </div>
        </div>
    </div>
    <script src="./js/app.js"></script>
</body>
</html>
