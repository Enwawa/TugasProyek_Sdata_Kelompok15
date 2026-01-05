<?php
session_start();

function login($username, $password, $role) {
    // Sanitasi input
    $username = htmlspecialchars(trim($username));
    $password = trim($password);
    
    // Tentukan file berdasarkan role
    $filePath = ($role === 'admin')
        ? __DIR__ . "/../data/akun_admin.txt"
        : __DIR__ . "/../data/akun_user.txt";

    if (!file_exists($filePath)) {
        // Log percobaan login gagal
        logAktivitas($username, "LOGIN_FAILED_FILE_NOT_EXIST", $role);
        return false;
    }

    $dataAkun = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($dataAkun as $row) {
        $akun = explode(";", trim($row));
        if (count($akun) < 2) continue;

        if ($akun[0] === $username && $akun[1] === $password) {
            // Set session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['login_time'] = time();
            $_SESSION['session_id'] = session_id();
            
            // Log login sukses
            logAktivitas($username, "LOGIN_SUCCESS", $role);
            return true;
        }
    }
    
    // Log percobaan login gagal
    logAktivitas($username, "LOGIN_FAILED_WRONG_CREDENTIALS", $role);
    return false;
}

function logAktivitas($username, $aktivitas, $role) {
    $logFile = __DIR__ . "/../data/log_aktivitas.txt";
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    $timestamp = date("Y-m-d H:i:s");
    
    $logData = "$timestamp;$ipAddress;$username;$role;$aktivitas;$userAgent\n";
    file_put_contents($logFile, $logData, FILE_APPEND);
}

function checkSessionTimeout() {
    if (isset($_SESSION['login_time'])) {
        $session_duration = 3600; // 1 jam dalam detik
        if (time() - $_SESSION['login_time'] > $session_duration) {
            // Session expired
            session_unset();
            session_destroy();
            header("Location: login.php?session=expired");
            exit;
        }
        
        // Perbarui waktu login
        $_SESSION['login_time'] = time();
    }
}

function requireLogin($requiredRole = null) {
    if (!isset($_SESSION['login'])) {
        header("Location: ../login.php");
        exit;
    }
    
    if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
        header("Location: ../" . ($_SESSION['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_user.php'));
        exit;
    }
    
    // Cek session timeout
    checkSessionTimeout();
}
?>