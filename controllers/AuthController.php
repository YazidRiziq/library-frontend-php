<?php

class AuthController {

    private $officerApi = "http://localhost:8080/api/auth/login/officer";
    private $memberApi  = "http://localhost:8080/api/auth/login/member";

    public function login($email, $password) {

        // 1. Coba login sebagai OFFICER
        $officer = $this->callApi($this->officerApi, $email, $password);
        if ($officer !== null) {
            session_start();
            $_SESSION['id']   = $officer['id'];
            $_SESSION['name'] = $officer['name'];
            $_SESSION['email'] = $officer['email'];
            $_SESSION['type'] = 'OFFICER';

            header("Location: ../views/officer/dashboard.php");
            exit();
        }

        // 2. Coba login sebagai MEMBER
        $member = $this->callApi($this->memberApi, $email, $password);
        if ($member !== null) {
            session_start();
            $_SESSION['id']   = $member['id'];
            $_SESSION['name'] = $member['name'];
            $_SESSION['type'] = 'MEMBER';

            header("Location: ../views/member/dashboard.php");
            exit();
        }

        // 3. Gagal semua
        return "Email or password is incorrect.";
    }

    private function callApi($url, $email, $password) {

        $payload = json_encode([
            "email" => $email,
            "password" => $password
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS     => $payload
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        return json_decode($response, true);
    }
}
