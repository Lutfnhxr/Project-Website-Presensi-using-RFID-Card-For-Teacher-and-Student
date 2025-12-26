<?php 
session_start();
date_default_timezone_set("Asia/Jakarta");
include '../config.php';  

$rfidInput = '';
$showSwal = false;
$swalType = '';
$swalMessage = '';
$toast = ['show' => false, 'type' => '', 'message' => ''];

// Proses jika form dikirim
if (isset($_POST['simpan'])) {
    $NIM   = mysqli_real_escape_string($conn, $_POST['NIM']);
    $NAMA  = mysqli_real_escape_string($conn, $_POST['NAMA']);
    $KELAS = mysqli_real_escape_string($conn, $_POST['KELAS']);
    $RFID  = mysqli_real_escape_string($conn, $_POST['RFID']);

    // Cek apakah RFID sudah ada di siswa_tb
    $cekRFID = mysqli_query($conn, "SELECT * FROM siswa_tb WHERE RFID = '$RFID'");
    if (mysqli_num_rows($cekRFID) > 0) {
        // RFID sudah terdaftar, gagal simpan
        $toast = ['show' => true, 'type' => 'danger', 'message' => 'RFID sudah terdaftar.'];
    } else {
        // Insert data siswa baru
        $insertSiswa = mysqli_query($conn, "INSERT INTO siswa_tb (RFID, NIM, NAMA, KELAS) VALUES ('$RFID', '$NIM', '$NAMA', '$KELAS')");

        if ($insertSiswa) {
            $tanggal = date("Y-m-d");
            $jam     = date("H:i:s");
            mysqli_query($conn, "INSERT INTO presensi_tb (RFID, TANGGAL, JAM, NIM, NAMA, KELAS) 
                                 VALUES ('$RFID', '$tanggal', '$jam', '$NIM', '$NAMA', '$KELAS')");

            mysqli_query($conn, "DELETE FROM rfid_buffer");

            $toast = ['show' => true, 'type' => 'success', 'message' => 'Data berhasil disimpan.'];
            // Setelah sukses, redirect bisa diganti dengan header, tapi agar toast tampil, pakai meta refresh + JS
            echo "<meta http-equiv='refresh' content='2;url=laporan_presensi.php'>";
        } else {
            $toast = ['show' => true, 'type' => 'danger', 'message' => 'Gagal menyimpan data: ' . mysqli_error($conn)];
        }
    }
} elseif (isset($_POST['cek_data'])) {
    $NIM  = mysqli_real_escape_string($conn, $_POST['NIM']);
    $NAMA = mysqli_real_escape_string($conn, $_POST['NAMA']);

    if (!empty($NIM) && !empty($NAMA)) {
        $cekMahasiswa = mysqli_query($conn, "SELECT * FROM siswa_tb WHERE NIM = '$NIM' AND NAMA = '$NAMA'");
        if (mysqli_num_rows($cekMahasiswa) > 0) {
            $rfidBuffer = mysqli_query($conn, "SELECT rfid FROM rfid_buffer ORDER BY id DESC LIMIT 1");
            if ($rfidBuffer && mysqli_num_rows($rfidBuffer) > 0) {
                $rfidInput = mysqli_fetch_assoc($rfidBuffer)['rfid'];
            }
            $showSwal = true;
            $swalType = 'success';
            $swalMessage = 'Data ditemukan';
        } else {
            $showSwal = true;
            $swalType = 'error';
            $swalMessage = 'Data tidak ditemukan';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Presensi Mahasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="shortcut icon" href="logo-polinema.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url('backgrbut.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Segoe UI', sans-serif;
            margin: 0; padding: 0;
        }
        .container {
            margin-top: 100px;
            max-width: 600px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: rgba(0, 123, 255, 0.8);
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label { font-weight: bold; }
        .btn-success { background-color: #28a745; border: none; }
        .btn-secondary { background-color: #6c757d; border: none; }
    </style>
</head>
<body>

<?php if ($showSwal): ?>
<script>
    Swal.fire({
        icon: '<?= $swalType ?>',
        title: '<?= $swalMessage ?>',
        showConfirmButton: false,
        timer: 1500
    });
</script>
<?php endif; ?>

<?php if ($toast['show']): ?>
<script>
    Swal.fire({
        icon: '<?= $toast['type'] ?>',
        title: '<?= addslashes($toast['message']) ?>',
        showConfirmButton: true,
    }).then(() => {
        <?php if ($toast['type'] === 'success'): ?>
            window.location.href = 'laporan_presensi.php';
        <?php endif; ?>
    });
</script>
<?php endif; ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">➕ Tambah Data Presensi Baru</h4>
        </div>
        <div class="card-body">
            <form method="post" autocomplete="off">
                <div class="form-group">
                    <label for="NIM">NOMOR INDUK MAHASISWA</label>
                    <input type="text" name="NIM" id="NIM" class="form-control" placeholder="Masukkan NIM" required>
                </div>
                <div class="form-group">
                    <label for="NAMA">NAMA LENGKAP</label>
                    <input type="text" name="NAMA" id="NAMA" class="form-control" placeholder="Masukkan Nama" required>
                </div>
                <button type="submit" name="cek_data" class="btn btn-info mb-3">🔍 Cek Data</button>

                <div class="form-group">
                    <label for="RFID">RFID</label>
                    <input type="text" name="RFID" id="RFID" class="form-control" value="<?= htmlspecialchars($rfidInput) ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="KELAS">KELAS</label>
                    <input type="text" name="KELAS" id="KELAS" class="form-control" placeholder="Masukkan Kelas" required>
                </div>
                <button type="submit" name="simpan" class="btn btn-success">💾 Absensi</button>
                <a href="../index.php" class="btn btn-secondary">← Kembali</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
