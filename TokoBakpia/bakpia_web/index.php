<?php
session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_user.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Bakpia Bahagia - Home</title>
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
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#contact">Kontak</a></li>
                <li><a href="login.php" class="btn btn-primary btn-sm">Login</a></li>
            </ul>
        </nav>
    </div>

    <div class="hero" id="home" style="min-height: 80vh; display: flex; align-items: center; background: linear-gradient(rgba(139, 69, 19, 0.85), rgba(210, 105, 30, 0.85)), url('https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1280&q=80'); background-size: cover; background-position: center;">
        <div class="container text-center">
            <h1 style="font-size: 3.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Selamat Datang di <br>Toko Bakpia Bahagia</h1>
            <p style="font-size: 1.3rem; max-width: 800px; margin: 20px auto; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                Menyediakan bakpia berkualitas dengan rasa autentik Yogyakarta sejak 1990
            </p>
            <div style="margin-top: 40px;">
                <a href="login.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.2rem; margin: 5px;">
                    <i class="fas fa-sign-in-alt"></i> Login untuk Pemesanan
                </a>
                <a href="#menu" class="btn" style="padding: 15px 40px; font-size: 1.2rem; margin: 5px; background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">
                    <i class="fas fa-eye"></i> Lihat Menu
                </a>
            </div>
        </div>
    </div>

    <div class="container" id="menu" style="padding: 80px 20px;">
        <div class="text-center mb-30">
            <h2 class="card-title">Varian Bakpia Kami</h2>
            <p style="color: var(--dark); max-width: 600px; margin: 0 auto;">Pilih dari berbagai varian bakpia berkualitas dengan rasa yang menggugah selera</p>
        </div>
        
        <div class="menu-grid">
            <div class="menu-item">
                <div class="menu-item-header" style="background: linear-gradient(135deg, var(--primary), #A0522D);">
                    <i class="fas fa-soft-drink fa-2x"></i>
                    <h3>Bakpia Basah</h3>
                </div>
                <div class="menu-item-body">
                    <p style="color: var(--dark);">Bakpia dengan tekstur lembut dan basah, fresh langsung dari oven. Terasa lumer di mulut!</p>
                    <p><strong>Varian Populer:</strong> Kacang Hijau, Coklat, Keju, Durian, Nanas</p>
                    <div style="margin-top: 20px;">
                        <a href="login.php" class="btn btn-primary btn-block">
                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-item-header" style="background: linear-gradient(135deg, #8B4513, #D2691E);">
                    <i class="fas fa-cookie fa-2x"></i>
                    <h3>Bakpia Kering</h3>
                </div>
                <div class="menu-item-body">
                    <p style="color: var(--dark);">Bakpia dengan tekstur kering dan renyah, tahan lama tanpa mengurangi kelezatannya.</p>
                    <p><strong>Varian Populer:</strong> Pandan, Nastar, Black Sesame, Sambal, Kurkir</p>
                    <div style="margin-top: 20px;">
                        <a href="login.php" class="btn btn-primary btn-block">
                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-item-header" style="background: linear-gradient(135deg, #D2691E, #FF8C00);">
                    <i class="fas fa-gift fa-2x"></i>
                    <h3>Paket Spesial</h3>
                </div>
                <div class="menu-item-body">
                    <p style="color: var(--dark);">Paket lengkap untuk kebutuhan hadiah, oleh-oleh, atau acara spesial keluarga.</p>
                    <p><strong>Pilihan Paket:</strong> Mix 24, Happy Package, KITKAT Special</p>
                    <div style="margin-top: 20px;">
                        <a href="login.php" class="btn btn-primary btn-block">
                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="about" style="padding: 60px 20px; background: rgba(139, 69, 19, 0.05); border-radius: 15px;">
        <div class="card" style="background: transparent; box-shadow: none; border: none;">
            <h2 class="card-title text-center">Kenapa Memilih Bakpia Bahagia?</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 40px;">
                <div style="text-align: center; padding: 25px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-leaf fa-2x" style="color: white;"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Bahan Alami</h3>
                    <p>Menggunakan bahan-bahan alami pilihan tanpa pengawet buatan</p>
                </div>
                
                <div style="text-align: center; padding: 25px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-award fa-2x" style="color: white;"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Rasa Autentik</h3>
                    <p>Resep turun-temurun dengan cita rasa khas Yogyakarta asli</p>
                </div>
                
                <div style="text-align: center; padding: 25px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-shipping-fast fa-2x" style="color: white;"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Pengiriman Cepat</h3>
                    <p>Pesanan diproses dan dikirim dalam waktu 24 jam</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="contact" style="padding: 60px 20px;">
        <div class="card">
            <h2 class="card-title text-center">Hubungi Kami</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; margin-top: 30px;">
                <div>
                    <h3 style="color: var(--primary); margin-bottom: 20px;">Informasi Kontak</h3>
                    
                    <div style="margin-bottom: 20px; display: flex; align-items: flex-start;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 15px; margin-top: 5px;"></i>
                        <div>
                            <h4 style="margin: 0 0 5px 0;">Alamat Toko</h4>
                            <p>Jl. Bakpia No. 123, Pathuk, Kota Yogyakarta, DIY 55253</p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px; display: flex; align-items: flex-start;">
                        <i class="fas fa-phone-alt" style="color: var(--primary); margin-right: 15px; margin-top: 5px;"></i>
                        <div>
                            <h4 style="margin: 0 0 5px 0;">Telepon</h4>
                            <p>(0274) 123456 | 0812-3456-7890</p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px; display: flex; align-items: flex-start;">
                        <i class="fas fa-envelope" style="color: var(--primary); margin-right: 15px; margin-top: 5px;"></i>
                        <div>
                            <h4 style="margin: 0 0 5px 0;">Email</h4>
                            <p>info@bakpiabahagia.com</p>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <h4 style="color: var(--primary); margin-bottom: 15px;">Jam Operasional</h4>
                        <p>Senin - Sabtu: 08:00 - 21:00 WIB</p>
                        <p>Minggu & Hari Libur: 09:00 - 18:00 WIB</p>
                    </div>
                </div>
                
                <div>
                    <h3 style="color: var(--primary); margin-bottom: 20px;">Lokasi Toko</h3>
                    <div style="background: #f5f5f5; border-radius: 10px; padding: 20px; height: 300px; display: flex; align-items: center; justify-content: center;">
                        <div style="text-align: center;">
                            <i class="fas fa-map-marked-alt fa-3x" style="color: var(--primary); margin-bottom: 15px;"></i>
                            <p>Peta lokasi akan ditampilkan di sini</p>
                            <p style="font-size: 0.9rem; color: #666;">Jl. Bakpia No. 123, Yogyakarta</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Toko Bakpia Bahagia. Seluruh hak cipta dilindungi.</p>
            <p>Jl. Bakpia No. 123, Yogyakarta | Telp: (0274) 123456 | Email: info@bakpiabahagia.com</p>
            <div style="margin-top: 15px;">
                <a href="#" style="color: var(--accent); margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: var(--accent); margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color: var(--accent); margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-whatsapp"></i></a>
                <a href="#" style="color: var(--accent); margin: 0 10px; font-size: 1.2rem;"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
    </div>
</body>
</html>