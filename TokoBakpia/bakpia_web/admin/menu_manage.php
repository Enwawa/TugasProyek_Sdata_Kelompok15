<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$file = __DIR__ . "/../../data/menu_bakpia.txt";

if (isset($_POST['simpan'])) {
    $jenis = htmlspecialchars($_POST['jenis']);
    $nama  = htmlspecialchars($_POST['nama']);
    $harga = (int)$_POST['harga'];

    $row = "$jenis;$nama;$harga\n";
    file_put_contents($file, $row, FILE_APPEND);
    
    header("Location: menu_manage.php?success=1");
    exit;
}

if (isset($_GET['delete'])) {
    $deleteIndex = (int)$_GET['delete'];
    if (file_exists($file)) {
        $menuData = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($menuData[$deleteIndex])) {
            unset($menuData[$deleteIndex]);
            file_put_contents($file, implode("\n", $menuData));
            header("Location: menu_manage.php?success=2");
            exit;
        }
    }
}

$menu = file_exists($file)
    ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin</title>
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
                <li><a href="menu_manage.php" class="active"><i class="fas fa-utensils"></i> Kelola Menu</a></li>
                <li><a href="transaksi_all.php"><i class="fas fa-chart-bar"></i> Transaksi</a></li>
                <li><a href="../logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-edit"></i> Kelola Menu Bakpia</h1>
            <p>Tambah, edit, atau hapus menu bakpia</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 1): ?>
                    <i class="fas fa-check-circle"></i> Menu berhasil ditambahkan!
                <?php else: ?>
                    <i class="fas fa-check-circle"></i> Menu berhasil dihapus!
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="card mb-30">
            <h2 class="card-title">Tambah Menu Baru</h2>
            <form method="post" action="">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="jenis"><i class="fas fa-tag"></i> Jenis Bakpia</label>
                        <select id="jenis" name="jenis" class="form-select" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Basah">Basah</option>
                            <option value="Kering">Kering</option>
                            <option value="Spesial">Spesial</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama"><i class="fas fa-cookie-bite"></i> Nama Bakpia</label>
                        <input type="text" id="nama" name="nama" class="form-control" 
                               placeholder="Contoh: Bakpia Kacang Hijau (isi 15)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga"><i class="fas fa-money-bill-wave"></i> Harga</label>
                        <input type="number" id="harga" name="harga" class="form-control" 
                               placeholder="Contoh: 20000" min="0" required>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" name="simpan" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Menu
                    </button>
                    <button type="reset" class="btn">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2 class="card-title">Daftar Menu Bakpia</h2>
            <p>Total: <strong><?php echo count($menu); ?></strong> menu</p>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Nama Bakpia</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($menu)): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div style="padding: 30px;">
                                        <i class="fas fa-cookie-bite fa-3x" style="color: var(--primary); margin-bottom: 15px;"></i>
                                        <p>Belum ada menu. Silahkan tambahkan menu baru.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($menu as $index => $row): 
                                $d = explode(";", $row);
                                if (count($d) < 3) continue;
                            ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <span class="badge <?php echo ($d[0] == 'Basah') ? 'badge-success' : 'badge-primary'; ?>">
                                        <?php echo $d[0]; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($d[1]); ?></td>
                                <td style="font-weight: bold; color: var(--primary);">
                                    Rp <?php echo number_format($d[2]); ?>
                                </td>
                                <td>
                                    <a href="?delete=<?php echo $index; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia Admin Panel</p>
            <p>Jl. Bakpia No. 123, Yogyakarta | Telp: (0274) 123456</p>
            <div style="margin-top: 15px;">
                <a href="#" style="color: var(--accent); margin: 0 10px;"><i class="fab fa-instagram"></i></a>
            </div>
            <p><i class="fas fa-user-shield"></i> Login sebagai: <?php echo $_SESSION['username']; ?></p>
        </div>
    </div>
</body>
</html>