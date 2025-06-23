<?php
require_once 'admin.php';
$admin = new Admin();
$admin->logout();
header("Location: login.php");
