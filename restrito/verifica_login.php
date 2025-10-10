<?php // VERIFICADOR DE USUÁRIO
session_start();

if (!isset($_SESSION['email'])) {
    // Redireciona para a página de login se não estiver autenticado
    header("Location: ../index.php");
    exit();
} if (isset($_SESSION['email'])) {
    // Se estiver autenticado, não passa pela página de login
    
}

// Verificar se o usuário ainda existe no banco de dados
include "conexao.php";
$email = $_SESSION['email'];
$sql = "SELECT * FROM clientes WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    // Se o usuário não existe mais no banco, destruir a sessão e voltar pro login
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>