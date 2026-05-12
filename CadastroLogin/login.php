<?php
session_start();

include("../Includes/conn.php");
include("../Includes/config.php");

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['cadastrar'])) {

        $nome = $_POST['nome'];
        $senha = $_POST['senha'];

        $stmt_sql = $conn->prepare("SELECT * FROM cadastros WHERE nome = ?");
        $stmt_sql->bind_param("s", $nome);
        $stmt_sql->execute();
        $result = $stmt_sql->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            if ($usuario['bloqueado_ate'] !== null && $usuario['bloqueado_ate'] < date("Y-m-d H:i:s")) {

                $stmt_clear = $conn->prepare("UPDATE cadastros SET tentativas = 0, bloqueado_ate = NULL WHERE id = ?");
                $stmt_clear->bind_param("i", $usuario['id']);
                $stmt_clear->execute();

                $usuario['tentativas'] = 0;
                $usuario['bloqueado_ate'] = null;
            }

            if ($usuario['bloqueado_ate'] !== null && $usuario['bloqueado_ate'] > date("Y-m-d H:i:s")) {
                header("Location: login.php?erro=tentativas");
                exit;
            }

            if (password_verify($senha, $usuario['senha'])) {
                $stmt_reset = $conn->prepare(" UPDATE cadastros SET tentativas = 0, bloqueado_ate = NULL WHERE id = ?");
                $stmt_reset->bind_param("i", $usuario['id']);
                $stmt_reset->execute();

                session_regenerate_id(true);

                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];

                header("Location: /pratica-login/Main/telalogin.php");
                exit;

            } else {
                $tentativas = $usuario['tentativas'] + 1;

                if ($tentativas > 5) {
                    $bloqueado_ate = date("Y-m-d H:i:s", strtotime("+5 minutes"));

                    $stmt_block = $conn->prepare("UPDATE cadastros SET tentativas = ?, bloqueado_ate = ? WHERE id = ?");
                    $stmt_block->bind_param("isi", $tentativas, $bloqueado_ate, $usuario['id']);
                    $stmt_block->execute();

                    header("Location: login.php?erro=tentativas");
                    exit;

                } else {
                    $stmt_tentativas = $conn->prepare("UPDATE cadastros SET tentativas = ? WHERE id = ?");
                    $stmt_tentativas->bind_param("ii", $tentativas, $usuario['id']);
                    $stmt_tentativas->execute();

                    header("Location: login.php?erro=negado");
                    exit;
                }
            }
        } else {
            header("Location: login.php?erro=negado");
            exit;
        }   
    }
    
// RECUPERAR SENHA
if (isset($_POST['recuperar'])) {
    $Recnome = $_POST['recNome'];
    $Recemail = $_POST['recEmail'];

    $stmt_sql = $conn->prepare("SELECT * FROM cadastros WHERE nome = ? AND email = ?");
    $stmt_sql->bind_param("ss", $Recnome, $Recemail);
    $stmt_sql->execute();
    $result = $stmt_sql->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));
        $id = $usuario['id'];

        $stmt_update = $conn->prepare("UPDATE cadastros SET token = ?, token_expira = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $token, $expira, $id);
        $stmt_update->execute();
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_USER;
            $mail->Password = EMAIL_PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom(EMAIL_USER, 'Recuperação');
            $mail->addAddress($Recemail);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de senha';

            $mail->Body = "Olá $Recnome,<br><br>
            Clique no link abaixo para redefinir sua senha:<br>
            <a href='http://localhost/pratica-login/CadastroLogin/novaSenha.php?token=$token'>
            Redefinir Senha
            </a><br><br>
            Esse link expira em 15 minutos.";
            $mail->send();
            
            header("Location: login.php?erro=sucesso_email");
            exit;

        } catch (Exception $e) {
            header("Location: login.php?erro=erro_rec_email");
            exit;
        }

    } else {
        header("Location: login.php?erro=erro_encontrado");
        exit;
    }
}
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="stylecad.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <a href="Cadastro.php"><button>Cadastro</button></a>
    </header>
    <main>
        <section id="tela">
            <form action="" method="POST" autocomplete="off">
                <p id="cad">LOGIN</p>
                <input type="text" name="nome" id="idnome" required placeholder="Nome:">
                <section id="show-password">
                    <input type="password" name="senha" id="idsenha" required placeholder="Senha:" autocomplete="new-password">
                    <button id="btnsenha" onclick="mostrarSenha()" type="button"><i class="bx bx-hide"></i></button>
                </section>
                <section id="avisos">
                    <p id="aviso-erro-login">Erro ao fazer Login!</p>
                    <p id="aviso-tentativas">Muitas Tentativas!!</p>
                </section>
                <button type="button" name="showrecuperar" id="showRec" onclick="mostrarRec()">Esqueci minha Senha</button>
                <button type="submit" id="conf" name="cadastrar">Confirmar</button>
            </form>
        </section>
        <section id="RecSenha">
            <form action="" method="POST" autocomplete="off">
                <button type="button" id="sairRS" onclick="sairRec()">X</button>
                <p>Recuperação de Senha</p>
                <input type="text" name="recNome" id="recuNome" required placeholder="Nome:">
                <input type="email" name="recEmail" id="recuEmail" placeholder="Email:" required autocomplete="off">
                <section id="avisos-rec">
                    <p id="aviso-erro-rec">Erro cadastro nao encontrado!</p>
                    <p id="aviso-erro-rec-email">Erro ao enviar email!</p>
                    <p id="aviso-sucesso-rec-email">Email Enviado!</p>
                </section>
                <button type="submit" name="recuperar" id="btnRS">Confirmar</button>
            </form>
        </section>
    </main>
    <script>
        const erroPHP = "<?= htmlspecialchars($_GET['erro'] ?? '') ?>";
    </script>
</body>
</html>
