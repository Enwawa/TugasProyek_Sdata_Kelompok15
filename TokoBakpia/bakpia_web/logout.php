<?php
session_start();

// Tambahkan log logout jika diperlukan
if (isset($_SESSION['username'])) {
    $logFile = __DIR__ . "/../data/log_aktivitas.txt";
    $logData = $_SESSION['username'] . ";" . date("Y-m-d H:i:s") . ";LOGOUT\n";
    file_put_contents($logFile, $logData, FILE_APPEND);
}

// Hapus semua session
$_SESSION = array();

// Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Redirect ke login dengan pesan logout
header("Location: login.php?logout=1");
exit;
?>