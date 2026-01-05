<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$menuFile = __DIR__ . "/../../data/menu_bakpia.txt";
$menus = file_exists($menuFile) ? file($menuFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Bakpia - Toko Bakpia</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <nav class="navbar">
            <a href="../dashboard_user.php" class="logo">
                <i class="fas fa-cookie-bite"></i>
                <span>Bakpia Bahagia</span>
            </a>
            <ul class="nav-links">
                <li><a href="../dashboard_user.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="menu.php" class="active"><i class="fas fa-utensils"></i> Menu</a></li>
                <li><a href="keranjang.php"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
                <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                <li><a href="../logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-cookie-bite"></i> Menu Bakpia Spesial</h1>
            <p>Pilih bakpia favorit Anda dari berbagai varian lezat</p>
            <p style="margin-top: 10px;">
                <span class="badge badge-primary">Halo, <?php echo $_SESSION['username']; ?>!</span>
                <a href="keranjang.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-shopping-cart"></i> Lihat Keranjang
                </a>
            </p>
        </div>

        <div class="card mb-30">
            <h2 class="card-title">Filter Menu</h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <button class="btn btn-primary" onclick="filterMenu('all')">
                    <i class="fas fa-th-large"></i> Semua
                </button>
                <button class="btn" onclick="filterMenu('Basah')" style="background: rgba(139, 69, 19, 0.1); color: var(--primary);">
                    <i class="fas fa-soft-drink"></i> Basah
                </button>
                <button class="btn" onclick="filterMenu('Kering')" style="background: rgba(139, 69, 19, 0.1); color: var(--primary);">
                    <i class="fas fa-cookie"></i> Kering
                </button>
            </div>
        </div>

        <div class="menu-grid">
            <?php
            $no = 1;
            foreach ($menus as $row) {
                $data = explode(";", $row);
                if (count($data) < 3) continue;

                $jenis = $data[0];
                $nama  = $data[1];
                $harga = $data[2];
                
                $jenisClass = ($jenis == 'Basah') ? 'badge-success' : 'badge-primary';
            ?>
            <div class="menu-item fade-in" data-jenis="<?php echo $jenis; ?>">
                <div class="menu-item-header">
                    <h3><?php echo $nama; ?></h3>
                    <span class="badge <?php echo $jenisClass; ?>"><?php echo $jenis; ?></span>
                </div>
                <div class="menu-item-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div>
                            <p class="menu-item-price">Rp <?php echo number_format($harga); ?></p>
                        </div>
                        <div>
                            <i class="fas fa-tag" style="color: var(--accent);"></i>
                        </div>
                    </div>
                    
                    <form method="post" action="keranjang.php" class="add-to-cart-form">
                        <input type="hidden" name="nama" value="<?php echo htmlspecialchars($nama); ?>">
                        <input type="hidden" name="harga" value="<?php echo $harga; ?>">
                        
                        <div class="form-group">
                            <label for="qty_<?php echo $no; ?>">Jumlah:</label>
                            <input type="number" id="qty_<?php echo $no; ?>" name="qty" 
                                   value="1" min="1" max="50" class="form-control" 
                                   style="text-align: center; font-weight: bold;">
                        </div>
                        
                        <button type="submit" name="tambah" class="btn btn-success btn-block">
                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
            <?php 
                $no++;
            } 
            
            if ($no == 1): ?>
                <div class="text-center" style="grid-column: 1 / -1;">
                    <div class="alert" style="background: rgba(139, 69, 19, 0.1); padding: 30px;">
                        <i class="fas fa-cookie-bite fa-3x" style="color: var(--primary); margin-bottom: 15px;"></i>
                        <h3>Menu Belum Tersedia</h3>
                        <p>Silahkan hubungi admin untuk informasi lebih lanjut</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p>Jl. Bakpia No. 123, Yogyakarta | Telp: (0274) 123456</p>
        </div>
    </div>

    <script>
        function filterMenu(jenis) {
            const items = document.querySelectorAll('.menu-item');
            const buttons = document.querySelectorAll('.btn');
            
            // Update button styles
            buttons.forEach(btn => {
                if (btn.textContent.includes(jenis) || (jenis === 'all' && btn.textContent.includes('Semua'))) {
                    btn.style.background = 'linear-gradient(to right, var(--primary), var(--secondary))';
                    btn.style.color = 'white';
                } else {
                    btn.style.background = 'rgba(139, 69, 19, 0.1)';
                    btn.style.color = 'var(--primary)';
                }
            });
            
            // Filter items
            items.forEach(item => {
                if (jenis === 'all' || item.getAttribute('data-jenis') === jenis) {
                    item.style.display = 'block';
                    item.classList.add('fade-in');
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        // Tambahkan animasi loading
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.add-to-cart-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[name="tambah"]');
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
                    button.disabled = true;
                });
            });
        });
    </script>
</body>
</html>