<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

include "conexao.php";

$email = $_SESSION['email'];

// Query simplificada para teste
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro no prepare: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$usuario = mysqli_fetch_assoc($result);

// Buscar informações da classe separadamente
$sql_classe = "SELECT nome, nivel FROM user_classes WHERE id = ?";
$stmt_classe = mysqli_prepare($conn, $sql_classe);
mysqli_stmt_bind_param($stmt_classe, "i", $usuario['class_id']);
mysqli_stmt_execute($stmt_classe);
$result_classe = mysqli_stmt_get_result($stmt_classe);
$classe = mysqli_fetch_assoc($result_classe);

// Configurar sessão
$_SESSION['id'] = $usuario['id'];
$_SESSION['nome'] = $usuario['nome'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['class_id'] = $usuario['class_id'];
$_SESSION['class_nome'] = $classe['nome'];
$_SESSION['class_nivel'] = $classe['nivel'];
?>