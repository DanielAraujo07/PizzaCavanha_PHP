<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 2+ para acessar dashboard)
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Buscar informações baseadas no nível do usuário
$nivel_usuario = $_SESSION['class_nivel'];
$user_id = $_SESSION['id'];

// DASHBOARD PARA ATENDENTES (Nível 2)
if ($nivel_usuario == 2) {
    // Pedidos do dia
    $sql_pedidos_hoje = "SELECT COUNT(*) as total FROM pedido WHERE DATE(horario) = CURDATE()";
    $result_pedidos_hoje = mysqli_query($conn, $sql_pedidos_hoje);
    $pedidos_hoje = mysqli_fetch_assoc($result_pedidos_hoje)['total'];
    
    // Pedidos pendentes
    $sql_pedidos_pendentes = "SELECT COUNT(*) as total FROM pedido WHERE id_estado IN (1, 2, 3)";
    $result_pendentes = mysqli_query($conn, $sql_pedidos_pendentes);
    $pedidos_pendentes = mysqli_fetch_assoc($result_pendentes)['total'];
    
    
    // Últimos pedidos (5 mais recentes)
    $sql_ultimos_pedidos = "SELECT p.*, u.nome as cliente_nome, e.nome as estado_nome 
                           FROM pedido p 
                           LEFT JOIN users u ON p.id_cliente = u.id 
                           LEFT JOIN estados e ON p.id_estado = e.id 
                           ORDER BY p.horario DESC LIMIT 5";
    $result_ultimos = mysqli_query($conn, $sql_ultimos_pedidos);
    $ultimos_pedidos = mysqli_fetch_all($result_ultimos, MYSQLI_ASSOC);
}

// DASHBOARD PARA ENTREGADORES (Nível 3)
if ($nivel_usuario == 3) {
    // Pedidos para entrega
    $sql_entregas_pendentes = "SELECT COUNT(*) as total FROM pedido WHERE id_estado = 3";
    $result_entregas = mysqli_query($conn, $sql_entregas_pendentes);
    $entregas_pendentes = mysqli_fetch_assoc($result_entregas)['total'];
    
    // Entregas do dia
    $sql_entregas_hoje = "SELECT COUNT(*) as total FROM pedido WHERE DATE(horario) = CURDATE() AND id_estado = 4";
    $result_entregas_hoje = mysqli_query($conn, $sql_entregas_hoje);
    $entregas_hoje = mysqli_fetch_assoc($result_entregas_hoje)['total'];
    
    // Próximas entregas
    $sql_proximas_entregas = "SELECT p.*, u.nome as cliente_nome, en.endereco 
                             FROM pedido p 
                             LEFT JOIN users u ON p.id_cliente = u.id 
                             LEFT JOIN entrega en ON p.id_entrega = en.id 
                             WHERE p.id_estado = 3 
                             ORDER BY p.horario ASC LIMIT 5";
    $result_proximas = mysqli_query($conn, $sql_proximas_entregas);
    $proximas_entregas = mysqli_fetch_all($result_proximas, MYSQLI_ASSOC);
}

// DASHBOARD PARA COZINHEIROS (Nível 4)
if ($nivel_usuario == 4) {
    // Pedidos em preparação
    $sql_preparacao = "SELECT COUNT(*) as total FROM pedido WHERE id_estado = 2";
    $result_preparacao = mysqli_query($conn, $sql_preparacao);
    $pedidos_preparacao = mysqli_fetch_assoc($result_preparacao)['total'];
    
    // Pedidos preparados hoje
    $sql_preparados_hoje = "SELECT COUNT(*) as total FROM pedido WHERE DATE(horario) = CURDATE() AND id_estado IN (3, 4)";
    $result_preparados = mysqli_query($conn, $sql_preparados_hoje);
    $preparados_hoje = mysqli_fetch_assoc($result_preparados)['total'];
    
    // Próximos pedidos para preparar
    $sql_proximos_preparar = "SELECT p.*, u.nome as cliente_nome 
                             FROM pedido p 
                             LEFT JOIN users u ON p.id_cliente = u.id 
                             WHERE p.id_estado IN (1, 2) 
                             ORDER BY p.horario ASC LIMIT 5";
    $result_proximos = mysqli_query($conn, $sql_proximos_preparar);
    $proximos_preparar = mysqli_fetch_all($result_proximos, MYSQLI_ASSOC);
}

