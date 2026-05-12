<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("../Includes/protect.php");
protect();
include("scriptTextArea.php");
include("../Includes/conn.php");

if (isset($_POST['add'])) {    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylemain.css">
    <link rel="stylesheet" href="styletTextArea.css">
    <link rel="stylesheet" href="styleToDoList.css">
    <script src="scriptBanner.js" defer></script>
    <script src="scriptLi.js" defer></script>
    <title>Tela</title>
</head>
<body>
    <header>
        <a href="/pratica-login/Includes/logout.php"><button id="deslogar">Sair</button></a>
        <h1>Olá <?= htmlspecialchars($_SESSION['nome']); ?></h1>
    </header>
    <main>
        <div id="banner-wrapper">
            <button class="btn-prev lados"><</button>
                <div id="banner-fade">
                    <div id="banner">
                        <section class="card">
                            <section class="card-header">
                                <p>Tarefas</p>
                            </section>
                            <!--
                            <section id="container-todolist">
                                <div id="container-inputs">
                                    <form method="POST">
                                        <input type="text" name="inp-todolist" id="inp-todolist">
                                        <button id="add" name="add">Add</button>
                                    </form>
                                </div>
                                <div id="container-ul">
                                    <ul id="lista">
                                        <li><button class="btn-prefinish"></button> oiiiiiiiiii <button class="btn-remover">X</button></li>
                                        <li><button class="btn-prefinish"></button> oiiiiiiiiii <button class="btn-remover">X</button></li>
                                        <li><button class="btn-prefinish"></button> oiiiiiiiiii <button class="btn-remover">X</button></li>
                                    </ul>
                                </div>
                            </section>
                            -->
                        </section>
                        <section class="card">
                            <section class="card-header">
                                <p>TEXTO</p>
                            </section>
                        </section>
                        <section class="card">
                            <section class="card-header">
                                <p>Anotações</p>
                            </section>
                            <div id="anotacoes">
                                <form method="POST" id="form-anotacoes">
                                    <textarea name="conteudo" id="textarea" placeholder="Escreva algo:"><?= $anotacao['conteudo'] ?? '' ?></textarea>
                                    <section>
                                        <button id="salvar-text" name="salvar-text" type="submit">Salvar</button>
                                        <button id="limpar-text" name="limpar-text" type="submit">Limpar</button>
                                    </section>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            <button class="btn-next lados">></button>
        </div>
    </main>
</body>
</html>