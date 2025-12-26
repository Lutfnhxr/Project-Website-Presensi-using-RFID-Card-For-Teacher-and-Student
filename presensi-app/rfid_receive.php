<?php
// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "presensi_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah data UID dikirim
if (isset($_POST['uid'])) {
    $uid = $conn->real_escape_string($_POST['uid']);

    // Cek apakah UID terdaftar sebagai mahasiswa
    $cek = "SELECT * FROM mahasiswa WHERE uid = '$uid'";
    $result = $conn->query($cek);

    if ($result->num_rows > 0) {
        // UID ditemukan, simpan ke rfid_log
        $sql = "INSERT INTO rfid_log (uid) VALUES ('$uid')";
        if ($conn->query($sql) === TRUE) {
            echo "Presensi berhasil untuk UID: $uid";
        } else {
            echo "Gagal simpan presensi: " . $conn->error;
        }
    } else {
        echo "UID tidak dikenal. Akses ditolak.";
    }
} else {
    echo "Data UID tidak diterima";
}

$conn->close();
?>
