<?php 
session_start();
date_default_timezone_set(timezoneId: "Asia/Jakarta"); 
include '../config.php';
// Inisialisasi variabel supaya tidak undefined saat pertama load
$rfid = $nama = $nim = $kelas = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rfid = trim($_POST['rfid'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');

    if ($rfid === '') {
        $error = "RFID harus diisi dengan scan kartu RFID.";
    } elseif ($nama === '' || $nim === '' || $kelas === '') {
        $error = "Semua kolom harus diisi.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM siswa_tb WHERE RFID = ?");
        $stmt->bind_param("s", $rfid);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error = "RFID sudah terdaftar.";
        } else {
            $stmt = $conn->prepare("INSERT INTO siswa_tb (RFID, NAMA, NIM, KELAS) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $rfid, $nama, $nim, $kelas);
            if ($stmt->execute()) {
                $success = "Mahasiswa baru berhasil didaftarkan.";
                // kosongkan form supaya user tahu data sudah terkirim
                $rfid = $nama = $nim = $kelas = '';
            } else {
                $error = "Gagal menyimpan data: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran Mahasiswa Baru</title>
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
            margin: 0;
            padding: 30px;
        }
        .card {
            background-color: rgba(255,255,255,0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: auto;
        }
        .rfid-input {
            font-weight: bold;
            font-size: 1.2rem;
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="card p-4 mt-5 shadow">
    <h3 class="mb-4">Form Pendaftaran Mahasiswa Baru</h3>

    <form method="post" id="form-mahasiswa" autocomplete="off">
        <div class="form-group">
            <label for="rfid">RFID <small class="text-muted">Gunakan Kartu Tanda Mahasiswa Anda</small></label>
            <input type="text" id="rfid" name="rfid" class="form-control rfid-input" value="<?= htmlspecialchars($rfid) ?>" readonly required placeholder="Tempel kartu RFID Anda">
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
        </div>

        <div class="form-group">
            <label for="nim">Nomor Induk Mahasiswa (NIM)</label>
            <input type="text" id="nim" name="nim" class="form-control" value="<?= htmlspecialchars($nim) ?>" required>
        </div>

        <div class="form-group">
            <label for="kelas">Kelas</label>
            <input type="text" id="kelas" name="kelas" class="form-control" value="<?= htmlspecialchars($kelas) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Daftar Mahasiswa</button>
        <a href="index.php" class="btn btn-secondary btn-block mt-2">Kembali ke Beranda</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
// Fungsi polling AJAX untuk ambil RFID terbaru dari buffer
function cekRFID() {
    $.ajax({
        url: 'rfid_buffer_fetch.php',  // pastikan nama file ini benar dan ada
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.rfid && data.rfid !== $('#rfid').val()) {
                $('#rfid').val(data.rfid);
            }
        },
        complete: function() {
            setTimeout(cekRFID, 2000);
        }
    });
}

$(document).ready(function() {
    cekRFID();

    <?php if ($success): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes($success) ?>',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "tambah_mahasiswa.php";
        });
    <?php elseif ($error): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= addslashes($error) ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
});
</script>

</body>
</html>
