<?php
// views/auth/logout.php
session_start();
session_unset();
session_destroy();

// PERBAIKAN: Gunakan /sims-tu/ di depan agar browser tidak bingung
header("Location: /sims-tu/views/auth/login.php?pesan=logout");
exit();
?>