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

// Set header untuk download file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="transaksi_bakpia_' . date('Y-m-d') . '.xls"');

echo "<table border='1'>";
echo "<tr>";
echo "<th>No</th>";
echo "<th>User</th>";
echo "<th>Tanggal</th>";
echo "<th>Menu</th>";
echo "<th>Harga</th>";
echo "<th>Qty</th>";
echo "<th>Subtotal</th>";
echo "</tr>";

$no = 1;
$total = 0;
foreach ($data as $row) {
    $d = explode(";", $row);
    if (count($d) >= 6) {
        echo "<tr>";
        echo "<td>" . $no . "</td>";
        echo "<td>" . htmlspecialchars($d[0]) . "</td>";
        echo "<td>" . htmlspecialchars($d[1]) . "</td>";
        echo "<td>" . htmlspecialchars($d[2]) . "</td>";
        echo "<td>" . number_format($d[3]) . "</td>";
        echo "<td>" . $d[4] . "</td>";
        echo "<td>" . number_format($d[5]) . "</td>";
        echo "</tr>";
        
        $total += $d[5];
        $no++;
    }
}

echo "<tr>";
echo "<td colspan='6'><strong>Total</strong></td>";
echo "<td><strong>" . number_format($total) . "</strong></td>";
echo "</tr>";

echo "</table>";
exit;