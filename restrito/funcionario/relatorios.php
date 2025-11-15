<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 2+ para acessar relatórios)
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Processar filtros
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : date('Y-m-01');
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : date('Y-m-d');
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
    <title>Relatórios - Pizza do Cavanha</title>
    <link rel="shortcut icon" href="../assets/funcionario.svg" />

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
                <li><a href="produtos.php"><i class="fas fa-pizza-slice"></i> Produtos</a></li>
                <li><a href="ingredientes.php"><i class="fas fa-carrot"></i> Ingredientes</a></li>
                <li><a href="pedidos.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="categorias.php"><i class="fas fa-tags"></i> Categorias</a></li>
                <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <li><a href="#" class="active"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
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
                            <div class="chart-container">
                                <canvas id="vendasChart" height="300"></canvas>
                            </div>
                        </div>
                    </section>

                    <!-- Produtos Mais Vendidos -->
                    <section class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-star"></i> Produtos Mais Vendidos</h2>
                        </div>
                        <div class="section-content">
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
                                        $total_geral = array_sum(array_column($produtos_populares, 'total'));
                                        foreach ($produtos_populares as $produto):
                                            $percentual = $total_geral > 0 ? ($produto['total'] / $total_geral) * 100 : 0;
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                                <td><?php echo $produto['quantidade']; ?></td>
                                                <td>R$ <?php echo number_format($produto['total'], 2, ',', '.'); ?></td>
                                                <td>
                                                    <div class="progress-bar-container">
                                                        <div class="progress-bar" style="width: <?php echo $percentual; ?>%"></div>
                                                        <span class="progress-text"><?php echo number_format($percentual, 1); ?>%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Últimos Pedidos -->
                <section class="section-card">
                    <div class="section-header">
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
                            color: '#E0E0E0'
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
                            color: '#E0E0E0',
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
                            color: '#E0E0E0'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#E0E0E0'
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
    </script>