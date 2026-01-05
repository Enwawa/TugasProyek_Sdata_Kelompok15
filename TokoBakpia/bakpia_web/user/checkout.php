<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

if (empty($_SESSION['keranjang'])) {
    header("Location: keranjang.php");
    exit;
}

// Simpan transaksi ke file
$file = __DIR__ . "/../../data/transaksi.txt";
$username = $_SESSION['username'];
$tanggal = date("Y-m-d H:i:s");

// Hitung total sebelum disimpan
$totalTransaksi = 0;
foreach ($_SESSION['keranjang'] as $nama => $item) {
    $subtotal = $item['harga'] * $item['qty'];
    $totalTransaksi += $subtotal;
}

// Simpan setiap item ke file transaksi
foreach ($_SESSION['keranjang'] as $nama => $item) {
    $harga = $item['harga'];
    $qty = $item['qty'];
    $subtotal = $harga * $qty;

    $row = "$username;$tanggal;" . htmlspecialchars($nama) . ";$harga;$qty;$subtotal\n";
    file_put_contents($file, $row, FILE_APPEND);
}

// Simpan detail transaksi untuk ditampilkan
$detailTransaksi = $_SESSION['keranjang'];
$nomorTransaksi = 'TRX' . date('YmdHis') . rand(100, 999);

// Kosongkan keranjang setelah checkout
unset($_SESSION['keranjang']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Berhasil - Toko Bakpia</title>
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
                <li><a href="keranjang.php"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
                <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                <li><a href="../logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero text-center" style="background: linear-gradient(135deg, rgba(46, 125, 50, 0.9), rgba(56, 142, 60, 0.9));">
            <h1><i class="fas fa-check-circle"></i> Checkout Berhasil!</h1>
            <p>Terima kasih telah berbelanja di Toko Bakpia Bahagia</p>
        </div>

        <div class="card text-center" style="border: 2px solid var(--success);">
            <div style="width: 100px; height: 100px; background: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-check fa-3x" style="color: white;"></i>
            </div>
            
            <h2 style="color: var(--success);">Pesanan Anda Telah Diterima</h2>
            <p style="font-size: 1.1rem; max-width: 600px; margin: 15px auto 30px;">
                Pesanan Anda akan segera diproses dan dikirim ke alamat yang terdaftar.
            </p>
            
            <div style="background: rgba(46, 125, 50, 0.1); padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid var(--success);">
                <h3 style="color: var(--success); margin-bottom: 10px;">Nomor Transaksi</h3>
                <h1 style="color: var(--dark); letter-spacing: 2px;"><?php echo $nomorTransaksi; ?></h1>
                <p style="color: #666;">Simpan nomor ini untuk tracking pesanan</p>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Detail Transaksi</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: rgba(139, 69, 19, 0.05); padding: 15px; border-radius: 8px;">
                    <h4 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-user"></i> Informasi Pelanggan</h4>
                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p><strong>Tanggal:</strong> <?php echo date('d F Y, H:i:s'); ?></p>
                    <p><strong>Status:</strong> <span style="color: var(--success); font-weight: bold;">Menunggu Diproses</span></p>
                </div>
                
                <div style="background: rgba(139, 69, 19, 0.05); padding: 15px; border-radius: 8px;">
                    <h4 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Informasi Pengiriman</h4>
                    <p><strong>Estimasi:</strong> 1-3 hari kerja</p>
                    <p><strong>Metode:</strong> Kurir Same Day/JNE/GoSend</p>
                    <p><strong>Biaya:</strong> Disesuaikan dengan lokasi</p>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bakpia</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $grandTotal = 0;
                        foreach ($detailTransaksi as $nama => $item):
                            $subtotal = $item['harga'] * $item['qty'];
                            $grandTotal += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                        <i class="fas fa-cookie-bite" style="color: white; font-size: 0.8rem;"></i>
                                    </div>
                                    <?php echo htmlspecialchars($nama); ?>
                                </div>
                            </td>
                            <td style="font-weight: bold;">Rp <?php echo number_format($item['harga']); ?></td>
                            <td><?php echo $item['qty']; ?></td>
                            <td style="font-weight: bold; color: var(--success);">Rp <?php echo number_format($subtotal); ?></td>
                        </tr>
                        <?php 
                        $no++;
                        endforeach; 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: linear-gradient(to right, var(--primary), var(--secondary)); color: white;">
                            <td colspan="4" style="text-align: right; font-weight: bold;">
                                <i class="fas fa-money-bill-wave"></i> Total Pembayaran
                            </td>
                            <td style="font-weight: bold; font-size: 1.2em;">
                                Rp <?php echo number_format($grandTotal); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background: rgba(255, 215, 0, 0.1); border-radius: 10px; border-left: 4px solid var(--accent);">
                <h4 style="color: var(--accent); margin-bottom: 10px;"><i class="fas fa-lightbulb"></i> Tips Penyimpanan Bakpia</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <p style="margin: 5px 0;"><i class="fas fa-check-circle" style="color: var(--success);"></i> Simpan di tempat sejuk</p>
                        <p style="margin: 5px 0;"><i class="fas fa-check-circle" style="color: var(--success);"></i> Jauhkan dari sinar matahari</p>
                    </div>
                    <div>
                        <p style="margin: 5px 0;"><i class="fas fa-check-circle" style="color: var(--success);"></i> Bakpia basah tahan 7 hari di kulkas</p>
                        <p style="margin: 5px 0;"><i class="fas fa-check-circle" style="color: var(--success);"></i> Bakpia kering tahan 1 bulan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="card-title"><i class="fas fa-share-alt"></i> Langkah Selanjutnya</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                <div style="text-align: center; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid rgba(139, 69, 19, 0.1);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fas fa-receipt" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                    <h4>1. Invoice</h4>
                    <p style="font-size: 0.9rem; color: #666;">Invoice akan dikirim ke email Anda</p>
                </div>
                
                <div style="text-align: center; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid rgba(139, 69, 19, 0.1);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fas fa-box" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                    <h4>2. Proses</h4>
                    <p style="font-size: 0.9rem; color: #666;">Pesanan akan diproses dalam 24 jam</p>
                </div>
                
                <div style="text-align: center; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid rgba(139, 69, 19, 0.1);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fas fa-shipping-fast" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                    <h4>3. Pengiriman</h4>
                    <p style="font-size: 0.9rem; color: #666;">Anda akan mendapat notifikasi pengiriman</p>
                </div>
                
                <div style="text-align: center; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid rgba(139, 69, 19, 0.1);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fas fa-star" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                    <h4>4. Review</h4>
                    <p style="font-size: 0.9rem; color: #666;">Berikan review setelah menerima pesanan</p>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 40px; padding-top: 30px; border-top: 1px solid rgba(139, 69, 19, 0.1);">
                <a href="menu.php" class="btn btn-primary" style="padding: 12px 40px; margin: 0 10px;">
                    <i class="fas fa-shopping-cart"></i> Belanja Lagi
                </a>
                <a href="riwayat.php" class="btn" style="padding: 12px 40px; margin: 0 10px; background: rgba(139, 69, 19, 0.1); color: var(--primary);">
                    <i class="fas fa-history"></i> Lihat Riwayat
                </a>
                <a href="../dashboard_user.php" class="btn" style="padding: 12px 40px; margin: 0 10px; background: var(--dark); color: white;">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?> | <i class="fas fa-calendar"></i> <?php echo date('d F Y'); ?></p>
        </div>
    </div>
</body>
</html>