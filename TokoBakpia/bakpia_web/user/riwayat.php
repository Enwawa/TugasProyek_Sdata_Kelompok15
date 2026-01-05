<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$file = __DIR__ . "/../../data/transaksi.txt";
$username = $_SESSION['username'];

$data = file_exists($file)
    ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];

// Filter transaksi berdasarkan username
$transaksiUser = [];
foreach ($data as $row) {
    $d = explode(";", $row);
    if (count($d) >= 6 && $d[0] === $username) {
        $transaksiUser[] = $d;
    }
}

// Hitung statistik
$totalTransaksi = count($transaksiUser);
$totalPengeluaran = 0;
$totalItem = 0;
foreach ($transaksiUser as $transaksi) {
    $totalPengeluaran += $transaksi[5];
    $totalItem += $transaksi[4];
}

// Urutkan dari yang terbaru
$transaksiUser = array_reverse($transaksiUser);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Toko Bakpia</title>
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
                <li><a href="riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat</a></li>
                <li><a href="../logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-history"></i> Riwayat Transaksi</h1>
            <p>Lihat semua riwayat pembelian Anda di Toko Bakpia Bahagia</p>
            <p style="margin-top: 10px;">
                <span class="badge badge-primary">Halo, <?php echo htmlspecialchars($username); ?>!</span>
                <a href="menu.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-shopping-cart"></i> Belanja Lagi
                </a>
            </p>
        </div>

        <div class="card mb-30">
            <h2 class="card-title">Statistik Belanja Anda</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, rgba(139, 69, 19, 0.1), rgba(210, 105, 30, 0.1)); border-radius: 10px; border: 2px solid rgba(139, 69, 19, 0.2);">
                    <i class="fas fa-shopping-cart fa-2x" style="color: var(--primary); margin-bottom: 10px;"></i>
                    <h3 style="margin: 10px 0; color: var(--primary);"><?php echo $totalTransaksi; ?></h3>
                    <p>Total Transaksi</p>
                </div>
                
                <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, rgba(46, 125, 50, 0.1), rgba(56, 142, 60, 0.1)); border-radius: 10px; border: 2px solid rgba(46, 125, 50, 0.2);">
                    <i class="fas fa-box fa-2x" style="color: var(--success); margin-bottom: 10px;"></i>
                    <h3 style="margin: 10px 0; color: var(--success);"><?php echo $totalItem; ?></h3>
                    <p>Total Item Dibeli</p>
                </div>
                
                <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 221, 51, 0.1)); border-radius: 10px; border: 2px solid rgba(255, 215, 0, 0.2);">
                    <i class="fas fa-money-bill-wave fa-2x" style="color: var(--accent); margin-bottom: 10px;"></i>
                    <h3 style="margin: 10px 0; color: var(--accent);">Rp <?php echo number_format($totalPengeluaran); ?></h3>
                    <p>Total Pengeluaran</p>
                </div>
                
                <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(211, 47, 47, 0.1)); border-radius: 10px; border: 2px solid rgba(198, 40, 40, 0.2);">
                    <i class="fas fa-calendar-alt fa-2x" style="color: var(--danger); margin-bottom: 10px;"></i>
                    <h3 style="margin: 10px 0; color: var(--danger);"><?php echo date('d M Y'); ?></h3>
                    <p>Tanggal Hari Ini</p>
                </div>
            </div>
        </div>

        <?php if (empty($transaksiUser)): ?>
            <div class="card text-center" style="padding: 60px 20px;">
                <i class="fas fa-history fa-4x" style="color: #ddd; margin-bottom: 20px;"></i>
                <h2 style="color: #888;">Belum Ada Riwayat Transaksi</h2>
                <p style="color: #666; max-width: 500px; margin: 15px auto 30px;">
                    Anda belum melakukan transaksi pembelian di Toko Bakpia Bahagia. 
                    Yuk, mulai belanja bakpia favorit Anda sekarang!
                </p>
                <a href="menu.php" class="btn btn-primary" style="padding: 12px 40px; font-size: 1.1rem;">
                    <i class="fas fa-utensils"></i> Lihat Menu Bakpia
                </a>
            </div>
        <?php else: ?>
            <div class="card">
                <h2 class="card-title">Detail Transaksi</h2>
                <p>Total: <strong><?php echo $totalTransaksi; ?></strong> transaksi ditemukan</p>
                
                <div style="margin-bottom: 20px;">
                    <input type="text" id="searchRiwayat" class="form-control" 
                           placeholder="Cari transaksi berdasarkan tanggal atau nama menu..." 
                           style="max-width: 400px;">
                </div>
                
                <div class="table-container">
                    <table id="riwayatTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Transaksi</th>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            foreach ($transaksiUser as $index => $transaksi):
                                $grandTotal += $transaksi[5];
                            ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-calendar-day" style="color: white;"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo date('d M Y', strtotime(explode(' ', $transaksi[1])[0])); ?></strong>
                                            <div style="font-size: 0.8rem; color: #666;">
                                                <?php echo explode(' ', $transaksi[1])[1]; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 30px; height: 30px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-cookie-bite" style="color: white; font-size: 0.8rem;"></i>
                                        </div>
                                        <?php echo htmlspecialchars($transaksi[2]); ?>
                                    </div>
                                </td>
                                <td style="font-weight: bold; color: var(--primary);">
                                    Rp <?php echo number_format($transaksi[3]); ?>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(139, 69, 19, 0.1); color: var(--primary); padding: 5px 10px;">
                                        <?php echo $transaksi[4]; ?>
                                    </span>
                                </td>
                                <td style="font-weight: bold; color: var(--success);">
                                    Rp <?php echo number_format($transaksi[5]); ?>
                                </td>
                                <td>
                                    <button onclick="detailTransaksi(<?php echo $index; ?>)" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background: linear-gradient(to right, var(--primary), var(--secondary)); color: white;">
                                <td colspan="5" style="text-align: right; font-weight: bold;">
                                    <i class="fas fa-money-check-alt"></i> Total Pengeluaran
                                </td>
                                <td colspan="2" style="font-weight: bold; font-size: 1.2em;">
                                    Rp <?php echo number_format($grandTotal); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div style="margin-top: 30px; padding: 20px; background: rgba(139, 69, 19, 0.05); border-radius: 10px;">
                    <h4 style="color: var(--primary); margin-bottom: 15px;"><i class="fas fa-chart-line"></i> Analisis Belanja</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <p><i class="fas fa-arrow-up" style="color: var(--success);"></i> <strong>Rata-rata Transaksi:</strong> 
                                Rp <?php echo $totalTransaksi > 0 ? number_format($grandTotal / $totalTransaksi) : 0; ?>
                            </p>
                        </div>
                        <div>
                            <p><i class="fas fa-box" style="color: var(--primary);"></i> <strong>Rata-rata Item/Transaksi:</strong> 
                                <?php echo $totalTransaksi > 0 ? number_format($totalItem / $totalTransaksi, 1) : 0; ?> item
                            </p>
                        </div>
                        <div>
                            <p><i class="fas fa-calendar" style="color: var(--accent);"></i> <strong>Transaksi Terbaru:</strong> 
                                <?php echo !empty($transaksiUser) ? date('d M', strtotime($transaksiUser[0][1])) : '-'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h3 class="card-title"><i class="fas fa-download"></i> Export Data</h3>
                <p>Unduh riwayat transaksi Anda untuk keperluan pencatatan:</p>
                <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px;">
                    <button onclick="exportToExcel()" class="btn btn-primary">
                        <i class="fas fa-file-excel"></i> Export ke Excel
                    </button>
                    <button onclick="window.print()" class="btn">
                        <i class="fas fa-print"></i> Cetak Riwayat
                    </button>
                    <button onclick="shareRiwayat()" class="btn" style="background: rgba(139, 69, 19, 0.1); color: var(--primary);">
                        <i class="fas fa-share-alt"></i> Bagikan Ringkasan
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?> | 
               <i class="fas fa-shopping-cart"></i> <?php echo $totalTransaksi; ?> transaksi | 
               <i class="fas fa-money-bill-wave"></i> Rp <?php echo number_format($grandTotal ?? 0); ?></p>
        </div>
    </div>

    <!-- Modal untuk Detail Transaksi -->
    <div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; border-radius: 10px; padding: 30px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; position: relative;">
            <button onclick="closeModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; color: var(--primary); cursor: pointer;">×</button>
            
            <h3 id="modalTitle" style="color: var(--primary); margin-bottom: 20px;"></h3>
            <div id="modalContent"></div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button onclick="closeModal()" class="btn" style="background: var(--primary); color: white;">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Fitur pencarian
        document.getElementById('searchRiwayat').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#riwayatTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        // Fungsi untuk menampilkan detail transaksi
        function detailTransaksi(index) {
            const transaksi = <?php echo json_encode($transaksiUser); ?>[index];
            if (transaksi) {
                const modal = document.getElementById('detailModal');
                const title = document.getElementById('modalTitle');
                const content = document.getElementById('modalContent');
                
                title.textContent = 'Detail Transaksi - ' + transaksi[1];
                
                let html = `
                    <div style="background: rgba(139, 69, 19, 0.05); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p><strong><i class="fas fa-user"></i> Username:</strong> ${transaksi[0]}</p>
                        <p><strong><i class="fas fa-calendar"></i> Tanggal:</strong> ${transaksi[1]}</p>
                        <p><strong><i class="fas fa-cookie-bite"></i> Menu:</strong> ${transaksi[2]}</p>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                        <div style="text-align: center; padding: 15px; background: white; border-radius: 8px; border: 1px solid rgba(139, 69, 19, 0.1);">
                            <h4 style="color: var(--primary); margin-bottom: 10px;">Harga Satuan</h4>
                            <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary);">Rp ${formatNumber(transaksi[3])}</p>
                        </div>
                        
                        <div style="text-align: center; padding: 15px; background: white; border-radius: 8px; border: 1px solid rgba(139, 69, 19, 0.1);">
                            <h4 style="color: var(--primary); margin-bottom: 10px;">Jumlah</h4>
                            <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary);">${transaksi[4]} item</p>
                        </div>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                        <h4 style="margin-bottom: 10px;">Total Pembayaran</h4>
                        <p style="font-size: 1.5rem; font-weight: bold;">Rp ${formatNumber(transaksi[5])}</p>
                    </div>
                `;
                
                content.innerHTML = html;
                modal.style.display = 'flex';
            }
        }
        
        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
        }
        
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        function exportToExcel() {
            alert('Fitur export ke Excel akan segera tersedia!');
            // Implementasi export ke Excel bisa ditambahkan di sini
        }
        
        function shareRiwayat() {
            const totalTransaksi = <?php echo $totalTransaksi; ?>;
            const totalPengeluaran = <?php echo $totalPengeluaran; ?>;
            
            const text = `Saya sudah melakukan ${totalTransaksi} transaksi di Toko Bakpia Bahagia dengan total pengeluaran Rp ${formatNumber(totalPengeluaran)}. Belanja bakpia berkualitas hanya di Bakpia Bahagia!`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Riwayat Belanja Bakpia Bahagia',
                    text: text,
                    url: window.location.href
                });
            } else {
                alert(text + '\n\nSalin teks di atas untuk berbagi.');
            }
        }
        
        // Tutup modal jika klik di luar
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>