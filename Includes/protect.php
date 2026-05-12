<?php

if (!function_exists("protect")) {

    function protect() {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id'])) {

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");

            header('Location: /pratica-login/CadastroLogin/login.php');
            exit;
        }
    }
}
?>