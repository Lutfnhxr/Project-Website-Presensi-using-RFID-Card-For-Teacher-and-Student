<?php 
include '../config.php';

if (!isset($_GET['RFID'])) {
    echo "<script>location='dashboard.php';</script>";
    exit;
}

$RFID = $_GET['RFID'];
$query = mysqli_query($conn, "SELECT * FROM siswa_tb WHERE RFID='$RFID'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $NIM   = mysqli_real_escape_string($conn, $_POST['NIM']);
    $NAMA  = mysqli_real_escape_string($conn, $_POST['NAMA']);
    $KELAS = mysqli_real_escape_string($conn, $_POST['KELAS']);

    // Validasi input (contoh: pastikan NIM tidak kosong)
    if (empty($NIM) || empty($NAMA) || empty($KELAS)) {
        echo "<div class='alert alert-warning'>Semua kolom harus diisi!</div>";
    } else {
        $update = mysqli_query($conn, "UPDATE siswa_tb SET NIM='$NIM', NAMA='$NAMA', KELAS='$KELAS' WHERE RFID='$RFID'");
        if ($update) {
            // Notifikasi alert dan redirect ke dashboard
            echo "<script>
                    alert('Data berhasil diperbarui!');
                    window.location='dashboard.php';
                  </script>";
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3 text-center'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="shortcut icon" href="logo-polinema.png" type="image/x-icon">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">✏️ Edit Data Mahasiswa</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="NIM">NOMOR INDUK MAHASISWA</label>
                    <input type="text" name="NIM" class="form-control" value="<?= htmlspecialchars($data['NIM']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="NAMA">NAMA LENGKAP</label>
                    <input type="text" name="NAMA" class="form-control" value="<?= htmlspecialchars($data['NAMA']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="KELAS">KELAS</label>
                    <input type="text" name="KELAS" class="form-control" value="<?= htmlspecialchars($data['KELAS']); ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
