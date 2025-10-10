<?php //include "verifica_admin.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Pizza do Cavanha</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --admin-primary: #2c3e50;
            --admin-secondary: #34495e;
            --admin-accent: #e74c3c;
            --admin-success: #27ae60;
            --admin-warning: #f39c12;
            --admin-light: #ecf0f1;
            --admin-dark: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: #333;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-logo h1 {
            font-size: 1.5rem;
            color: var(--admin-light);
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-user-info {
            text-align: right;
        }

        .admin-user-name {
            font-weight: bold;
        }

        .admin-user-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .logout-btn {
            background: var(--admin-accent);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .admin-container {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        .admin-sidebar {
            width: 250px;
            background: var(--admin-secondary);
            color: white;
            padding: 2rem 0;
        }

        .admin-menu {
            list-style: none;
        }

        .admin-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .admin-menu a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 2rem;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .admin-menu a:hover, .admin-menu a.active {
            background: var(--admin-primary);
            border-left: 4px solid var(--admin-accent);
        }

        .admin-menu i {
            width: 20px;
            text-align: center;
        }

        .admin-content {
            flex: 1;
            padding: 2rem;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid var(--admin-accent);
        }

        .card h3 {
            color: var(--admin-dark);
            margin-bottom: 0.5rem;
        }

        .card .number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--admin-primary);
        }

        .card.success {
            border-left-color: var(--admin-success);
        }

        .card.warning {
            border-left-color: var(--admin-warning);
        }

        .recent-orders {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .recent-orders h2 {
            margin-bottom: 1rem;
            color: var(--admin-dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .status {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status.processando {
            background: #fff3cd;
            color: #856404;
        }

        .status.enviado {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status.entregue {
            background: #d4edda;
            color: #155724;
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .admin-sidebar {
                width: 100%;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1>🍕 Painel Administrativo</h1>
            </div>
            <div class="admin-user">
                <div class="admin-user-info">
                    <div class="admin-user-name"></div>
                    <div class="admin-user-role">Administrador</div>
                </div>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </nav>
    </header>

    <div class="admin-container">
        <aside class="admin-sidebar">
            <ul class="admin-menu">
                <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="produtos.php"><i class="fas fa-pizza-slice"></i> Gerenciar Produtos</a></li>
                <li><a href="ingredientes.php"><i class="fas fa-carrot"></i> Gerenciar Ingredientes</a></li>
                <li><a href="pedidos.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="categorias.php"><i class="fas fa-tags"></i> Categorias</a></li>
                <li><a href="clientes.php"><i class="fas fa-users"></i> Clientes</a></li>
                <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
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