<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Verificar se o usuário é administrador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    // Se não for admin, verificar no banco de dados
    include "../conexao.php";
    
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        
        // Verificar se o email existe na tabela de admins
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 1) {
            // Usuário é admin - atualizar sessão
            $_SESSION['tipo_usuario'] = 'admin';
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
        } else {
            // Não é admin - redirecionar
            header("Location: ../index.php?acesso_negado");
            exit();
        }
    } else {
        // Não tem email na sessão - redirecionar
        header("Location: ../index.php");
        exit();
    }
}

// Verificação adicional de segurança
function verificarPermissaoAdmin() {
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
        header("Location: ../index.php?erro=acesso_negado");
        exit();
    }
}

// Chamar a verificação
verificarPermissaoAdmin();
?>