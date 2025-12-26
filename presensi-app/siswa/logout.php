<?php
session_start();
session_unset();
session_destroy();

// Redirect kembali ke login dengan notifikasi logout
header("Location: login.php?logout=success");
exit;
?>
