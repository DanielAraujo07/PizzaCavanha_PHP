<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 2+ para gerenciar produtos)
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Processar formulários
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar_produto'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
        $preco = floatval($_POST['preco']);
        $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
        $id_categoria = intval($_POST['id_categoria']);
        $disponivel = isset($_POST['disponivel']) ? 1 : 0;

        $sql = "INSERT INTO produtos (nome, descricao, preco, imagem, id_categoria, disponivel) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsii", $nome, $descricao, $preco, $imagem, $id_categoria, $disponivel);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Produto adicionado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao adicionar produto: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Atualizar produto
    if (isset($_POST['atualizar_produto'])) {
        $id = intval($_POST['id']);
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
        $preco = floatval($_POST['preco']);
        $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
        $id_categoria = intval($_POST['id_categoria']);
        $disponivel = isset($_POST['disponivel']) ? 1 : 0;

        $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, imagem=?, id_categoria=?, disponivel=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsiii", $nome, $descricao, $preco, $imagem, $id_categoria, $disponivel, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Produto atualizado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar produto: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Excluir produto
    if (isset($_POST['excluir_produto'])) {
        $id = intval($_POST['id']);
        
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Produto excluído com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao excluir produto: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }
}

// Buscar produtos (com filtro de busca)
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($conn, $_GET['busca']) : '';
$filtro_categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : '';

$sql_produtos = "SELECT p.*, c.nome as categoria_nome 
                 FROM produtos p 
                 LEFT JOIN categorias c ON p.id_categoria = c.id 
                 WHERE 1=1";

if (!empty($busca)) {
    $sql_produtos .= " AND (p.nome LIKE '%$busca%' OR p.descricao LIKE '%$busca%')";
}

if (!empty($filtro_categoria)) {
    $sql_produtos .= " AND p.id_categoria = $filtro_categoria";
}

$sql_produtos .= " ORDER BY p.nome";
$result_produtos = mysqli_query($conn, $sql_produtos);
$produtos = mysqli_fetch_all($result_produtos, MYSQLI_ASSOC);

// Buscar categorias
$sql_categorias = "SELECT * FROM categorias ORDER BY nome";
$result_categorias = mysqli_query($conn, $sql_categorias);
$categorias = mysqli_fetch_all($result_categorias, MYSQLI_ASSOC);