// DASHBOARD PARA FINANCEIRO (Nível 5)
if ($nivel_usuario == 5) {
    // Vendas do mês
    $sql_vendas_mes = "SELECT SUM(valor) as total FROM pedido WHERE MONTH(horario) = MONTH(CURDATE()) AND YEAR(horario) = YEAR(CURDATE()) AND id_estado != 5";
    $result_vendas_mes = mysqli_query($conn, $sql_vendas_mes);
    $vendas_mes = mysqli_fetch_assoc($result_vendas_mes)['total'] ?? 0;
    
    // Vendas do dia
    $sql_vendas_hoje = "SELECT SUM(valor) as total FROM pedido WHERE DATE(horario) = CURDATE() AND id_estado != 5";
    $result_vendas_hoje = mysqli_query($conn, $sql_vendas_hoje);
    $vendas_hoje = mysqli_fetch_assoc($result_vendas_hoje)['total'] ?? 0;
    
    // Ticket médio
    $sql_ticket_medio = "SELECT AVG(valor) as medio FROM pedido WHERE DATE(horario) = CURDATE() AND id_estado != 5";
    $result_ticket = mysqli_query($conn, $sql_ticket_medio);
    $ticket_medio = mysqli_fetch_assoc($result_ticket)['medio'] ?? 0;
    
    // Top produtos do mês
    $sql_top_produtos = "SELECT i.nome, COUNT(pi.id_item) as quantidade 
                        FROM pedido_itens pi 
                        LEFT JOIN itens i ON pi.id_item = i.id 
                        LEFT JOIN pedido p ON pi.id_pedido = p.id 
                        WHERE MONTH(p.horario) = MONTH(CURDATE()) 
                        GROUP BY i.nome 
                        ORDER BY quantidade DESC 
                        LIMIT 5";
    $result_top = mysqli_query($conn, $sql_top_produtos);
    $top_produtos = mysqli_fetch_all($result_top, MYSQLI_ASSOC);
}

