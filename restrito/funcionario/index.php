<?php include
    '../verifica_login.php';

if ($_SESSION['class_nivel'] == 1) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionário do Cavanha</title>
    <link rel="shortcur icon" href="../assets/funcionario.svg" />
    
    <!-- Fontes Oswald, Jaro e Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">

    <!-- Icones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/18b2c31938.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/funcionario.css">
</head>

<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1><i class="fa-solid fa-user-tie"></i> Painel do Funcionário</h1>
            </div>
            <div class="admin-user">
                <div class="admin-user-info">
                    <div class="admin-user-name"><?php echo htmlspecialchars($_SESSION['nome']); ?></div>
                    &bull;
                    <div class="admin-user-role"><?php echo htmlspecialchars($_SESSION['class_nome']); ?></div>
                </div>
                <a href="../../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </nav>
    </header>

    <div class="admin-container">
        <aside class="admin-sidebar">
            <ul class="admin-menu">
                <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="produtos.php"><i class="fas fa-pizza-slice"></i> Produtos</a></li>
                <li><a href="ingredientes.php"><i class="fas fa-carrot"></i> Ingredientes</a></li>
                <li><a href="pedidos.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="categorias.php"><i class="fas fa-tags"></i> Categorias</a></li>
                <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Pedidos Hoje</h3>
                    <div class="number">12</div>
                    <p>+2 em relação a ontem</p>
                </div>
                <div class="card success">
                    <h3>Total de Vendas</h3>
                    <div class="number">R$ 2.847,50</div>
                    <p>Este mês</p>
                </div>
                <div class="card warning">
                    <h3>Produtos Ativos</h3>
                    <div class="number">24</div>
                    <p>No cardápio</p>
                </div>
                <div class="card">
                    <h3>Clientes Cadastrados</h3>
                    <div class="number">156</div>
                    <p>Total</p>
                </div>
            </div>

            <section class="recent-orders">
                <h2>Pedidos Recentes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#00125</td>
                            <td>João Silva</td>
                            <td>R$ 45,00</td>
                            <td><span class="status processando">Processando</span></td>
                            <td>10/12/2024 19:30</td>
                        </tr>
                        <tr>
                            <td>#00124</td>
                            <td>Maria Santos</td>
                            <td>R$ 68,50</td>
                            <td><span class="status enviado">Enviado</span></td>
                            <td>10/12/2024 18:45</td>
                        </tr>
                        <tr>
                            <td>#00123</td>
                            <td>Pedro Oliveira</td>
                            <td>R$ 32,00</td>
                            <td><span class="status entregue">Entregue</span></td>
                            <td>10/12/2024 17:20</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        // Ativar menu atual
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const menuLinks = document.querySelectorAll('.admin-menu a');

            menuLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>