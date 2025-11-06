<?php
require_once __DIR__ . '/../src/auth/Auth.php';
\auth\Auth::logout();
header('Location: ./login.php');
exit;