// DASHBOARD PARA ADMIN (Nível 6)
if ($nivel_usuario == 6) {
    // Estatísticas gerais
    $sql_total_usuarios = "SELECT COUNT(*) as total FROM users";
    $result_usuarios = mysqli_query($conn, $sql_total_usuarios);
    $total_usuarios = mysqli_fetch_assoc($result_usuarios)['total'];
    
    $sql_total_pedidos = "SELECT COUNT(*) as total FROM pedido WHERE DATE(horario) = CURDATE()";
    $result_pedidos = mysqli_query($conn, $sql_total_pedidos);
    $total_pedidos = mysqli_fetch_assoc($result_pedidos)['total'];
    
    $sql_vendas_hoje = "SELECT SUM(valor) as total FROM pedido WHERE DATE(horario) = CURDATE() AND id_estado != 5";
    $result_vendas = mysqli_query($conn, $sql_vendas_hoje);
    $vendas_hoje = mysqli_fetch_assoc($result_vendas)['total'] ?? 0;
    
    $sql_pedidos_ativos = "SELECT COUNT(*) as total FROM pedido WHERE id_estado IN (1, 2, 3)";
    $result_ativos = mysqli_query($conn, $sql_pedidos_ativos);
    $pedidos_ativos = mysqli_fetch_assoc($result_ativos)['total'];
    
    // Últimas atividades do sistema
    $sql_ultimas_atividades = "SELECT la.*, u.nome as usuario_nome 
                              FROM logs_auditoria la 
                              LEFT JOIN users u ON la.id_usuario = u.id 
                              ORDER BY la.data_hora DESC 
                              LIMIT 8";
    $result_atividades = mysqli_query($conn, $sql_ultimas_atividades);
    $ultimas_atividades = mysqli_fetch_all($result_atividades, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboards</title>
    <link rel="shortcut icon" href="../assets/funcionario.png" />
    
    <!-- Fontes e Ícones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/18b2c31938.js" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="../assets/css/funcionario.css">
</head>

<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            </div>
            <div class="admin-user">
                <div class="admin-user-info">
                    <div class="admin-user-name"><?php echo htmlspecialchars($_SESSION['nome']); ?></div>
                    &bull;
                    <div class="admin-user-role"><?php echo htmlspecialchars($_SESSION['class_nome']); ?></div>
                </div>
                
                <!-- Botão de Toggle Tema -->
                <button class="theme-toggle" id="themeToggle" title="Alternar tema">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                
                <a href="../../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </nav>
    </header>

    <div class="admin-container">
        <aside class="admin-sidebar">
            <ul class="admin-menu">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

                <?php if ($_SESSION['class_nivel'] >= 4): ?>
                    <li><a href="produtos.php"><i class="fas fa-pizza-slice"></i> Produtos</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 4): ?>
                    <li><a href="ingredientes.php"><i class="fas fa-carrot"></i> Ingredientes</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 2): ?>
                    <li><a href="pedidos.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 5): ?>
                    <li><a href="categorias.php"><i class="fas fa-tag"></i> Categorias</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 6): ?>
                    <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 5): ?>
                    <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 6): ?>
                    <li><a href="logs_auditoria.php"><i class="fas fa-clipboard-list"></i> Logs de Auditoria</a></li>
                <?php endif; ?>

                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

            <!-- Botão de Toggle da Sidebar -->
    <button class="sidebar-toggle" id="sidebarToggle" title="Minimizar menu">
        <i class="fas fa-chevron-left"></i>
    </button>


        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard - <?php echo htmlspecialchars($_SESSION['class_nome']); ?></h1>
                    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>! Aqui está seu resumo do dia.</p>
                </div>
            </div>

            <!-- DASHBOARD ATENDENTE (Nível 2) -->
            <?php if ($nivel_usuario == 2): ?>
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo $pedidos_hoje; ?></span>
                    <span class="stat-label">Pedidos Hoje</span>
                </div>
                <div class="stat-card warning">
                    <span class="stat-number"><?php echo $pedidos_pendentes; ?></span>
                    <span class="stat-label">Pendentes</span>
                </div>
            </div>

            <div class="admin-sections">
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-clock"></i> Últimos Pedidos</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th>Horário</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimos_pedidos as $pedido): ?>
                                    <tr>
                                        <td>#<?php echo $pedido['id']; ?></td>
                                        <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                                        <td>R$ <?php echo number_format($pedido['valor'], 2, ',', '.'); ?></td>
                                        <td>
                                            <span class="status-badge 
                                                <?php echo $pedido['id_estado'] == 1 ? 'status-inactive' : 
                                                      ($pedido['id_estado'] == 2 ? 'status-warning' : 
                                                      ($pedido['id_estado'] == 3 ? 'status-info' : 'status-active')); ?>">
                                                <?php echo htmlspecialchars($pedido['estado_nome']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('H:i', strtotime($pedido['horario'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-bolt"></i> Ações Rápidas</h2>
                    </div>
                    <div class="section-content">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="pedidos.php" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Gerenciar Pedidos
                            </a>
                            <a href="pedidos.php?aba=ativos" class="btn btn-warning">
                                <i class="fas fa-list"></i> Ver Pedidos Ativos
                            </a>
                        </div>
                    </div>
                </section>
            </div>
            <?php endif; ?>

            <!-- DASHBOARD ENTREGADOR (Nível 3) -->
            <?php if ($nivel_usuario == 3): ?>
            <div class="stats-cards">
                <div class="stat-card warning">
                    <span class="stat-number"><?php echo $entregas_pendentes; ?></span>
                    <span class="stat-label">Entregas Pendentes</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $entregas_hoje; ?></span>
                    <span class="stat-label">Entregues Hoje</span>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-motorcycle"></i> Próximas Entregas</h2>
                </div>
                <div class="section-content">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Endereço</th>
                                    <th>Horário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximas_entregas as $entrega): ?>
                                <tr>
                                    <td>#<?php echo $entrega['id']; ?></td>
                                    <td><?php echo htmlspecialchars($entrega['cliente_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($entrega['endereco']); ?></td>
                                    <td><?php echo date('H:i', strtotime($entrega['horario'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- DASHBOARD COZINHEIRO (Nível 4) -->
            <?php if ($nivel_usuario == 4): ?>
            <div class="stats-cards">
                <div class="stat-card warning">
                    <span class="stat-number"><?php echo $pedidos_preparacao; ?></span>
                    <span class="stat-label">Em Preparação</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $preparados_hoje; ?></span>
                    <span class="stat-label">Preparados Hoje</span>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h2><i class="fas fa-utensils"></i> Próximos para Preparar</h2>
                </div>
                <div class="section-content">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Status</th>
                                    <th>Horário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximos_preparar as $pedido): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $pedido['id_estado'] == 1 ? 'status-inactive' : 'status-warning'; ?>">
                                            <?php echo $pedido['id_estado'] == 1 ? 'Processando' : 'Preparando'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('H:i', strtotime($pedido['horario'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- DASHBOARD FINANCEIRO (Nível 5) -->
            <?php if ($nivel_usuario == 5): ?>
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number">R$ <?php echo number_format($vendas_mes, 2, ',', '.'); ?></span>
                    <span class="stat-label">Vendas do Mês</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number">R$ <?php echo number_format($vendas_hoje, 2, ',', '.'); ?></span>
                    <span class="stat-label">Vendas Hoje</span>
                </div>
                <div class="stat-card warning">
                    <span class="stat-number">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></span>
                    <span class="stat-label">Ticket Médio</span>
                </div>
            </div>

            <div class="admin-sections">
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-chart-line"></i> Produtos Mais Vendidos</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_produtos as $produto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                        <td><?php echo $produto['quantidade']; ?> vendas</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-rocket"></i> Ações Financeiras</h2>
                    </div>
                    <div class="section-content">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="relatorios.php" class="btn btn-primary">
                                <i class="fas fa-chart-bar"></i> Ver Relatórios
                            </a>
                            <a href="categorias.php" class="btn btn-warning">
                                <i class="fas fa-tag"></i> Gerenciar Categorias
                            </a>
                            <a href="exportar_relatorio.php" class="btn btn-success">
                                <i class="fas fa-file-export"></i> Exportar Dados
                            </a>
                        </div>
                    </div>
                </section>
            </div>
            <?php endif; ?>

            <!-- DASHBOARD ADMIN (Nível 6) -->
            <?php if ($nivel_usuario == 6): ?>
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo $total_usuarios; ?></span>
                    <span class="stat-label">Total Usuários</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $total_pedidos; ?></span>
                    <span class="stat-label">Pedidos Hoje</span>
                </div>
                <div class="stat-card warning">
                    <span class="stat-number">R$ <?php echo number_format($vendas_hoje, 2, ',', '.'); ?></span>
                    <span class="stat-label">Vendas Hoje</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo $pedidos_ativos; ?></span>
                    <span class="stat-label">Pedidos Ativos</span>
                </div>
            </div>

            <div class="admin-sections">
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-history"></i> Últimas Atividades</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Usuário</th>
                                        <th>Ação</th>
                                        <th>Tabela</th>
                                        <th>ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimas_atividades as $atividade): ?>
                                    <tr>
                                        <td><?php echo date('d/m H:i', strtotime($atividade['data_hora'])); ?></td>
                                        <td><?php echo htmlspecialchars($atividade['usuario_nome']); ?></td>
                                        <td>
                                            <span class="status-badge 
                                                <?php echo $atividade['acao'] == 'INSERT' ? 'status-active' : 
                                                      ($atividade['acao'] == 'UPDATE' ? 'status-warning' : 'status-error'); ?>">
                                                <?php echo $atividade['acao']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($atividade['tabela_afetada']); ?></td>
                                        <td>#<?php echo $atividade['id_registro']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-cogs"></i> Administração do Sistema</h2>
                    </div>
                    <div class="section-content">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="usuarios.php" class="btn btn-primary">
                                <i class="fas fa-users"></i> Gerenciar Usuários
                            </a>
                            <a href="logs_auditoria.php" class="btn btn-warning">
                                <i class="fas fa-clipboard-list"></i> Ver Logs
                            </a>
                            <a href="produtos.php" class="btn btn-success">
                                <i class="fas fa-pizza-slice"></i> Produtos
                            </a>
                            <a href="ingredientes.php" class="btn btn-success">
                                <i class="fas fa-carrot"></i> Ingredientes
                            </a>
                            <a href="pedidos.php" class="btn btn-success">
                                <i class="fas fa-shopping-cart"></i> Pedidos
                            </a>
                            <a href="relatorios.php" class="btn btn-success">
                                <i class="fas fa-chart-bar"></i> Relatórios
                            </a>
                        </div>
                    </div>
                </section>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        // Sistema de Tema Claro/Escuro
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;

            // Verificar tema salvo ou preferência do sistema
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Aplicar tema inicial
            if (savedTheme === 'light' || (!savedTheme && !systemPrefersDark)) {
                enableLightMode();
            } else {
                enableDarkMode();
            }

            // Event listener para o botão de toggle
            themeToggle.addEventListener('click', function() {
                if (body.getAttribute('data-theme') === 'light') {
                    enableDarkMode();
                } else {
                    enableLightMode();
                }
            });

            function enableLightMode() {
                body.setAttribute('data-theme', 'light');
                themeIcon.className = 'fas fa-sun';
                themeToggle.title = 'Alternar para modo escuro';
                localStorage.setItem('theme', 'light');
            }

            function enableDarkMode() {
                body.removeAttribute('data-theme');
                themeIcon.className = 'fas fa-moon';
                themeToggle.title = 'Alternar para modo claro';
                localStorage.setItem('theme', 'dark');
            }

            // Observar mudanças na preferência do sistema
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        enableDarkMode();
                    } else {
                        enableLightMode();
                    }
                }
            });
        });

        // Sistema de Sidebar Minimizável
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.admin-sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminContent = document.querySelector('.admin-content');
    
    // Verificar estado salvo da sidebar
    const sidebarState = localStorage.getItem('sidebarMinimized');
    
    // Aplicar estado inicial
    if (sidebarState === 'true') {
        minimizeSidebar();
    } else {
        expandSidebar();
    }
    
    // Event listener para o botão de toggle
    sidebarToggle.addEventListener('click', function() {
        if (sidebar.classList.contains('minimized')) {
            expandSidebar();
        } else {
            minimizeSidebar();
        }
    });
    
    function minimizeSidebar() {
        sidebar.classList.add('minimized');
        sidebarToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
        sidebarToggle.title = 'Expandir menu';
        localStorage.setItem('sidebarMinimized', 'true');
        
        // Adicionar tooltips aos itens do menu
        addMenuTooltips();
    }
    
    function expandSidebar() {
        sidebar.classList.remove('minimized');
        sidebarToggle.innerHTML = '<i class="fas fa-chevron-left"></i>';
        sidebarToggle.title = 'Minimizar menu';
        localStorage.setItem('sidebarMinimized', 'false');
        
        // Remover tooltips dos itens do menu
        removeMenuTooltips();
    }
    
    function addMenuTooltips() {
        const menuItems = document.querySelectorAll('.admin-menu a');
        menuItems.forEach(item => {
            const text = item.querySelector('span');
            if (text) {
                item.setAttribute('data-tooltip', text.textContent);
            }
        });
    }
    
    function removeMenuTooltips() {
        const menuItems = document.querySelectorAll('.admin-menu a');
        menuItems.forEach(item => {
            item.removeAttribute('data-tooltip');
        });
    }
    
    // Fechar sidebar ao clicar em um link no mobile
    if (window.innerWidth <= 768) {
        const menuLinks = document.querySelectorAll('.admin-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (sidebar.classList.contains('minimized')) {
                    expandSidebar();
                }
            });
        });
    }
});
    </script>
</body>
</html>