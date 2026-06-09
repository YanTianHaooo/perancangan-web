<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
if (!isLoggedIn()) { redirect('auth/login.php'); }
if (isAdmin()) {
    header("Location: " . BASE_URL . "dashboard/admin/");
} else {
    header("Location: " . BASE_URL . "dashboard/pembeli/");
}
exit();
