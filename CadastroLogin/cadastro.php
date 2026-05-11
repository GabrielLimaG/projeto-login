<?php
include("../Includes/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cadastrar'])) {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $stmt_nome = $conn->prepare("SELECT * FROM cadastros WHERE nome = ?");
        $stmt_nome->bind_param("s", $nome);
        $stmt_nome->execute();
        $result_nome = $stmt_nome->get_result();

        $stmt_email = $conn->prepare("SELECT * FROM cadastros WHERE email = ?");
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $result_email = $stmt_email->get_result();


        $nomeExiste = $result_nome->num_rows > 0;
        $emailExiste = $result_email->num_rows > 0;

        $erro = "";

    if ($nomeExiste && $emailExiste) {
            $erro = "nome_email";
        } elseif ($nomeExiste) {
            $erro = "nome";
        } elseif ($emailExiste) {
            $erro = "email";
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO cadastros (nome, email, senha) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $nome, $email, $senha);

            if ($stmt_insert->execute()){
                echo "<script>alert('Cadastro realizado com sucesso!');window.location.href = 'login.php';</script>";
            } else {
                echo "Erro: " . $stmt_insert->error;
            }
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="stylecad.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <a href="login.php"><button>Login</button></a>
    </header>
    <main>
        <section id="tela">
            <form action="" method="POST" autocomplete="off">
                <p id="cad">CADASTRO</p>
                <input type="text" name="nome" id="idnome" required placeholder="Nome:" minlength="6">
                <section id="show-password">
                    <input type="password" name="senha" id="idsenha" required placeholder="Senha:" minlength="8" autocomplete="new-password">
                    <button id="btnsenha" onclick="mostrarSenha()" type="button"><i class="bx bx-hide"></i></button>
                </section>
                <input type="email" name="email" id="idemail" required placeholder="Email:" autocomplete="off">
                <section id="avisos">
                    <p id="aviso-nome-email">Já existe alguém com esse nome e email!</p>
                    <p id="aviso-nome">Já existe alguém com esse nome!</p>
                    <p id="aviso-email">Já existe alguém com esse email!</p>
                </section>
                <button type="submit" id="conf" name="cadastrar">Confirmar</button>
            </form>
        </section>
    </main>
    <script>
        const erroPHP = <?= json_encode($erro ?? '') ?>;
    </script>
</body>
</html>