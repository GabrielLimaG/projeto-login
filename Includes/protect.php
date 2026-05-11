<?php
if(!function_exists("protect")){
    function protect(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        if(!isset($_SESSION['id']) || !is_numeric($_SESSION['id'])){
            header('Location: /pratica-login/CadastroLogin/login.php');
            exit;
        }
    } 
}
?>