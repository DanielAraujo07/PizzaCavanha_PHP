<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 2+ para gerenciar ingredientes)
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Processar formulários
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar_ingrediente'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $preco = floatval($_POST['preco']);
        $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
        $id_tipo = intval($_POST['id_tipo']);
        $disponivel = isset($_POST['disponivel']) ? 1 : 0;

        $sql = "INSERT INTO ingredientes (nome, preco, imagem, id_tipo, disponivel) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sdsii", $nome, $preco, $imagem, $id_tipo, $disponivel);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Ingrediente adicionado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao adicionar ingrediente: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Atualizar ingrediente
    if (isset($_POST['atualizar_ingrediente'])) {
        $id = intval($_POST['id']);
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $preco = floatval($_POST['preco']);
        $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
        $id_tipo = intval($_POST['id_tipo']);
        $disponivel = isset($_POST['disponivel']) ? 1 : 0;

        $sql = "UPDATE ingredientes SET nome=?, preco=?, imagem=?, id_tipo=?, disponivel=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sdsiii", $nome, $preco, $imagem, $id_tipo, $disponivel, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Ingrediente atualizado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar ingrediente: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Excluir ingrediente
    if (isset($_POST['excluir_ingrediente'])) {
        $id = intval($_POST['id']);
        
        $sql = "DELETE FROM ingredientes WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Ingrediente excluído com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao excluir ingrediente: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }
}

// Buscar ingredientes (com filtro de busca)
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($conn, $_GET['busca']) : '';
$filtro_tipo = isset($_GET['tipo']) ? intval($_GET['tipo']) : '';

$sql_ingredientes = "SELECT i.*, t.nome as tipo_nome 
                     FROM ingredientes i 
                     LEFT JOIN tipos_categoria t ON i.id_tipo = t.id 
                     WHERE 1=1";

if (!empty($busca)) {
    $sql_ingredientes .= " AND i.nome LIKE '%$busca%'";
}

if (!empty($filtro_tipo)) {
    $sql_ingredientes .= " AND i.id_tipo = $filtro_tipo";
}

$sql_ingredientes .= " ORDER BY i.nome";
$result_ingredientes = mysqli_query($conn, $sql_ingredientes);
$ingredientes = mysqli_fetch_all($result_ingredientes, MYSQLI_ASSOC);

// Buscar tipos de categoria
$sql_tipos = "SELECT * FROM tipos_categoria ORDER BY nome";
$result_tipos = mysqli_query($conn, $sql_tipos);
$tipos = mysqli_fetch_all($result_tipos, MYSQLI_ASSOC);

// Contar ingredientes ativos
$sql_ativos = "SELECT COUNT(*) as total FROM ingredientes WHERE disponivel = 1";
$result_ativos = mysqli_query($conn, $sql_ativos);
$ativos = mysqli_fetch_assoc($result_ativos)['total'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Ingredientes</title>
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
    <link rel="stylesheet" href="../assets/css/funcionario.css">
</head>

<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1><i class="fa-solid fa-carrot"></i> Gerenciar Ingredientes</h1>
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
                <li><a href="#" class="active"><i class="fas fa-carrot"></i> Ingredientes</a></li>
                <li><a href="pedidos.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <li><a href="categorias.php"><i class="fas fa-tags"></i> Categorias</a></li>
                <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Gerenciar Ingredientes</h1>
                    <p>Adicione, edite ou remova ingredientes para as pizzas</p>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($ingredientes); ?></span>
                    <span class="stat-label">Total de Ingredientes</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $ativos; ?></span>
                    <span class="stat-label">Ingredientes Ativos</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($tipos); ?></span>
                    <span class="stat-label">Tipos Disponíveis</span>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <section class="filters-section">
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label for="busca">Buscar Ingredientes</label>
                        <input type="text" class="form-control" id="busca" name="busca" 
                               value="<?php echo htmlspecialchars($busca); ?>" 
                               placeholder="Digite o nome do ingrediente...">
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo">Filtrar por Tipo</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="">Todos os tipos</option>
                            <?php foreach ($tipos as $tipo): ?>
                                <option value="<?php echo $tipo['id']; ?>" 
                                    <?php echo $filtro_tipo == $tipo['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="ingredientes.php" class="btn btn-warning">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </div>
                </form>
            </section>

            <!-- Mensagens -->
            <?php if ($mensagem): ?>
                <div class="message <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <div class="admin-sections">
                <!-- Formulário de Adicionar/Editar Ingrediente -->
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-plus-circle"></i> 
                            <span id="formTitle">Adicionar Novo Ingrediente</span>
                        </h2>
                    </div>
                    <div class="section-content">
                        <form method="POST" id="ingredienteForm">
                            <input type="hidden" name="id" id="ingrediente_id">
                            
                            <div class="form-group">
                                <label for="nome">Nome do Ingrediente</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>

                            <div class="form-group">
                                <label for="preco">Preço Adicional (R$)</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="imagem">URL da Imagem</label>
                                <input type="url" class="form-control" id="imagem" name="imagem" placeholder="https://exemplo.com/imagem.jpg">
                            </div>

                            <div class="form-group">
                                <label for="id_tipo">Tipo de Ingrediente</label>
                                <select class="form-control" id="id_tipo" name="id_tipo" required>
                                    <option value="">Selecione um tipo</option>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>">
                                            <?php echo htmlspecialchars($tipo['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="disponivel" name="disponivel" value="1" checked>
                                    <label for="disponivel">Ingrediente disponível</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="adicionar_ingrediente" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-plus"></i> Adicionar Ingrediente
                                </button>
                                <button type="button" class="btn btn-warning" id="cancelEdit" style="display: none;">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Lista de Ingredientes -->
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-list"></i> Ingredientes Cadastrados</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Preço</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ingredientes as $ingrediente): ?>
                                        <tr>
                                            <td>
                                                <?php if ($ingrediente['imagem']): ?>
                                                    <img src="<?php echo htmlspecialchars($ingrediente['imagem']); ?>" 
                                                         alt="<?php echo htmlspecialchars($ingrediente['nome']); ?>" 
                                                         class="product-image"
                                                         onerror="this.src='../assets/placeholder-ingrediente.png'">
                                                <?php else: ?>
                                                    <img src="../assets/placeholder-ingrediente.png" 
                                                         alt="Sem imagem" 
                                                         class="product-image">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($ingrediente['nome']); ?></td>
                                            <td>R$ <?php echo number_format($ingrediente['preco'], 2, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($ingrediente['tipo_nome']); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $ingrediente['disponivel'] ? 'status-active' : 'status-inactive'; ?>">
                                                    <?php echo $ingrediente['disponivel'] ? 'Ativo' : 'Inativo'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn btn-warning btn-sm" 
                                                            onclick="editarIngrediente(<?php echo $ingrediente['id']; ?>)"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-primary btn-sm" 
                                                            onclick="visualizarIngrediente(<?php echo $ingrediente['id']; ?>)"
                                                            title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="confirmarExclusao(<?php echo $ingrediente['id']; ?>, '<?php echo htmlspecialchars($ingrediente['nome']); ?>')"
                                                            title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
        </main>
    </div>

    <!-- Modal de Visualização -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes do Ingrediente</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Tem certeza que deseja excluir este ingrediente?</p>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="id" id="delete_id">
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" class="btn btn-warning" onclick="fecharModal('deleteModal')">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" name="excluir_ingrediente" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Confirmar Exclusão
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Elementos dos modais
        const viewModal = document.getElementById('viewModal');
        const deleteModal = document.getElementById('deleteModal');
        const closeButtons = document.querySelectorAll('.close');

        // Fechar modais
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                viewModal.style.display = 'none';
                deleteModal.style.display = 'none';
            });
        });

        // Fechar modal ao clicar fora
        window.addEventListener('click', function(event) {
            if (event.target === viewModal) {
                viewModal.style.display = 'none';
            }
            if (event.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });

        // Função auxiliar para fechar modal
        function fecharModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Dados dos ingredientes
        const ingredientesData = <?php echo json_encode($ingredientes); ?>;

        // Função para visualizar ingrediente
        function visualizarIngrediente(id) {
            const ingrediente = ingredientesData.find(i => i.id == id);
            if (ingrediente) {
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = `
                    <div class="product-detail">
                        <div>
                            <img src="${ingrediente.imagem || '../assets/placeholder-ingrediente.png'}" 
                                 alt="${ingrediente.nome}" 
                                 class="product-detail-image"
                                 onerror="this.src='../assets/placeholder-ingrediente.png'">
                        </div>
                        <div class="product-info">
                            <h3>${ingrediente.nome}</h3>
                            <p><span class="info-label">Preço Adicional:</span> R$ ${parseFloat(ingrediente.preco).toFixed(2)}</p>
                            <p><span class="info-label">Tipo:</span> ${ingrediente.tipo_nome}</p>
                            <p><span class="info-label">Status:</span> 
                                <span class="status-badge ${ingrediente.disponivel ? 'status-active' : 'status-inactive'}">
                                    ${ingrediente.disponivel ? 'Ativo' : 'Inativo'}
                                </span>
                            </p>
                            <p><span class="info-label">ID:</span> ${ingrediente.id}</p>
                        </div>
                    </div>
                `;
                viewModal.style.display = 'block';
            }
        }

        // Função para editar ingrediente
        function editarIngrediente(id) {
            const ingrediente = ingredientesData.find(i => i.id == id);
            if (ingrediente) {
                // Preencher formulário com dados do ingrediente
                document.getElementById('ingrediente_id').value = ingrediente.id;
                document.getElementById('nome').value = ingrediente.nome;
                document.getElementById('preco').value = ingrediente.preco;
                document.getElementById('imagem').value = ingrediente.imagem || '';
                document.getElementById('id_tipo').value = ingrediente.id_tipo;
                document.getElementById('disponivel').checked = ingrediente.disponivel == 1;
                
                // Mudar formulário para modo edição
                document.getElementById('formTitle').textContent = 'Editar Ingrediente';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Atualizar Ingrediente';
                document.getElementById('submitBtn').name = 'atualizar_ingrediente';
                document.getElementById('cancelEdit').style.display = 'inline-block';
                
                // Rolagem suave para o formulário
                document.getElementById('ingredienteForm').scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Cancelar edição
        document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('ingredienteForm').reset();
            document.getElementById('ingrediente_id').value = '';
            document.getElementById('formTitle').textContent = 'Adicionar Novo Ingrediente';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus"></i> Adicionar Ingrediente';
            document.getElementById('submitBtn').name = 'adicionar_ingrediente';
            this.style.display = 'none';
        });

        // Função para confirmar exclusão
        function confirmarExclusao(id, nome) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteMessage').textContent = 
                `Tem certeza que deseja excluir o ingrediente "${nome}"? Esta ação não pode ser desfeita.`;
            deleteModal.style.display = 'block';
        }

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