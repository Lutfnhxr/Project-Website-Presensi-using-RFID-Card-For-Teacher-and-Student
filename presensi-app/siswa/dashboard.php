<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include '../config.php';

$adminName = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Presensi</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo-polinema.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
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
            padding: 0;
        }
        .table-transparent {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .thead-dark th {
            background-color: rgba(0, 0, 0, 0.6) !important;
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.2);
        }
        .custom-navbar {
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-transparent {
            background-color: transparent;
            border: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light justify-content-between custom-navbar">
    <span class="navbar-text font-weight-bold ml-3">Dashboard Kepegawaian</span>
    <div class="dropdown mr-4">
        <button class="btn btn-transparent text-dark font-weight-bold dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            👤 Username: <?= htmlspecialchars($adminName); ?>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="logout.php">🚪 Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4 text-black">Data Presensi Mahasiswa</h2>
    <table class="table table-bordered table-hover table-transparent">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Kelas</th>
                <th>RFID</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = "
                SELECT 
                    p.RFID, 
                    IFNULL(s.NAMA, 'Tidak Dikenal') AS NAMA,
                    IFNULL(s.NIM, '-') AS NIM,
                    IFNULL(s.KELAS, '-') AS KELAS,
                    p.TANGGAL, 
                    p.JAM
                FROM presensi_tb p
                LEFT JOIN siswa_tb s ON p.RFID = s.RFID
                ORDER BY p.TANGGAL DESC, p.JAM DESC
                LIMIT 10
            ";

            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['NAMA']) . "</td>
                        <td>" . htmlspecialchars($row['NIM']) . "</td>
                        <td>" . htmlspecialchars($row['KELAS']) . "</td>
                        <td>" . htmlspecialchars($row['RFID']) . "</td>
                        <td>" . htmlspecialchars($row['TANGGAL']) . "</td>
                        <td>" . htmlspecialchars($row['JAM']) . "</td>
                        <td>";
                    if (!empty($row['RFID'])) {
                        echo "<a href='edit_mahasiswa.php?RFID=" . urlencode($row['RFID']) . "' class='btn btn-warning btn-sm'>Edit</a> 
                              <a href='hapus_mahasiswa.php?RFID=" . urlencode($row['RFID']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus data presensi ini?')\">Hapus</a>";
                    } else {
                        echo "<span class='text-muted'>Tidak tersedia</span>";
                    }
                    echo "</td></tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='8' class='text-center text-danger'>Tidak ada data presensi yang tersedia.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php if (isset($_GET['logout']) && $_GET['logout'] === 'success') : ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Logout!',
        showConfirmButton: false,
        timer: 2000
    });
</script>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
