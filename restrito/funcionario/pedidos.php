<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 2+ para gerenciar pedidos)
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Processar atualização de status
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_status'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $novo_status = intval($_POST['novo_status']);

    // Buscar dados atuais antes da atualização
    $sql_antigo = "SELECT p.*, e.nome as estado_nome 
                   FROM pedido p 
                   LEFT JOIN estados e ON p.id_estado = e.id 
                   WHERE p.id = ?";
    $stmt_antigo = mysqli_prepare($conn, $sql_antigo);
    mysqli_stmt_bind_param($stmt_antigo, "i", $pedido_id);
    mysqli_stmt_execute($stmt_antigo);
    $result_antigo = mysqli_stmt_get_result($stmt_antigo);
    $dados_antigos = mysqli_fetch_assoc($result_antigo);

    $sql = "UPDATE pedido SET id_estado = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $novo_status, $pedido_id);

    if (mysqli_stmt_execute($stmt)) {
        // Buscar nome do novo estado para o log
        $sql_novo_estado = "SELECT nome FROM estados WHERE id = ?";
        $stmt_novo = mysqli_prepare($conn, $sql_novo_estado);
        mysqli_stmt_bind_param($stmt_novo, "i", $novo_status);
        mysqli_stmt_execute($stmt_novo);
        $result_novo = mysqli_stmt_get_result($stmt_novo);
        $estado_novo = mysqli_fetch_assoc($result_novo);

        // REGISTRAR LOG DE ATUALIZAÇÃO
        $dados_anteriores_log = [
            'id_estado' => $dados_antigos['id_estado'],
            'estado_nome' => $dados_antigos['estado_nome'],
            'valor' => $dados_antigos['valor'],
            'id_cliente' => $dados_antigos['id_cliente']
        ];
        
        $dados_novos_log = [
            'id_estado' => $novo_status,
            'estado_nome' => $estado_novo['nome'],
            'valor' => $dados_antigos['valor'], // Mantém o mesmo
            'id_cliente' => $dados_antigos['id_cliente'] // Mantém o mesmo
        ];
        
        registrarLog($conn, 'pedido', $pedido_id, 'UPDATE', 
                    formatarDadosParaLog($dados_anteriores_log), 
                    formatarDadosParaLog($dados_novos_log));

        $mensagem = "Status do pedido #{$pedido_id} atualizado com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao atualizar status: " . mysqli_error($conn);
        $tipo_mensagem = "error";
    }
}

// Buscar estados disponíveis
$sql_estados = "SELECT * FROM estados ORDER BY id";
$result_estados = mysqli_query($conn, $sql_estados);
$estados = mysqli_fetch_all($result_estados, MYSQLI_ASSOC);

// Buscar pedidos em andamento (estados 1-3) ordenados por prioridade (mais antigo primeiro)
$sql_pedidos_ativos = "
    SELECT p.*, 
           u.nome as cliente_nome, 
           u.telefone as cliente_telefone,
           u.email as cliente_email,
           e.nome as estado_nome,
           f.nome as forma_pagamento,
           en.endereco as endereco_entrega,
           te.tipo as tipo_entrega
    FROM pedido p
    LEFT JOIN users u ON p.id_cliente = u.id
    LEFT JOIN estados e ON p.id_estado = e.id
    LEFT JOIN formapag f ON p.id_formapag = f.id
    LEFT JOIN entrega en ON p.id_entrega = en.id
    LEFT JOIN tipo_entrega te ON en.id_tipo = te.id
    WHERE p.id_estado IN (1, 2, 3)  -- Em Processamento, Preparando, Enviado
    ORDER BY p.horario ASC
";

$result_pedidos_ativos = mysqli_query($conn, $sql_pedidos_ativos);
$pedidos_ativos = mysqli_fetch_all($result_pedidos_ativos, MYSQLI_ASSOC);

