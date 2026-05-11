<?php
include("../Includes/conn.php");

$usuario_id = $_SESSION['id'];

// SALVAR
if (isset($_POST['salvar-text'])) {
    $conteudo = $_POST['conteudo'];

    $sql = "INSERT INTO anotacoes (usuario_id, conteudo)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE conteudo = VALUES(conteudo)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $conteudo);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// LIMPAR (apaga as anotações do usuário)
if (isset($_POST['limpar-text'])) {
    $conteudo = "";

    $sql = "INSERT INTO anotacoes (usuario_id, conteudo)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE conteudo = VALUES(conteudo)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $conteudo);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT conteudo FROM anotacoes WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();
$anotacao = $result->fetch_assoc();
?>