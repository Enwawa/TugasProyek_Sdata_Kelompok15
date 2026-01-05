<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$file = __DIR__ . "/../../data/transaksi.txt";
$data = file_exists($file)
    ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <nav class="navbar">
            <a href="../dashboard_admin.php" class="logo">
                <i class="fas fa-cookie-bite"></i>
                <span>Bakpia Bahagia</span>
            </a>
            <ul class="nav-links">
                <li><a href="../dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="menu_manage.php"><i class="fas fa-utensils"></i> Kelola Menu</a></li>
                <li><a href="transaksi_all.php" class="active"><i class="fas fa-chart-bar"></i> Transaksi</a></li>
                <li><a href="../logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-chart-bar"></i> Laporan Transaksi</h1>
            <p>Data semua transaksi pelanggan</p>
        </div>

        <div class="card mb-30">
            <h2 class="card-title">Statistik Transaksi</h2>
            <?php
            $totalTransaksi = count($data);
            $totalPendapatan = 0;
            $totalQty = 0;
            $users = [];
            
            foreach ($data as $row) {
                $d = explode(";", $row);
                if (count($d) >= 6) {
                    $totalPendapatan += $d[5];
                    $totalQty += $d[4];
                    $users[$d[0]] = true;
                }
            }
            ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div class="text-center p-20" style="background: rgba(139, 69, 19, 0.1); border-radius: 10px;">
                    <i class="fas fa-shopping-cart fa-3x" style="color: var(--primary);"></i>
                    <h3 style="margin: 10px 0;"><?php echo $totalTransaksi; ?></h3>
                    <p>Total Transaksi</p>
                </div>
                
                <div class="text-center p-20" style="background: rgba(46, 125, 50, 0.1); border-radius: 10px;">
                    <i class="fas fa-users fa-3x" style="color: var(--success);"></i>
                    <h3 style="margin: 10px 0;"><?php echo count($users); ?></h3>
                    <p>Total User</p>
                </div>
                
                <div class="text-center p-20" style="background: rgba(255, 215, 0, 0.1); border-radius: 10px;">
                    <i class="fas fa-box fa-3x" style="color: var(--accent);"></i>
                    <h3 style="margin: 10px 0;"><?php echo $totalQty; ?></h3>
                    <p>Total Item Terjual</p>
                </div>
                
                <div class="text-center p-20" style="background: rgba(198, 40, 40, 0.1); border-radius: 10px;">
                    <i class="fas fa-money-bill-wave fa-3x" style="color: var(--danger);"></i>
                    <h3 style="margin: 10px 0;">Rp <?php echo number_format($totalPendapatan); ?></h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Detail Transaksi</h2>
            
            <div style="margin-bottom: 20px;">
                <input type="text" id="searchInput" class="form-control" 
                       placeholder="Cari transaksi berdasarkan user atau menu..." 
                       style="max-width: 400px;">
            </div>
            
            <div class="table-container">
                <table id="transactionsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Tanggal</th>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div style="padding: 30px;">
                                        <i class="fas fa-shopping-cart fa-3x" style="color: var(--primary); margin-bottom: 15px;"></i>
                                        <p>Belum ada transaksi.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $total = 0;
                            $no = 1;
                            foreach ($data as $row): 
                                $d = explode(";", $row);
                                if (count($d) < 6) continue;
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td>
                                    <span class="badge badge-primary"><?php echo htmlspecialchars($d[0]); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($d[1]); ?></td>
                                <td><?php echo htmlspecialchars($d[2]); ?></td>
                                <td style="font-weight: bold;">Rp <?php echo number_format($d[3]); ?></td>
                                <td>
                                    <span class="badge" style="background: rgba(139, 69, 19, 0.1); color: var(--primary);">
                                        <?php echo $d[4]; ?>
                                    </span>
                                </td>
                                <td style="font-weight: bold; color: var(--success);">
                                    Rp <?php echo number_format($d[5]); ?>
                                </td>
                            </tr>
                            <?php 
                                $total += $d[5];
                                $no++;
                            endforeach; 
                            ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: linear-gradient(to right, var(--primary), var(--secondary)); color: white;">
                            <td colspan="6" style="text-align: right; font-weight: bold;">
                                <i class="fas fa-money-check-alt"></i> Total Semua Transaksi
                            </td>
                            <td style="font-weight: bold; font-size: 1.1em;">
                                Rp <?php echo number_format($total); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <?php if (!empty($data)): ?>
                <div style="margin-top: 20px; text-align: right;">
                    <a href="export_transaksi.php" class="btn btn-primary">
                        <i class="fas fa-file-export"></i> Export ke Excel
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia Admin Panel</p>
            <p>Laporan transaksi per <?php echo date('d F Y H:i:s'); ?></p>
        </div>
    </div>

    <script>
        // Fitur pencarian
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#transactionsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>