// Buscar pedidos finalizados (estados 4-5)
$sql_pedidos_finalizados = "
    SELECT p.*, 
           u.nome as cliente_nome, 
           u.telefone as cliente_telefone,
           u.email as cliente_email,
           e.nome as estado_nome,
           f.nome as forma_pagamento,
           en.endereco as endereco_entrega,
           te.tipo as tipo_entrega
    FROM pedido p
    LEFT JOIN users u ON p.id_cliente = u.id
    LEFT JOIN estados e ON p.id_estado = e.id
    LEFT JOIN formapag f ON p.id_formapag = f.id
    LEFT JOIN entrega en ON p.id_entrega = en.id
    LEFT JOIN tipo_entrega te ON en.id_tipo = te.id
    WHERE p.id_estado IN (4, 5)  -- Entregue, Cancelado
    ORDER BY p.horario DESC
";

$result_pedidos_finalizados = mysqli_query($conn, $sql_pedidos_finalizados);
$pedidos_finalizados = mysqli_fetch_all($result_pedidos_finalizados, MYSQLI_ASSOC);

// Função para buscar itens de um pedido
function buscarItensPedido($conn, $pedido_id)
{
    $sql_itens = "
        SELECT i.*, 
               GROUP_CONCAT(DISTINCT ing.nome SEPARATOR ', ') as ingredientes_adicionais
        FROM pedido_itens pi
        LEFT JOIN itens i ON pi.id_item = i.id
        LEFT JOIN ingredientes_itens ii ON i.id = ii.id_item
        LEFT JOIN ingredientes ing ON ii.id_ingrediente = ing.id
        WHERE pi.id_pedido = ?
        GROUP BY i.id
    ";

    $stmt = mysqli_prepare($conn, $sql_itens);
    mysqli_stmt_bind_param($stmt, "i", $pedido_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Contadores para estatísticas
$total_ativos = count($pedidos_ativos);
$total_finalizados = count($pedidos_finalizados);
$total_hoje = 0;

// Calcular pedidos de hoje
$sql_hoje = "SELECT COUNT(*) as total FROM pedido WHERE DATE(horario) = CURDATE()";
$result_hoje = mysqli_query($conn, $sql_hoje);
if ($result_hoje) {
    $total_hoje = mysqli_fetch_assoc($result_hoje)['total'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pedidos</title>
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
    <link rel="stylesheet" href="../assets/css/funcionario.css">
</head>

<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1><i class="fas fa-shopping-cart"></i> Gerenciar Pedidos</h1>
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
                <li><a href="#" class="active"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="categorias.php"><i class="fas fa-tag"></i> Categorias</a></li>
                <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="logs_auditoria.php"><i class="fas fa-clipboard-list"></i> Logs de Auditoria</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Gerenciar Pedidos</h1>
                    <p>Acompanhe e gerencie todos os pedidos do sistema</p>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo $total_ativos; ?></span>
                    <span class="stat-label">Pedidos Ativos</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $total_hoje; ?></span>
                    <span class="stat-label">Pedidos Hoje</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo $total_finalizados; ?></span>
                    <span class="stat-label">Pedidos Finalizados</span>
                </div>
            </div>

            <!-- Mensagens -->
            <?php if ($mensagem): ?>
                <div class="message <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <!-- Abas de Navegação -->
            <div class="pedidos-tabs">
                <button class="tab-btn active" data-tab="ativos">Pedidos em Andamento (<?php echo $total_ativos; ?>)</button>
                <button class="tab-btn" data-tab="finalizados">Pedidos Finalizados (<?php echo $total_finalizados; ?>)</button>
            </div>

            <!-- Conteúdo da Aba de Pedidos Ativos -->
            <div id="tab-ativos" class="tab-content active">
                <div class="pedidos-list">
                    <?php if (empty($pedidos_ativos)): ?>
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <h3>Nenhum pedido em andamento</h3>
                            <p>Todos os pedidos foram finalizados ou cancelados.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($pedidos_ativos as $pedido): ?>
                            <?php
                            $itens_pedido = buscarItensPedido($conn, $pedido['id']);
                            $horario = date('H:i', strtotime($pedido['horario']));
                            $data = date('d/m/Y', strtotime($pedido['horario']));
                            ?>
                            <div class="pedido-card" data-pedido-id="<?php echo $pedido['id']; ?>">
                                <div class="pedido-header minimized" onclick="togglePedido(this)">
                                    <div class="pedido-title">
                                        <div class="pedido-info-basic">
                                            <span class="pedido-id">PEDIDO #<?php echo $pedido['id']; ?></span>
                                            <span class="pedido-cliente"><?php echo htmlspecialchars($pedido['cliente_nome']); ?></span>
                                            <span class="pedido-hora"><?php echo $data . ' às ' . $horario; ?></span>
                                        </div>
                                        <button class="toggle-btn">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="pedido-content">
                                    <div class="pedido-container">
                                        <div class="container-top">
                                            <!-- Itens do Pedido -->
                                            <div class="itens-container">
                                                <div class="itens-header">
                                                    <h2 class="section-title">Itens</h2>
                                                </div>
                                                <div class="itens-list">
                                                    <?php foreach ($itens_pedido as $item): ?>
                                                        <div class="item-card">
                                                            <div class="item-info">
                                                                <div class="item-details">
                                                                    <h3 class="item-qtd-nome"><?php echo $item['quantidade']; ?> × <?php echo htmlspecialchars($item['nome']); ?></h3>
                                                                    <?php if (!empty($item['descricao'])): ?>
                                                                        <div class="item-desc"><?php echo htmlspecialchars($item['descricao']); ?></div>
                                                                    <?php endif; ?>
                                                                    <div class="item-preco">R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></div>
                                                                </div>
                                                                <div class="item-extra">
                                                                    <?php if (!empty($item['observacao'])): ?>
                                                                        <div class="observacao-container">
                                                                            <p class="observacao"><?php echo htmlspecialchars($item['observacao']); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($item['ingredientes_adicionais'])): ?>
                                                                        <div class="adicionais">
                                                                            <h4>Adicionais:</h4>
                                                                            <ul>
                                                                                <?php
                                                                                $adicionais = explode(', ', $item['ingredientes_adicionais']);
                                                                                foreach ($adicionais as $adicional):
                                                                                ?>
                                                                                    <li><?php echo htmlspecialchars($adicional); ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <!-- Cliente e Entrega -->
                                            <div class="cliente-entrega">
                                                <!-- Informações do Cliente -->
                                                <div class="info-container">
                                                    <div class="info-header">
                                                        <h2 class="section-title">Cliente #<?php echo $pedido['id_cliente']; ?></h2>
                                                    </div>
                                                    <div class="info-content">
                                                        <div class="cliente-info-grid">
                                                            <div class="info-row">
                                                                <span class="info-label">Nome</span>
                                                                <div class="info-value"><?php echo htmlspecialchars($pedido['cliente_nome']); ?></div>
                                                            </div>
                                                            <div class="info-row">
                                                                <span class="info-label">Tel.</span>
                                                                <div class="info-value"><?php echo htmlspecialchars($pedido['cliente_telefone']); ?></div>
                                                            </div>
                                                            <div class="info-row">
                                                                <span class="info-label">Login</span>
                                                                <div class="info-value"><?php echo htmlspecialchars($pedido['cliente_email']); ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Local de Entrega -->
                                                <div class="info-container">
                                                    <div class="info-header">
                                                        <h2 class="section-title">Local de Entrega</h2>
                                                    </div>
                                                    <div class="info-content">
                                                        <div class="entrega-content">
                                                            <h3 class="tipo-entrega"><?php echo htmlspecialchars($pedido['tipo_entrega']); ?></h3>
                                                            <div class="local-entrega-container">
                                                                <div class="local-entrega">
                                                                    <?php echo htmlspecialchars($pedido['endereco_entrega']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rodapé com Preço, Pagamento e Status -->
                                        <div class="container-bottom">
                                            <div class="pagamento-info">
                                                <div class="preco-container">
                                                    <h2 class="preco-label">R$ <?php echo number_format($pedido['valor'], 2, ',', '.'); ?></h2>
                                                </div>
                                                <div class="pagamento-container">
                                                    <h2 class="pagamento-label"><?php echo htmlspecialchars($pedido['forma_pagamento']); ?></h2>
                                                </div>
                                            </div>
                                            <div class="status-actions">
                                                <div class="status-container">
                                                    <h2 class="status-label">Status</h2>
                                                    <span class="status-value"><?php echo htmlspecialchars($pedido['estado_nome']); ?></span>
                                                </div>
                                                <button class="btn-atualizar" onclick="abrirModalStatus(<?php echo $pedido['id']; ?>, <?php echo $pedido['id_estado']; ?>)">
                                                    Atualizar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Conteúdo da Aba de Pedidos Finalizados -->
            <div id="tab-finalizados" class="tab-content">
                <div class="pedidos-list">
                    <?php if (empty($pedidos_finalizados)): ?>
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <h3>Nenhum pedido finalizado</h3>
                            <p>Os pedidos finalizados aparecerão aqui.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($pedidos_finalizados as $pedido): ?>
                            <?php
                            $itens_pedido = buscarItensPedido($conn, $pedido['id']); // ← AQUI ESTAVA FALTANDO!
                            $horario = date('H:i', strtotime($pedido['horario']));
                            $data = date('d/m/Y', strtotime($pedido['horario']));
                            ?>
                            <div class="pedido-card" data-pedido-id="<?php echo $pedido['id']; ?>">
                                <div class="pedido-header minimized" onclick="togglePedido(this)">
                                    <div class="pedido-title">
                                        <div class="pedido-info-basic">
                                            <span class="pedido-id">PEDIDO #<?php echo $pedido['id']; ?></span>
                                            <span class="pedido-cliente"><?php echo htmlspecialchars($pedido['cliente_nome']); ?></span>
                                            <span class="pedido-hora"><?php echo $data . ' às ' . $horario; ?></span>
                                        </div>
                                        <button class="toggle-btn">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="pedido-content">
                                    <div class="pedido-container">
                                        <div class="container-top">
                                            <!-- Itens do Pedido -->
                                            <div class="itens-container">
                                                <div class="itens-header">
                                                    <h2 class="section-title">Itens</h2>
                                                </div>
                                                <div class="itens-list">
                                                    <?php foreach ($itens_pedido as $item): ?>
                                                        <div class="item-card">
                                                            <div class="item-info">
                                                                <div class="item-details">
                                                                    <h3 class="item-qtd-nome"><?php echo $item['quantidade']; ?> × <?php echo htmlspecialchars($item['nome']); ?></h3>
                                                                    <?php if (!empty($item['descricao'])): ?>
                                                                        <div class="item-desc"><?php echo htmlspecialchars($item['descricao']); ?></div>
                                                                    <?php endif; ?>
                                                                    <div class="item-preco">R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></div>
                                                                </div>
                                                                <div class="item-extra">
                                                                    <?php if (!empty($item['observacao'])): ?>
                                                                        <div class="observacao-container">
                                                                            <p class="observacao"><?php echo htmlspecialchars($item['observacao']); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($item['ingredientes_adicionais'])): ?>
                                                                        <div class="adicionais">
                                                                            <h4>Adicionais:</h4>
                                                                            <ul>
                                                                                <?php
                                                                                $adicionais = explode(', ', $item['ingredientes_adicionais']);
                                                                                foreach ($adicionais as $adicional):
                                                                                ?>
                                                                                    <li><?php echo htmlspecialchars($adicional); ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <!-- Cliente e Entrega -->
                                            <div class="cliente-entrega">
                                                <!-- Informações do Cliente -->
                                                <div class="info-container">
                                                    <div class="info-header">
                                                        <h2 class="section-title">Cliente #<?php echo $pedido['id_cliente']; ?></h2>
                                                    </div>
                                                    <div class="info-content">
                                                        <div class="cliente-info-grid">
                                                            <div class="info-row">
                                                                <span class="info-label">Nome</span>
                                                                <div class="info-value"><?php echo htmlspecialchars($pedido['cliente_nome']); ?></div>
                                                            </div>
                                                            <div class="info-row">
                                                                <span class="info-label">Tel.</span>
                                                                <div class="info-value"><?php echo htmlspecialchars($pedido['cliente_telefone']); ?></div>
                                                            </div>
                                                            <div class="info-row">
                                                                <span class="info-label">Login</span>
                                                                <div class="info-value"><?php echo htmlspecialchars($pedido['cliente_email']); ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Local de Entrega -->
                                                <div class="info-container">
                                                    <div class="info-header">
                                                        <h2 class="section-title">Local de Entrega</h2>
                                                    </div>
                                                    <div class="info-content">
                                                        <div class="entrega-content">
                                                            <h3 class="tipo-entrega"><?php echo htmlspecialchars($pedido['tipo_entrega']); ?></h3>
                                                            <div class="local-entrega-container">
                                                                <div class="local-entrega">
                                                                    <?php echo htmlspecialchars($pedido['endereco_entrega']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rodapé com Preço, Pagamento e Status -->
                                        <div class="container-bottom">
                                            <div class="pagamento-info">
                                                <div class="preco-container">
                                                    <h2 class="preco-label">R$ <?php echo number_format($pedido['valor'], 2, ',', '.'); ?></h2>
                                                </div>
                                                <div class="pagamento-container">
                                                    <h2 class="pagamento-label"><?php echo htmlspecialchars($pedido['forma_pagamento']); ?></h2>
                                                </div>
                                            </div>
                                            <div class="status-actions">
                                                <div class="status-container">
                                                    <h2 class="status-label">Status</h2>
                                                    <span class="status-value"><?php echo htmlspecialchars($pedido['estado_nome']); ?></span>
                                                </div>
                                                <button class="btn-atualizar" style="background-color: #555;" onclick="abrirModalStatus(<?php echo $pedido['id']; ?>, <?php echo $pedido['id_estado']; ?>)">
                                                    Atualizar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para Atualização de Status -->
    <div id="statusModal" class="status-modal">
        <div class="status-modal-content">
            <div class="status-modal-header">
                <h2>Atualizar Status do Pedido</h2>
                <span class="close" onclick="fecharModalStatus()">&times;</span>
            </div>
            <div class="status-modal-body">
                <form method="POST" id="statusForm">
                    <input type="hidden" name="pedido_id" id="modal_pedido_id">

                    <div class="status-options" id="statusOptions">
                        <!-- As opções de status serão preenchidas via JavaScript -->
                    </div>

                    <div style="display: flex; justify-content: center; align-items: center;">
                        <button type="submit" name="atualizar_status" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sistema de Abas
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

        // Função para expandir/recolher pedidos
        function togglePedido(header) {
            const pedidoCard = header.closest('.pedido-card');
            const content = pedidoCard.querySelector('.pedido-content');
            const toggleBtn = pedidoCard.querySelector('.toggle-btn');
            const icon = toggleBtn.querySelector('i');

            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                header.classList.add('minimized');
                toggleBtn.classList.remove('rotated');
                icon.className = 'fas fa-chevron-down';
            } else {
                content.classList.add('expanded');
                header.classList.remove('minimized');
                toggleBtn.classList.add('rotated');
                icon.className = 'fas fa-chevron-up';
            }
        }

        // Modal de Status
        function abrirModalStatus(pedidoId, statusAtual) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const optionsContainer = document.getElementById('statusOptions');

            // Preencher ID do pedido
            document.getElementById('modal_pedido_id').value = pedidoId;

            // Limpar opções anteriores
            optionsContainer.innerHTML = '';

            // Adicionar opções de status (baseado nos estados do banco)
            const statusOptions = [{
                    id: 1,
                    nome: 'Em Processamento'
                },
                {
                    id: 2,
                    nome: 'Preparando'
                },
                {
                    id: 3,
                    nome: 'Enviado'
                },
                {
                    id: 4,
                    nome: 'Entregue'
                },
                {
                    id: 5,
                    nome: 'Cancelado'
                }
            ];

            statusOptions.forEach(status => {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'status-option';
                optionDiv.innerHTML = `
                    <input type="radio" id="status_${status.id}" name="novo_status" value="${status.id}" ${status.id == statusAtual ? 'checked' : ''}>
                    <label for="status_${status.id}">${status.nome}</label>
                `;
                optionsContainer.appendChild(optionDiv);
            });

            // Mostrar modal
            modal.style.display = 'block';
        }

        function fecharModalStatus() {
            document.getElementById('statusModal').style.display = 'none';
        }

        // Fechar modal ao clicar fora
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                fecharModalStatus();
            }
        });

        // Auto-expandir pedidos ativos (opcional)
        document.addEventListener('DOMContentLoaded', function() {
            // Expandir automaticamente o primeiro pedido ativo
            const primeiroPedidoAtivo = document.querySelector('#tab-ativos .pedido-card');
            if (primeiroPedidoAtivo) {
                const header = primeiroPedidoAtivo.querySelector('.pedido-header');
                togglePedido(header);
            }
        });
    </script>
</body>

</html>