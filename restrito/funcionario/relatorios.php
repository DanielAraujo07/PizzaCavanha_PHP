<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível Financeiro para acessar relatórios)
if ($_SESSION['class_nivel'] < 5) {
    header('Location: ../index.php');
    exit();
}

// Processar filtros
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : date('Y-m-01');
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

// Se data_fim estiver vazia, usar data atual
if (empty($data_fim)) {
    $data_fim = date('Y-m-d');
}

$filtro_estado = isset($_GET['estado']) ? intval($_GET['estado']) : '';

// Buscar dados para os cards
$sql_vendas_periodo = "SELECT SUM(valor) as total FROM pedido WHERE DATE(horario) BETWEEN ? AND ?";
$stmt = mysqli_prepare($conn, $sql_vendas_periodo);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_vendas = mysqli_fetch_assoc($result)['total'] ?? 0;

$sql_pedidos_periodo = "SELECT COUNT(*) as total FROM pedido WHERE DATE(horario) BETWEEN ? AND ?";
$stmt = mysqli_prepare($conn, $sql_pedidos_periodo);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_pedidos = mysqli_fetch_assoc($result)['total'] ?? 0;

$sql_clientes_ativos = "SELECT COUNT(DISTINCT id_cliente) as total FROM pedido WHERE DATE(horario) BETWEEN ? AND ?";
$stmt = mysqli_prepare($conn, $sql_clientes_ativos);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$clientes_ativos = mysqli_fetch_assoc($result)['total'] ?? 0;

// NOVO: Funcionários ativos (usuários com nível > 1 que fizeram login recentemente)
$sql_funcionarios_ativos = "SELECT COUNT(DISTINCT u.id) as total 
                           FROM users u 
                           WHERE u.class_id > 1 
                           AND u.id IN (SELECT DISTINCT id_cliente FROM pedido WHERE DATE(horario) BETWEEN ? AND ?)";
$stmt = mysqli_prepare($conn, $sql_funcionarios_ativos);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$funcionarios_ativos = mysqli_fetch_assoc($result)['total'] ?? 0;

$sql_ticket_medio = "SELECT AVG(valor) as medio FROM pedido WHERE DATE(horario) BETWEEN ? AND ?";
$stmt = mysqli_prepare($conn, $sql_ticket_medio);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ticket_medio = mysqli_fetch_assoc($result)['medio'] ?? 0;

// Buscar pedidos para a tabela
$sql_pedidos = "SELECT p.*, u.nome as cliente_nome, e.nome as estado_nome 
                FROM pedido p 
                LEFT JOIN users u ON p.id_cliente = u.id 
                LEFT JOIN estados e ON p.id_estado = e.id 
                WHERE DATE(p.horario) BETWEEN ? AND ?";

if (!empty($filtro_estado)) {
    $sql_pedidos .= " AND p.id_estado = ?";
}

$sql_pedidos .= " ORDER BY p.horario DESC LIMIT 100";

$stmt = mysqli_prepare($conn, $sql_pedidos);
if (!empty($filtro_estado)) {
    mysqli_stmt_bind_param($stmt, "ssi", $data_inicio, $data_fim, $filtro_estado);
} else {
    mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
}
mysqli_stmt_execute($stmt);
$result_pedidos = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($result_pedidos, MYSQLI_ASSOC);

// Buscar estados para o filtro
$sql_estados = "SELECT * FROM estados ORDER BY id";
$result_estados = mysqli_query($conn, $sql_estados);
$estados = mysqli_fetch_all($result_estados, MYSQLI_ASSOC);

// Buscar produtos mais vendidos
$sql_produtos_populares = "
    SELECT i.nome, COUNT(pi.id_item) as quantidade, SUM(i.valor) as total
    FROM pedido_itens pi
    LEFT JOIN itens i ON pi.id_item = i.id
    LEFT JOIN pedido p ON pi.id_pedido = p.id
    WHERE DATE(p.horario) BETWEEN ? AND ?
    GROUP BY i.nome
    ORDER BY quantidade DESC
    LIMIT 10
";

$stmt = mysqli_prepare($conn, $sql_produtos_populares);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result_populares = mysqli_stmt_get_result($stmt);
$produtos_populares = mysqli_fetch_all($result_populares, MYSQLI_ASSOC);

// NOVO: Ingredientes mais utilizados (adicionais + pizzas personalizadas)
$sql_ingredientes_populares = "
    SELECT 
        ing.nome,
        COUNT(ii.id_ingrediente) as quantidade_utilizada,
        SUM(ing.preco) as total_gerado,
        COUNT(DISTINCT pi.id_pedido) as pedidos_com_ingrediente
    FROM ingredientes_itens ii
    LEFT JOIN ingredientes ing ON ii.id_ingrediente = ing.id
    LEFT JOIN itens i ON ii.id_item = i.id
    LEFT JOIN pedido_itens pi ON i.id = pi.id_item
    LEFT JOIN pedido p ON pi.id_pedido = p.id
    WHERE DATE(p.horario) BETWEEN ? AND ?
    GROUP BY ing.id, ing.nome
    ORDER BY quantidade_utilizada DESC
    LIMIT 10
