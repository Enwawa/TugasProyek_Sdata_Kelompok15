<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Toko Bakpia</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <nav class="navbar">
            <a href="dashboard_admin.php" class="logo">
                <i class="fas fa-cookie-bite"></i>
                <span>Bakpia Bahagia</span>
            </a>
            <ul class="nav-links">
                <li><a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="admin/menu_manage.php"><i class="fas fa-utensils"></i> Kelola Menu</a></li>
                <li><a href="admin/transaksi_all.php"><i class="fas fa-chart-bar"></i> Transaksi</a></li>
                <li><a href="logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-user-shield"></i> Dashboard Admin</h1>
            <p>Selamat datang, <strong><?php echo $_SESSION['username']; ?></strong></p>
        </div>

        <div class="card">
            <h2 class="card-title">Quick Actions</h2>
            
            <div class="menu-grid">
                <div class="menu-item">
                    <div class="menu-item-header">
                        <i class="fas fa-utensils fa-2x"></i>
                        <h3>Kelola Menu</h3>
                    </div>
                    <div class="menu-item-body">
                        <p>Kelola menu bakpia basah dan kering</p>
                        <a href="admin/menu_manage.php" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Kelola
                        </a>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-item-header">
                        <i class="fas fa-chart-bar fa-2x"></i>
                        <h3>Transaksi</h3>
                    </div>
                    <div class="menu-item-body">
                        <p>Lihat semua transaksi pelanggan</p>
                        <a href="admin/transaksi_all.php" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-item-header">
                        <i class="fas fa-users fa-2x"></i>
                        <h3>Data User</h3>
                    </div>
                    <div class="menu-item-body">
                        <p>Kelola data admin dan pelanggan</p>
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-cog"></i> Kelola
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Statistik Cepat</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <?php
                // Hitung total menu
                $menuFile = __DIR__ . "/data/menu_bakpia.txt";
                $menuCount = 0;
                if (file_exists($menuFile)) {
                    $menuData = file($menuFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $menuCount = count($menuData);
                }

                // Hitung total transaksi
                $transaksiFile = __DIR__ . "/data/transaksi.txt";
                $totalTransaksi = 0;
                $totalPendapatan = 0;
                if (file_exists($transaksiFile)) {
                    $transaksiData = file($transaksiFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $totalTransaksi = count($transaksiData);
                    foreach ($transaksiData as $row) {
                        $d = explode(";", $row);
                        if (count($d) >= 6) {
                            $totalPendapatan += $d[5];
                        }
                    }
                }
                ?>

                <div class="text-center p-20" style="background: rgba(139, 69, 19, 0.1); border-radius: 10px;">
                    <i class="fas fa-utensils fa-3x" style="color: var(--primary);"></i>
                    <h3 style="margin: 10px 0;"><?php echo $menuCount; ?></h3>
                    <p>Menu Bakpia</p>
                </div>

                <div class="text-center p-20" style="background: rgba(46, 125, 50, 0.1); border-radius: 10px;">
                    <i class="fas fa-shopping-cart fa-3x" style="color: var(--success);"></i>
                    <h3 style="margin: 10px 0;"><?php echo $totalTransaksi; ?></h3>
                    <p>Total Transaksi</p>
                </div>

                <div class="text-center p-20" style="background: rgba(255, 215, 0, 0.1); border-radius: 10px;">
                    <i class="fas fa-money-bill-wave fa-3x" style="color: var(--accent);"></i>
                    <h3 style="margin: 10px 0;">Rp <?php echo number_format($totalPendapatan); ?></h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia Admin Panel</p>
            <p>Login sebagai: <?php echo $_SESSION['username']; ?> | Role: <?php echo $_SESSION['role']; ?></p>
        </div>
    </div>
</body>
</html>