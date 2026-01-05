<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Hitung jumlah item di keranjang
$keranjangCount = isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Bakpia</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <nav class="navbar">
            <a href="dashboard_user.php" class="logo">
                <i class="fas fa-cookie-bite"></i>
                <span>Bakpia Bahagia</span>
            </a>
            <ul class="nav-links">
                <li><a href="dashboard_user.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="user/menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
                <li><a href="user/keranjang.php">
                    <i class="fas fa-shopping-cart"></i> Keranjang
                    <?php if ($keranjangCount > 0): ?>
                        <span style="background: var(--accent); color: var(--dark); padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-left: 5px;">
                            <?php echo $keranjangCount; ?>
                        </span>
                    <?php endif; ?>
                </a></li>
                <li><a href="user/riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                <li><a href="logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-cookie-bite"></i> Selamat Datang</h1>
            <p>Halo, <strong><?php echo $_SESSION['username']; ?></strong>!</p>
            <p>Silahkan jelajahi menu bakpia lezat kami</p>
        </div>

        <div class="card">
            <h2 class="card-title">Akses Cepat</h2>
            
            <div class="menu-grid">
                <div class="menu-item">
                    <div class="menu-item-header">
                        <i class="fas fa-utensils fa-2x"></i>
                        <h3>Lihat Menu</h3>
                    </div>
                    <div class="menu-item-body">
                        <p>Jelajahi berbagai varian bakpia basah dan kering</p>
                        <a href="user/menu.php" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Lihat Menu
                        </a>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-item-header">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                        <h3>Keranjang Belanja</h3>
                    </div>
                    <div class="menu-item-body">
                        <p>
                            <?php if ($keranjangCount > 0): ?>
                                Anda memiliki <strong><?php echo $keranjangCount; ?></strong> item
                            <?php else: ?>
                                Keranjang belanja Anda kosong
                            <?php endif; ?>
                        </p>
                        <a href="user/keranjang.php" class="btn btn-success btn-block">
                            <i class="fas fa-shopping-basket"></i> Lihat Keranjang
                        </a>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-item-header">
                        <i class="fas fa-history fa-2x"></i>
                        <h3>Riwayat Belanja</h3>
                    </div>
                    <div class="menu-item-body">
                        <p>Lihat semua transaksi pembelian Anda</p>
                        <a href="user/riwayat.php" class="btn btn-primary btn-block">
                            <i class="fas fa-receipt"></i> Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Promo Spesial</h2>
            <div style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 25px; border-radius: 10px;">
                <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1;">
                        <h3><i class="fas fa-gift"></i> Diskon 20%!</h3>
                        <p>Untuk pembelian minimal Rp 100.000, dapatkan diskon spesial 20% untuk semua varian bakpia kering.</p>
                        <p><i class="fas fa-calendar-alt"></i> Berlaku sampai 31 Desember 2024</p>
                    </div>
                    <div>
                        <span style="font-size: 2rem; background: var(--accent); color: var(--dark); padding: 10px 20px; border-radius: 10px; font-weight: bold;">
                            20% OFF
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Tips Menyimpan Bakpia</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="text-align: center; padding: 15px; background: rgba(139, 69, 19, 0.05); border-radius: 8px;">
                    <i class="fas fa-snowflake fa-2x" style="color: var(--primary); margin-bottom: 10px;"></i>
                    <h4>Bakpia Basah</h4>
                    <p>Simpan di lemari es maksimal 7 hari</p>
                </div>
                <div style="text-align: center; padding: 15px; background: rgba(139, 69, 19, 0.05); border-radius: 8px;">
                    <i class="fas fa-box fa-2x" style="color: var(--primary); margin-bottom: 10px;"></i>
                    <h4>Bakpia Kering</h4>
                    <p>Tahan hingga 1 bulan di suhu ruang</p>
                </div>
                <div style="text-align: center; padding: 15px; background: rgba(139, 69, 19, 0.05); border-radius: 8px;">
                    <i class="fas fa-fire fa-2x" style="color: var(--primary); margin-bottom: 10px;"></i>
                    <h4>Sajian Terbaik</h4>
                    <p>Hangatkan sebentar sebelum disajikan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p>Jl. Bakpia No. 123, Yogyakarta | Telp: (0274) 123456</p>
            <div style="margin-top: 15px;">
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-instagram"></i> @bakpiabahagia</a>
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-facebook"></i> Bakpia Bahagia</a>
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-whatsapp"></i> 0812-3456-7890</a>
            </div>
        </div>
    </div>
</body>
</html>