";

$stmt = mysqli_prepare($conn, $sql_ingredientes_populares);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result_ingredientes = mysqli_stmt_get_result($stmt);
$ingredientes_populares = mysqli_fetch_all($result_ingredientes, MYSQLI_ASSOC);

// Buscar vendas por dia (para gráfico)
$sql_vendas_dia = "
    SELECT DATE(horario) as data, COUNT(*) as pedidos, SUM(valor) as total
    FROM pedido 
    WHERE DATE(horario) BETWEEN ? AND ?
    GROUP BY DATE(horario)
    ORDER BY data
";

$stmt = mysqli_prepare($conn, $sql_vendas_dia);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$result_vendas_dia = mysqli_stmt_get_result($stmt);
$vendas_por_dia = mysqli_fetch_all($result_vendas_dia, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="shortcut icon" href="../assets/funcionario.png" />

    <!-- Fontes Oswald, Jaro e Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap" rel="stylesheet">

    <!-- Icones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/18b2c31938.js" crossorigin="anonymous"></script>

    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../assets/css/funcionario.css">
</head>

<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1><i class="fas fa-chart-bar"></i> Relatórios e Estatísticas</h1>
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

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Relatórios e Estatísticas</h1>
                    <p>Analise o desempenho e visualize métricas da pizzaria</p>
                </div>
            </div>

            <!-- Filtros -->
            <section class="filters-section">
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label for="data_inicio">Data Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio"
                            value="<?php echo htmlspecialchars($data_inicio); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="data_fim">Data Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim"
                            value="<?php echo htmlspecialchars($data_fim); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="estado">Status do Pedido</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Todos os status</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?php echo $estado['id']; ?>"
                                    <?php echo $filtro_estado == $estado['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($estado['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Aplicar Filtros
                        </button>
                        <a href="relatorios.php" class="btn btn-warning">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                        <button type="button" class="btn btn-success" id="btn-exportar">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                    </div>
                </form>
            </section>

            <!-- Cards de Métricas -->
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number">R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></span>
                    <span class="stat-label">Total em Vendas</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $total_pedidos; ?></span>
                    <span class="stat-label">Pedidos Realizados</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $clientes_ativos; ?></span>
                    <span class="stat-label">Clientes Ativos</span>
                </div>
                <div class="stat-card warning">
                    <span class="stat-number"><?php echo $funcionarios_ativos; ?></span>
                    <span class="stat-label">Funcionários Ativos</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></span>
                    <span class="stat-label">Ticket Médio</span>
                </div>
            </div>
            <div class="admin-cards-container">
                <div class="admin-sections">
                    <!-- Gráfico de Vendas -->
                    <section class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-chart-line"></i> Vendas por Período</h2>
                        </div>
                        <div class="section-content">
                            <div class="chart-container" style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 800px;">
                                <canvas id="vendasChart" height="300"></canvas>
                            </div>
                        </div>
                    </section>

                    <!-- Produtos e Ingredientes Mais Vendidos -->
                    <section class="section-card">
                        <div class="section-header" style="display: flex; flex-direction: row; justify-content: space-between; padding: 11px 22px 11px 22px;">
                            <h2><i class="fas fa-star"></i>Produtos e Ingredientes Favoritos</h2>
                            <div class="tabs-header">
                                <button class="tab-btn active" data-tab="produtos"><i class="fas fa-pizza-slice"></i></button>
                                <button class="tab-btn" data-tab="ingredientes"><i class="fas fa-carrot"></i></button>
                            </div>
                        </div>

                        <div class="tabs-container">
                            <div class="tab-content active" id="tab-produtos">
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Quantidade</th>
                                                <th>Total Vendido</th>
                                                <th>Percentual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_geral_produtos = array_sum(array_column($produtos_populares, 'total'));
                                            foreach ($produtos_populares as $produto):
                                                $percentual = $total_geral_produtos > 0 ? ($produto['total'] / $total_geral_produtos) * 100 : 0;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="produto-info">
                                                            <strong><?php echo htmlspecialchars($produto['nome']); ?></strong>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $produto['quantidade']; ?> un.</td>
                                                    <td>R$ <?php echo number_format($produto['total'], 2, ',', '.'); ?></td>
                                                    <td>
                                                        <div class="progress-bar-container">
                                                            <div class="progress-bar" style="width: <?php echo $percentual; ?>%"></div>
                                                            <span class="progress-text"><?php echo number_format($percentual, 1); ?>%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <?php if (empty($produtos_populares)): ?>
                                                <tr>
                                                    <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                                        Nenhum produto vendido no período
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-content" id="tab-ingredientes">
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Ingrediente</th>
                                                <th>Vezes Utilizado</th>
                                                <th>Pedidos com Ingrediente</th>
                                                <th>Total Gerado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_geral_ingredientes = array_sum(array_column($ingredientes_populares, 'total_gerado'));
                                            foreach ($ingredientes_populares as $ingrediente):
                                                $percentual_uso = $ingredientes_populares[0]['quantidade_utilizada'] > 0 ?
                                                    ($ingrediente['quantidade_utilizada'] / $ingredientes_populares[0]['quantidade_utilizada']) * 100 : 0;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="ingrediente-info">
                                                            <strong><?php echo htmlspecialchars($ingrediente['nome']); ?></strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php echo $ingrediente['quantidade_utilizada']; ?>x
                                                        <div class="mini-progress">
                                                            <div class="mini-progress-bar" style="width: <?php echo $percentual_uso; ?>%"></div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $ingrediente['pedidos_com_ingrediente']; ?> pedidos</td>
                                                    <td>R$ <?php echo number_format($ingrediente['total_gerado'], 2, ',', '.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <?php if (empty($ingredientes_populares)): ?>
                                                <tr>
                                                    <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                                        Nenhum ingrediente utilizado no período
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Últimos Pedidos -->
                <section class="section-card">
                    <div class="section-header" style="display: flex; flex-direction: row; gap: 15px;">
                        <h2><i class="fas fa-history"></i> Últimos Pedidos</h2>
                        <span class="badge"><?php echo count($pedidos); ?> pedidos</span>
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
                                        <th>Data/Hora</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($pedidos)): ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                                Nenhum pedido encontrado no período selecionado
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($pedidos as $pedido): ?>
                                            <tr>
                                                <td>#<?php echo $pedido['id']; ?></td>
                                                <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                                                <td>R$ <?php echo number_format($pedido['valor'], 2, ',', '.'); ?></td>
                                                <td>
                                                    <span class="status-badge 
                                                    <?php
                                                    switch ($pedido['id_estado']) {
                                                        case 1:
                                                            echo 'status-inactive';
                                                            break; // Em processamento
                                                        case 2:
                                                            echo 'status-warning';
                                                            break; // Preparando
                                                        case 3:
                                                            echo 'status-info';
                                                            break; // Enviado
                                                        case 4:
                                                            echo 'status-active';
                                                            break; // Entregue
                                                        case 5:
                                                            echo 'status-error';
                                                            break; // Cancelado
                                                        default:
                                                            echo 'status-inactive';
                                                    }
                                                    ?>">
                                                        <?php echo htmlspecialchars($pedido['estado_nome']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['horario'])); ?></td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="pedidos.php#pedido-<?php echo $pedido['id']; ?>"
                                                            class="btn btn-primary btn-sm" title="Ver Detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </main>

    </div>

    <script>
        // Dados para o gráfico
        const vendasPorDia = <?php echo json_encode($vendas_por_dia); ?>;

        // Preparar dados para o Chart.js
        const labels = vendasPorDia.map(item => {
            const date = new Date(item.data);
            return date.toLocaleDateString('pt-BR');
        });

        const dadosVendas = vendasPorDia.map(item => parseFloat(item.total));
        const dadosPedidos = vendasPorDia.map(item => parseInt(item.pedidos));

        // Criar gráfico
        const ctx = document.getElementById('vendasChart').getContext('2d');
        const vendasChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Valor em Vendas (R$)',
                        data: dadosVendas,
                        borderColor: '#FFA500',
                        backgroundColor: 'rgba(255, 165, 0, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Número de Pedidos',
                        data: dadosPedidos,
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'var(--light-color)'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'var(--light-color)',
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2
                                });
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            color: 'var(--light-color)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'var(--light-color)'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#FFA500',
                        bodyColor: '#E0E0E0',
                        borderColor: '#FFA500',
                        borderWidth: 1
                    }
                }
            }
        });

        // Exportar relatório
        document.getElementById('btn-exportar').addEventListener('click', function() {
            const dataInicio = document.getElementById('data_inicio').value;
            const dataFim = document.getElementById('data_fim').value;
            const estado = document.getElementById('estado').value;

            const url = `exportar_relatorio.php?data_inicio=${dataInicio}&data_fim=${dataFim}&estado=${estado}`;
            window.open(url, '_blank');
        });

        // Validação de datas
        document.getElementById('data_fim').addEventListener('change', function() {
            const dataInicio = new Date(document.getElementById('data_inicio').value);
            const dataFim = new Date(this.value);

            if (dataFim < dataInicio) {
                alert('A data final não pode ser anterior à data inicial!');
                this.value = document.getElementById('data_inicio').value;
            }

            // Limitar a 30 dias para performance
            const diffTime = Math.abs(dataFim - dataInicio);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 90) {
                alert('O período máximo permitido é de 90 dias para melhor performance.');
                this.value = new Date(dataInicio.getTime() + (89 * 24 * 60 * 60 * 1000)).toISOString().split('T')[0];
            }
        });

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

        // Sistema de Tabs para Produtos/Ingredientes
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remover classe active de todos os botões e conteúdos
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Adicionar classe active ao botão e conteúdo clicado
                    this.classList.add('active');
                    document.getElementById(`tab-${targetTab}`).classList.add('active');
                });
            });

            // Ativar menu atual
            const currentPage = window.location.pathname.split('/').pop();
            const menuLinks = document.querySelectorAll('.admin-menu a');

            menuLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
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
    </script>
    </body>
    </html>