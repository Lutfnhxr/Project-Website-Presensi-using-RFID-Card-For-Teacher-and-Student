<?php
include '../config.php';

if (isset($_GET['RFID'])) {
    $RFID = $_GET['RFID'];

    // Hapus data presensi yang terkait RFID ini
    $hapus_presensi = mysqli_query($conn, "DELETE FROM presensi_tb WHERE RFID='$RFID'");

    if (!$hapus_presensi) {
        echo "<script>
                alert('Gagal menghapus data presensi!');
                window.location='dashboard.php';
              </script>";
        exit();
    }

    // Hapus data mahasiswa
    $hapus_mahasiswa = mysqli_query($conn, "DELETE FROM siswa_tb WHERE RFID='$RFID'");

    if ($hapus_mahasiswa) {
        // Notifikasi alert dan redirect ke dashboard
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location='dashboard.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal menghapus data mahasiswa!');
                window.location='dashboard.php';
              </script>";
    }
} else {
    echo "<script>location='dashboard.php';</script>";
    exit();
}
?>
