<?php 
include "../verifica_login.php";
// Somente entregadores (nivel 2+) podem acessar
redirecionar_se_nao_permitido(2, '../index.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Pedidos - Pizza do Cavanha</title>
</head>
<body>
    <h1>Gerenciar Pedidos</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?> (<?php echo htmlspecialchars($_SESSION['class_nome']); ?>)</p>
    <!-- Conteúdo da página de pedidos para funcionários -->
</body>
</html>