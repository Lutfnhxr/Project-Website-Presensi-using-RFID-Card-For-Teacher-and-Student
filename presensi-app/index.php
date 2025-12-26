<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplikasi Presensi</title>
  <link rel="shortcut icon" href="img/logo-polinema.png" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    body {
      background-image: url('img/backgrbut.jpg'); /* Pastikan path gambar benar */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .welcome-card {
      margin-top: 40px; /* Mengurangi jarak antara logo dan bagian atas */
      animation: fadeIn 1s ease-in-out;
    }

    .logo-image {
      width: 150px; /* Ukuran logo medium */
      height: auto;
      margin-bottom: 15px;
    }

    .title-box {
      background-color: rgba(0, 0, 0, 0.5); /* Latar transparan gelap */
      padding: 15px 25px;
      border-radius: 10px;
      display: inline-block;
    }

    .btn-lg {
      font-size: 1.25rem;
    }

    .info-presensi,
    .info-laporan {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 6px;
      text-align: left;
    }

    .info-presensi {
      border-left: 6px solid #007bff; /* Biru */
    }

    .info-laporan {
      border-left: 6px solid #28a745; /* Hijau */
    }

    .font-medium {
      font-size: 1.1rem;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Mengubah margin footer agar lebih ke atas */
    footer {
      margin-top: 30px; /* Mengurangi jarak footer dari konten */
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="container welcome-card text-center">
    <div class="d-flex flex-column align-items-center">
      <img src="img/logo-polinema.png" alt="Logo Institusi" class="logo-image">
      <div class="title-box text-white mt-2">
        <h1 class="font-weight-bold mb-2">Aplikasi Presensi Mikrokontroler</h1>
        <p class="font-medium mb-0">Luthfan Ahmad Habibi - DIII T. Telekomunikasi, TA 2024/2025</p>
        <p class="font-medium">Politeknik Negeri Malang</p>
      </div>
    </div>
  </div>

  <!-- Konten Tengah -->
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <!-- Kartu Informasi -->
        <div class="info-card info-presensi">
          <h5>📲 Presensi Mahasiswa</h5>
          <p>Lakukan Presensi Mahasiswa</p>
        </div>

        <div class="info-card info-laporan">
          <h5>📋 Laporan Kehadiran Mahasiswa</h5>
          <p>Lihat data kehadiran Mahasiswa</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="text-center mt-4">
          <a href="siswa/tambah_mahasiswa.php" class="btn btn-primary btn-lg mr-2">
            📲 Masuk Menu Presensi
          </a>
          <a href="siswa/laporan_presensi.php" class="btn btn-success btn-lg">
            📋 Masuk Menu Laporan Presensi
          </a>
        </div>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="mt-3 mb-3">
    <p class="text-center text-white font-medium">© 2025 _Xjustfun - All rights reserved</p>
  </footer>

</body>
</html>
