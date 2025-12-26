<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Presensi Mahasiswa</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="shortcut icon" href="presensi-app/img/logo-polinema.png" type="image/x-icon">
  <style>
    body {
      background-image: url('backgrbut.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 40px 20px 20px 20px;
      justify-content: flex-start; /* konten lebih ke atas */
    }

    .fade-in {
      animation: fadeIn 1.2s ease-in-out both;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-link {
      display: inline-block;
      text-decoration: none;
      color: white;
    }

    .logo {
      width: 175px;
      margin-bottom: 20px;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.1);
      opacity: 0.8;
    }

    h1 {
      font-size: 2.2rem;
      font-weight: bold;
      margin-bottom: 10px;
    }

    p.tagline {
      font-size: 1rem;
      margin-bottom: 10px;
      font-style: italic;
      color: #ddd;
    }

    .btn-custom {
      font-size: 1rem;
      padding: 10px 20px;
      background-color: transparent;
      border: 2px solid #fff;
      color: #fff;
      transition: all 0.3s ease;
      min-width: 180px;
    }

    .btn-custom:hover {
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    footer {
      background-color: rgba(0, 0, 0, 0.5);
      color: #ccc;
      text-align: center;
      padding: 15px 10px;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="overlay">
    <!-- Logo -->
    <a href="presensi-app/index.php" class="logo-link fade-in">
      <img src="presensi-app/img/logo-polinema.png" alt="Logo Polinema" class="logo">
    </a>

    <!-- Judul dan deskripsi -->
     <br>
    <h1 class="fade-in" style="animation-delay: 0.5s;">Selamat Datang di Sistem Presensi Mahasiswa</h1>
    <p class="tagline fade-in" style="animation-delay: 0.7s;">Sistem Presensi Mahasiswa Politeknik Negeri Malang.</p>
    <p class="tagline fade-in" style="animation-delay: 0.9s;">Fakultas Teknik Elektro, Program Studi Teknik Telekomunikasi</p>

    <!-- Tombol navigasi -->
    <div class="btn-group d-flex flex-column align-items-center fade-in" style="animation-delay: 1s; animation-duration: 1.2s;">
      <a href="presensi-app/siswa/login.php" class="btn btn-custom mb-4 mt-3">Admin Login</a>
      <br>
      <p class="tagline fade-in" style="animation-delay: 1.2s;">Menu Untuk Mahasiswa</p>
      <div class="d-flex justify-content-center mt-3">
        <a href="presensi-app/index.php" class="btn btn-custom mx-2">Masuk ke Sistem Presensi</a>
        <a href="presensi-app/siswa/pendaftaran.php" class="btn btn-custom mx-2">Pendaftaran Mahasiswa Baru</a>
      </div>
    </div>
  </div>

  <footer>
    <p class="text-center text-white font-medium">© 2025 _Xjustfun - All rights reserved</p>
  </footer>
</body>
</html>