// Contar produtos ativos
$sql_ativos = "SELECT COUNT(*) as total FROM produtos WHERE disponivel = 1";
$result_ativos = mysqli_query($conn, $sql_ativos);
$ativos = mysqli_fetch_assoc($result_ativos)['total'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
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
                <h1><i class="fa-solid fa-pizza-slice" style="margin-right: 10px;"></i> Gerenciar Produtos</h1>
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
            <ul class="admin-menu">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#" class="active"><i class="fas fa-pizza-slice"></i> Produtos</a></li>
                <li><a href="ingredientes.php"><i class="fas fa-carrot"></i> Ingredientes</a></li>
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
                    <h1 class="page-title">Gerenciar Produtos</h1>
                    <p>Adicione, edite ou remova produtos do cardápio</p>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($produtos); ?></span>
                    <span class="stat-label">Total de Produtos</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $ativos; ?></span>
                    <span class="stat-label">Produtos Ativos</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($categorias); ?></span>
                    <span class="stat-label">Categorias</span>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <section class="filters-section">
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label for="busca">Buscar Produtos</label>
                        <input type="text" class="form-control" id="busca" name="busca" 
                               value="<?php echo htmlspecialchars($busca); ?>" 
                               placeholder="Digite o nome ou descrição do produto...">
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria">Filtrar por Categoria</label>
                        <select class="form-control" id="categoria" name="categoria">
                            <option value="">Todas as categorias</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id']; ?>" 
                                    <?php echo $filtro_categoria == $categoria['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="produtos.php" class="btn btn-warning">
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
                <!-- Formulário de Adicionar/Editar Produto -->
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-plus-circle"></i> 
                            <span id="formTitle">Adicionar Novo Produto</span>
                        </h2>
                    </div>
                    <div class="section-content">
                        <form method="POST" id="produtoForm">
                            <input type="hidden" name="id" id="produto_id">
                            
                            <div class="form-group">
                                <label for="nome">Nome do Produto</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>

                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="preco">Preço (R$)</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="imagem">URL da Imagem</label>
                                <input type="url" class="form-control" id="imagem" name="imagem" placeholder="https://exemplo.com/imagem.jpg">
                            </div>

                            <div class="form-group">
                                <label for="id_categoria">Categoria</label>
                                <select class="form-control" id="id_categoria" name="id_categoria" required>
                                    <option value="">Selecione uma categoria</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id']; ?>">
                                            <?php echo htmlspecialchars($categoria['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="disponivel" name="disponivel" value="1" checked>
                                    <label for="disponivel">Produto disponível no cardápio</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="adicionar_produto" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-plus"></i> Adicionar Produto
                                </button>
                                <button type="button" class="btn btn-warning" id="cancelEdit" style="display: none;">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Lista de Produtos -->
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-list"></i> Produtos Cadastrados</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Preço</th>
                                        <th>Categoria</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtos as $produto): ?>
                                        <tr>
                                            <td>
                                                <?php if ($produto['imagem']): ?>
                                                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                                         alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                                         class="product-image"
                                                         onerror="this.src='../assets/placeholder-pizza.jpg'">
                                                <?php else: ?>
                                                    <img src="../assets/placeholder-pizza.jpg" 
                                                         alt="Sem imagem" 
                                                         class="product-image">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($produto['categoria_nome']); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $produto['disponivel'] ? 'status-active' : 'status-inactive'; ?>">
                                                    <?php echo $produto['disponivel'] ? 'Ativo' : 'Inativo'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn btn-warning btn-sm" 
                                                            onclick="editarProduto(<?php echo $produto['id']; ?>)"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-primary btn-sm" 
                                                            onclick="visualizarProduto(<?php echo $produto['id']; ?>)"
                                                            title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="confirmarExclusao(<?php echo $produto['id']; ?>, '<?php echo htmlspecialchars($produto['nome']); ?>')"
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
                <h2>Detalhes do Produto</h2>
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
                <p id="deleteMessage">Tem certeza que deseja excluir este produto?</p>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="id" id="delete_id">
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" class="btn btn-warning" onclick="fecharModal('deleteModal')">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" name="excluir_produto" class="btn btn-danger">
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

        // Dados dos produtos (em um sistema real, isso viria do banco via AJAX)
        const produtosData = <?php echo json_encode($produtos); ?>;

        // Função para visualizar produto
        function visualizarProduto(id) {
            const produto = produtosData.find(p => p.id == id);
            if (produto) {
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = `
                    <div class="product-detail">
                        <div>
                            <img src="${produto.imagem || '../assets/placeholder-pizza.jpg'}" 
                                 alt="${produto.nome}" 
                                 class="product-detail-image"
                                 onerror="this.src='../assets/placeholder-pizza.jpg'">
                        </div>
                        <div class="product-info">
                            <h3>${produto.nome}</h3>
                            <p><span class="info-label">Descrição:</span> ${produto.descricao}</p>
                            <p><span class="info-label">Preço:</span> R$ ${parseFloat(produto.preco).toFixed(2)}</p>
                            <p><span class="info-label">Categoria:</span> ${produto.categoria_nome}</p>
                            <p><span class="info-label">Status:</span> 
                                <span class="status-badge ${produto.disponivel ? 'status-active' : 'status-inactive'}">
                                    ${produto.disponivel ? 'Ativo' : 'Inativo'}
                                </span>
                            </p>
                            <p><span class="info-label">ID:</span> ${produto.id}</p>
                        </div>
                    </div>
                `;
                viewModal.style.display = 'block';
            }
        }

        // Função para editar produto
        function editarProduto(id) {
            const produto = produtosData.find(p => p.id == id);
            if (produto) {
                // Preencher formulário com dados do produto
                document.getElementById('produto_id').value = produto.id;
                document.getElementById('nome').value = produto.nome;
                document.getElementById('descricao').value = produto.descricao;
                document.getElementById('preco').value = produto.preco;
                document.getElementById('imagem').value = produto.imagem || '';
                document.getElementById('id_categoria').value = produto.id_categoria;
                document.getElementById('disponivel').checked = produto.disponivel == 1;
                
                // Mudar formulário para modo edição
                document.getElementById('formTitle').textContent = 'Editar Produto';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Atualizar Produto';
                document.getElementById('submitBtn').name = 'atualizar_produto';
                document.getElementById('cancelEdit').style.display = 'inline-block';
                
                // Rolagem suave para o formulário
                document.getElementById('produtoForm').scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Cancelar edição
        document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('produtoForm').reset();
            document.getElementById('produto_id').value = '';
            document.getElementById('formTitle').textContent = 'Adicionar Novo Produto';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus"></i> Adicionar Produto';
            document.getElementById('submitBtn').name = 'adicionar_produto';
            this.style.display = 'none';
        });

        // Função para confirmar exclusão
        function confirmarExclusao(id, nome) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteMessage').textContent = 
                `Tem certeza que deseja excluir o produto "${nome}"? Esta ação não pode ser desfeita.`;
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