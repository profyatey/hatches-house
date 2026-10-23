<?php
require_once __DIR__ . '/../config/config.php';
session_unset();
session_destroy();
header('Location: ' . site_url('admin/login.php'));
exit;
