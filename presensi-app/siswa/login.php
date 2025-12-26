<?php
session_start();
include('../config.php'); // Masukkan file koneksi database

// Jika sudah login, alihkan ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$successLogin = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Query untuk mengambil data username dan password dari tabel admin_tb
    $stmt = $conn->prepare("SELECT * FROM admin_tb WHERE USERNAME = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (md5($password) === $user['PASSWORD']) { // Ganti password_verify dengan md5
            // Set session jika login berhasil
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username; // Menyimpan username di session
            $successLogin = true;
        } else {
            $error = 'Maaf, Password salah!';
        }
    } else {
        $error = 'Maaf, Username tidak ditemukan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Data Presensi Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="shortcut icon" href="logo-polinema.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background-color: rgba(0,0,0,0.6);
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .login-box {
            background-color: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
        }

        .login-box input {
            background-color: rgba(255,255,255,0.2);
            color: #fff;
            border: none;
        }

        .login-box input::placeholder {
            color: #ccc;
        }

        .login-box .btn {
            background-color: transparent;
            border: 2px solid #fff;
            color: #fff;
            transition: 0.3s;
        }

        .login-box .btn:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .error-message {
            color: #ffcccc;
            margin-top: 10px;
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
        <img src="logo-polinema.png" alt="Logo Polinema" class="logo">
        <h3 class="mb-4">Login Admin</h3>

        <div class="login-box">
            <form method="post">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-block">Login</button>
            </form>
        </div>
    </div>
    <footer>
        <p class="text-center text-white font-medium">© 2025 _Xjustfun - All rights reserved</p>
    </footer>

    <?php if ($error) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: '<?= $error; ?>',
            confirmButtonColor: '#d33'
        });
    </script>
    <?php endif; ?>

    <?php if ($successLogin) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = "dashboard.php";
        });
    </script>
    <?php endif; ?>

</body>
</html>
