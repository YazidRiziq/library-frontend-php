<?php
require_once 'AuthController.php';

$auth = new AuthController();

$email = $_POST['email'];
$password = $_POST['password'];

$msg = $auth->login($email, $password);

if ($msg) {
    header("Location: ../views/auth/login.php?error=" . urlencode($msg));
    exit();
}
