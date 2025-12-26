<?php 
session_start();
date_default_timezone_set("Asia/Jakarta"); 
include '../config.php';

// Fungsi validasi tanggal (YYYY-MM-DD)
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

$tgl1 = isset($_GET['tgl1']) && validateDate($_GET['tgl1']) ? $_GET['tgl1'] : date("Y-m-d");
$tgl2 = isset($_GET['tgl2']) && validateDate($_GET['tgl2']) ? $_GET['tgl2'] : date("Y-m-d");
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Jika tombol export CSV diklik
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=laporan_presensi_' . $tgl1 . '_sampai_' . $tgl2 . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['NO', 'NAMA LENGKAP', 'RFID', 'NOMOR INDUK MAHASISWA', 'KELAS', 'TANGGAL', 'JAM']);

    $sqlExport = "SELECT p.RFID, s.NIM, s.NAMA, s.KELAS, p.TANGGAL, p.JAM 
                FROM presensi_tb p
                LEFT JOIN siswa_tb s ON p.RFID = s.RFID
                WHERE p.TANGGAL BETWEEN ? AND ? ";

    if ($search !== '') {
        $sqlExport .= "AND (s.NAMA LIKE ? OR s.NIM LIKE ?) ";
    }
    $sqlExport .= "ORDER BY p.TANGGAL DESC, p.JAM DESC";

    $stmt = $conn->prepare($sqlExport);
    if ($search !== '') {
        $searchParam = '%' . $search . '%';
        $stmt->bind_param('ssss', $tgl1, $tgl2, $searchParam, $searchParam);
    } else {
        $stmt->bind_param('ss', $tgl1, $tgl2);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $no = 1;
    while ($row = $res->fetch_assoc()) {
        fputcsv($output, [
            $no++,
            $row['NAMA'],
            $row['RFID'],
            $row['NIM'],
            $row['KELAS'],
            $row['TANGGAL'],
            $row['JAM']
        ]);
    }
    fclose($output);
    exit;
}

// Query data dengan filter tanggal dan search
$sql = "SELECT p.RFID, s.NIM, s.NAMA, s.KELAS, p.TANGGAL, p.JAM 
        FROM presensi_tb p
        LEFT JOIN siswa_tb s ON p.RFID = s.RFID
        WHERE p.TANGGAL BETWEEN ? AND ? ";

if ($search !== '') {
    $sql .= "AND (s.NAMA LIKE ? OR s.NIM LIKE ?) ";
}

$sql .= "ORDER BY p.TANGGAL DESC, p.JAM DESC";

$stmt = $conn->prepare($sql);
if ($search !== '') {
    $searchParam = '%' . $search . '%';
    $stmt->bind_param('ssss', $tgl1, $tgl2, $searchParam, $searchParam);
} else {
    $stmt->bind_param('ss', $tgl1, $tgl2);
}
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="shortcut icon" href="logo-polinema.png" type="image/x-icon">
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
        .container {
            margin-top: 130px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: rgba(23, 162, 184, 0.8);
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 2rem;
        }
        .form-inline .form-control {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid #ccc;
            border-radius: 5px;
            min-width: 150px;
        }
        .btn-primary, .btn-secondary {
            border-radius: 5px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .table {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
        }
        .table th, .table td {
            background-color: rgba(255, 255, 255, 0.8);
            vertical-align: middle;
            text-align: center;
        }
        .thead-dark th {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
        }
        label {
            font-weight: bold;
        }
        .search-group {
            margin-left: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">📋 Laporan Presensi</h4>
        </div>
        <div class="card-body">
            <form method="get" class="form-inline mb-4 flex-wrap">
                <label class="mr-2">DARI:</label>
                <input type="date" name="tgl1" class="form-control mr-3 mb-2" required value="<?= htmlspecialchars($tgl1) ?>">
                <label class="mr-2">SAMPAI:</label>
                <input type="date" name="tgl2" class="form-control mr-3 mb-2" required value="<?= htmlspecialchars($tgl2) ?>">
                
                <div class="search-group">
                    <label class="mr-2">Cari Nama / NIM:</label>
                    <input type="text" name="search" class="form-control mr-3 mb-2" placeholder="Masukkan nama atau NIM" value="<?= htmlspecialchars($search) ?>">
                </div>

                <button type="submit" class="btn btn-primary mb-2">🔍 Filter</button>
                <a href="laporan_presensi.php" class="btn btn-secondary mb-2 ml-2">🔄 Reset</a>
                <button type="submit" name="export" value="csv" class="btn btn-success mb-2 ml-auto">⬇️ Export CSV</button>
            </form>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>NO</th>
                                <th>NAMA LENGKAP</th>
                                <th>RFID</th>
                                <th>NOMOR INDUK MAHASISWA</th>
                                <th>KELAS</th>
                                <th>TANGGAL</th>
                                <th>JAM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            while ($row = $result->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['NAMA']) ?></td>
                                <td><?= htmlspecialchars($row['RFID']) ?></td>
                                <td><?= htmlspecialchars($row['NIM']) ?></td>
                                <td><?= htmlspecialchars($row['KELAS']) ?></td>
                                <td><?= htmlspecialchars($row['TANGGAL']) ?></td>
                                <td><?= htmlspecialchars($row['JAM']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    Data presensi tidak ditemukan untuk tanggal <?= htmlspecialchars($tgl1) ?> s/d <?= htmlspecialchars($tgl2) ?> <?= $search !== '' ? "dengan kata kunci '".htmlspecialchars($search)."'" : '' ?>.
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
