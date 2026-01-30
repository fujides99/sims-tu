<?php
// views/auth/logout.php

session_start();

// Hapus semua session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect ke DASHBOARD UTAMA (Public View), bukan ke Login
header("Location: ../public_view/index.php");
exit;
?>