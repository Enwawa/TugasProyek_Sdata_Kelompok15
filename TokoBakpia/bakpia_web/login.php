<?php
require "auth.php";
$error = "";

if (isset($_POST['login'])) {
    if (login($_POST['username'], $_POST['password'], $_POST['role'])) {
        header(
            "Location: " .
            ($_SESSION['role'] === 'admin'
                ? "dashboard_admin.php"
                : "dashboard_user.php")
        );
        exit;
    } else {
        $error = "Username / Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Bakpia Bahagia</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <nav class="navbar">
            <a href="index.php" class="logo">
                <i class="fas fa-cookie-bite"></i>
                <span>Bakpia Bahagia</span>
            </a>
        </nav>
    </div>

    <div class="container">
        <div class="hero text-center">
            <h1><i class="fas fa-cookie-bite"></i> Selamat Datang</h1>
            <p>Login untuk memulai pemesanan bakpia favorit Anda</p>
        </div>

        <div class="row" style="display: flex; justify-content: center;">
            <div class="card" style="max-width: 500px; width: 100%;">
                <h2 class="card-title">Login</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>

                    <div class="form-group">
                        <label for="role"><i class="fas fa-user-tag"></i> Login Sebagai</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="user">Pelanggan</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>
                </form>

                <div class="text-center mt-20">
                    <p>Belum punya akun? Hubungi admin untuk pendaftaran</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p>Jl. Bakpia No. 123, Yogyakarta | Telp: (0274) 123456</p>
            <div style="margin-top: 15px;">
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</body>
</html>