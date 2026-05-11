<?php
include("../Includes/conn.php");

if (!isset($_GET['token'])) {
    die("Token inválido");
}

$token = $_GET['token'];

$stmt_sql = $conn->prepare("SELECT * FROM cadastros WHERE token = ? AND token_expira > NOW()");
$stmt_sql->bind_param("s", $token);
$stmt_sql->execute();
$result = $stmt_sql->get_result();

if ($result->num_rows == 0) {
    die("Token inválido ou expirado");
}

$usuario = $result->fetch_assoc();

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    if ($senha !== $confirmar) {
        $erro = "senha_diferente";
    } else {

        $novaSenha = password_hash($senha, PASSWORD_DEFAULT);
        $id = $usuario['id'];

        $stmt_update = $conn->prepare("UPDATE cadastros SET senha = ?, token = NULL, token_expira = NULL WHERE id = ?");
        $stmt_update->bind_param("si", $novaSenha, $id);
        $stmt_update->execute();

        echo "<script>alert('Senha alterada com sucesso!'); window.location.href='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Nova Senha</title>
<link rel="stylesheet" href="styleNS.css">
<script src="script.js" defer></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<main>
    <section id="tela">
        <form method="POST" autocomplete="off">
            <p id="cad">NOVA SENHA</p>

            <section class="show-password">
                <input type="password" name="senha" id="idsenha" placeholder="Nova senha:" required minlength="8">
                <button id="btnsenha1" type="button" onclick="mostrarSenha2()">
                    <i class="bx bx-hide"></i>
                </button>
            </section>

            <section class="show-password">
                <input type="password" name="confirmar" id="idsenha2" placeholder="Confirmar senha:" required minlength="8">
                <button id="btnsenha2" type="button" onclick="mostrarSenha3()">
                    <i class="bx bx-hide"></i>
                </button>
            </section>
            <section id="avisos">
                <p id="aviso-erro-senhadif">Erro senhas diferentes!</p>
            </section>
            <button type="submit" id="conf">Salvar</button>
        </form>
    </section>
</main>
<script>const erroPHP = "<?= $erro ?>";</script>
</body>
</html>