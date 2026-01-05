<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_POST['tambah'])) {
    $nama  = htmlspecialchars($_POST['nama']);
    $harga = (int)$_POST['harga'];
    $qty   = (int)$_POST['qty'];

    if ($qty > 0) {
        if (isset($_SESSION['keranjang'][$nama])) {
            $_SESSION['keranjang'][$nama]['qty'] += $qty;
        } else {
            $_SESSION['keranjang'][$nama] = [
                'harga' => $harga,
                'qty'   => $qty
            ];
        }
    }
    
    header("Location: keranjang.php?success=tambah");
    exit;
}

if (isset($_GET['hapus'])) {
    $hapusNama = $_GET['hapus'];
    if (isset($_SESSION['keranjang'][$hapusNama])) {
        unset($_SESSION['keranjang'][$hapusNama]);
        header("Location: keranjang.php?success=hapus");
        exit;
    }
}

if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $nama => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) {
            unset($_SESSION['keranjang'][$nama]);
        } elseif (isset($_SESSION['keranjang'][$nama])) {
            $_SESSION['keranjang'][$nama]['qty'] = $qty;
        }
    }
    header("Location: keranjang.php?success=update");
    exit;
}

$keranjangCount = count($_SESSION['keranjang']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Toko Bakpia</title>
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
                <li><a href="menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
                <li><a href="keranjang.php" class="active"><i class="fas fa-shopping-cart"></i> Keranjang 
                    <?php if ($keranjangCount > 0): ?>
                        <span style="background: var(--accent); color: var(--dark); padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-left: 5px;">
                            <?php echo $keranjangCount; ?>
                        </span>
                    <?php endif; ?>
                </a></li>
                <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                <li><a href="../logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h1>
            <p>Review dan kelola pesanan Anda sebelum checkout</p>
            <p style="margin-top: 10px;">
                <span class="badge badge-primary">Halo, <?php echo $_SESSION['username']; ?>!</span>
                <a href="menu.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Item Lagi
                </a>
            </p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <?php 
                if ($_GET['success'] == 'tambah') echo "Item berhasil ditambahkan ke keranjang!";
                elseif ($_GET['success'] == 'hapus') echo "Item berhasil dihapus dari keranjang!";
                elseif ($_GET['success'] == 'update') echo "Keranjang berhasil diperbarui!";
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($_SESSION['keranjang'])): ?>
            <div class="card text-center" style="padding: 60px 20px;">
                <i class="fas fa-shopping-cart fa-4x" style="color: #ddd; margin-bottom: 20px;"></i>
                <h2 style="color: #888;">Keranjang Belanja Kosong</h2>
                <p style="color: #666; max-width: 500px; margin: 15px auto 30px;">
                    Belum ada item di keranjang belanja Anda. Silahkan pilih menu bakpia favorit Anda terlebih dahulu.
                </p>
                <a href="menu.php" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.1rem;">
                    <i class="fas fa-utensils"></i> Lihat Menu Bakpia
                </a>
            </div>
        <?php else: ?>
            <div class="card">
                <h2 class="card-title">Daftar Pesanan</h2>
                <p>Total: <strong><?php echo $keranjangCount; ?></strong> item dalam keranjang</p>
                
                <form method="post" action="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bakpia</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $no = 1;
                                foreach ($_SESSION['keranjang'] as $nama => $item):
                                    $subtotal = $item['harga'] * $item['qty'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <i class="fas fa-cookie-bite" style="color: white;"></i>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($nama); ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-weight: bold; color: var(--primary);">
                                        Rp <?php echo number_format($item['harga']); ?>
                                    </td>
                                    <td>
                                        <input type="number" name="qty[<?php echo htmlspecialchars($nama); ?>]" 
                                               value="<?php echo $item['qty']; ?>" 
                                               min="1" max="100" 
                                               class="form-control" 
                                               style="width: 80px; text-align: center; display: inline-block;">
                                    </td>
                                    <td style="font-weight: bold; color: var(--success);">
                                        Rp <?php echo number_format($subtotal); ?>
                                    </td>
                                    <td>
                                        <a href="?hapus=<?php echo urlencode($nama); ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus <?php echo htmlspecialchars($nama); ?> dari keranjang?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                $no++;
                                endforeach; 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr style="background: rgba(139, 69, 19, 0.05);">
                                    <td colspan="4" style="text-align: right; font-weight: bold;">
                                        <i class="fas fa-money-bill-wave"></i> Total Belanja
                                    </td>
                                    <td colspan="2" style="font-weight: bold; font-size: 1.2em; color: var(--success);">
                                        Rp <?php echo number_format($total); ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 30px; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <button type="submit" name="update" class="btn" style="background: var(--dark); color: white;">
                                <i class="fas fa-sync-alt"></i> Update Keranjang
                            </button>
                            <a href="menu.php" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Tambah Item Lagi
                            </a>
                        </div>
                        
                        <div>
                            <a href="checkout.php" class="btn btn-success" style="padding: 12px 40px; font-size: 1.1rem;">
                                <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Checkout</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                    <div style="background: rgba(46, 125, 50, 0.1); padding: 15px; border-radius: 8px; border-left: 4px solid var(--success);">
                        <h4 style="color: var(--success); margin-bottom: 10px;"><i class="fas fa-shipping-fast"></i> Pengiriman</h4>
                        <p>Pesanan akan diproses dalam 24 jam setelah pembayaran</p>
                        <p style="font-size: 0.9rem; color: #666;">Gratis ongkir untuk pembelian > Rp 200.000</p>
                    </div>
                    
                    <div style="background: rgba(255, 215, 0, 0.1); padding: 15px; border-radius: 8px; border-left: 4px solid var(--accent);">
                        <h4 style="color: var(--accent); margin-bottom: 10px;"><i class="fas fa-gift"></i> Promo Spesial</h4>
                        <p>Dapatkan diskon 20% untuk pembelian minimal Rp 100.000</p>
                        <p style="font-size: 0.9rem; color: #666;">Kode: <strong>BAKPIA20</strong></p>
                    </div>
                    
                    <div style="background: rgba(139, 69, 19, 0.1); padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary);">
                        <h4 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-clock"></i> Jam Operasional</h4>
                        <p>Pemesanan online buka 24 jam</p>
                        <p style="font-size: 0.9rem; color: #666;">Pengiriman Senin - Sabtu</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p><i class="fas fa-user"></i> Login sebagai: <?php echo $_SESSION['username']; ?></p>
        </div>
    </div>

    <script>
        // Animasi untuk tombol
        document.addEventListener('DOMContentLoaded', function() {
            const updateBtn = document.querySelector('button[name="update"]');
            if (updateBtn) {
                updateBtn.addEventListener('click', function() {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
                });
            }
        });
    </script>
</body>
</html>