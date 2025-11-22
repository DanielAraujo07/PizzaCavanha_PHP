<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 2+ para gerenciar categorias)
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Processar formulários
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adicionar categoria
    if (isset($_POST['adicionar_categoria'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $tipo_id = intval($_POST['tipo_id']);

        // Verificar se categoria já existe
        $check_sql = "SELECT id FROM categorias WHERE nome = ? AND tipo_id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "si", $nome, $tipo_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $mensagem = "Esta categoria já existe para este tipo!";
            $tipo_mensagem = "error";
        } else {
            $sql = "INSERT INTO categorias (nome, tipo_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $nome, $tipo_id);

            if (mysqli_stmt_execute($stmt)) {
                $novo_id = mysqli_insert_id($conn);
        
                // REGISTRAR LOG DE ADICIONAR
                $dados_novos = ['nome' => $nome, 'tipo_id' => $tipo_id];
                registrarLog($conn, 'categorias', $novo_id, 'INSERT', null, formatarDadosParaLog($dados_novos));

                $mensagem = "Categoria adicionada com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao adicionar categoria: " . mysqli_error($conn);
                $tipo_mensagem = "error";
            }
        }
    }

    // Atualizar categoria
    if (isset($_POST['atualizar_categoria'])) {
        $id = intval($_POST['id']);
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $tipo_id = intval($_POST['tipo_id']);

        // Buscar dados atuais antes da atualização
        $sql_antigo = "SELECT nome, tipo_id FROM categorias WHERE id = ?";
        $stmt_antigo = mysqli_prepare($conn, $sql_antigo);
        mysqli_stmt_bind_param($stmt_antigo, "i", $id);
        mysqli_stmt_execute($stmt_antigo);
        $result_antigo = mysqli_stmt_get_result($stmt_antigo);
        $dados_antigos = mysqli_fetch_assoc($result_antigo);

        $sql = "UPDATE categorias SET nome = ?, tipo_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $nome, $tipo_id, $id);

        if (mysqli_stmt_execute($stmt)) {
            // REGISTRAR LOG DE ATUALIZAÇÃO
            $dados_novos = ['nome' => $nome, 'tipo_id' => $tipo_id];
            registrarLog($conn, 'categorias', $id, 'UPDATE', formatarDadosParaLog($dados_antigos), formatarDadosParaLog($dados_novos));
        
            $mensagem = "Categoria atualizada com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar categoria: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Excluir categoria
    if (isset($_POST['excluir_categoria'])) {
        $id = intval($_POST['id']);

        // Buscar dados antigos para o log
        $sql_antigo = "SELECT nome, tipo_id FROM categorias WHERE id = ?";
        $stmt_antigo = mysqli_prepare($conn, $sql_antigo);
        mysqli_stmt_bind_param($stmt_antigo, "i", $id);
        mysqli_stmt_execute($stmt_antigo);
        $result_antigo = mysqli_stmt_get_result($stmt_antigo);
        $dados_antigos = mysqli_fetch_assoc($result_antigo);

        // Verificar se há produtos usando esta categoria
        $sql_check = "SELECT COUNT(*) as total FROM produtos WHERE id_categoria = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "i", $id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $count_data = mysqli_fetch_assoc($result_check);
        $count = $count_data['total'];

        if ($count > 0) {
            $mensagem = "Não é possível excluir esta categoria pois existem $count produto(s) vinculado(s)!";
            $tipo_mensagem = "error";
        } else {
            $sql = "DELETE FROM categorias WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                // REGISTRAR LOG DE EXCLUIR
                registrarLog($conn, 'categorias', $id, 'DELETE', formatarDadosParaLog($dados_antigos), null);

                $mensagem = "Categoria excluída com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao excluir categoria: " . mysqli_error($conn);
                $tipo_mensagem = "error";
            }
        }
    }
}

// Buscar categorias com informações dos tipos
$sql_categorias = "SELECT c.*, t.nome as tipo_nome 
                    FROM categorias c 
                    LEFT JOIN tipos_categoria t ON c.tipo_id = t.id 
                    ORDER BY t.nome, c.nome";
$result_categorias = mysqli_query($conn, $sql_categorias);
$categorias = mysqli_fetch_all($result_categorias, MYSQLI_ASSOC);

// Buscar tipos de categoria
$sql_tipos = "SELECT * FROM tipos_categoria ORDER BY nome";
$result_tipos = mysqli_query($conn, $sql_tipos);
$tipos = mysqli_fetch_all($result_tipos, MYSQLI_ASSOC);

// Contar produtos por categoria
$sql_contagem = "SELECT id_categoria, COUNT(*) as total FROM produtos GROUP BY id_categoria";
$result_contagem = mysqli_query($conn, $sql_contagem);
$contagem_produtos = [];
while ($row = mysqli_fetch_assoc($result_contagem)) {
    $contagem_produtos[$row['id_categoria']] = $row['total'];
}

// Estatísticas
$total_categorias = count($categorias);
$categorias_com_produtos = count($contagem_produtos);
$tipos_ativos = count(array_unique(array_column($categorias, 'tipo_id')));
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Categorias</title>
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
                <h1><i class="fas fa-tag"></i> Gerenciar Categorias</h1>
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
                <li><a href="#" class="active"><i class="fas fa-tag"></i> Categorias</a></li>
                <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="logs_auditoria.php"><i class="fas fa-clipboard-list"></i> Logs de Auditoria</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Gerenciar Categorias</h1>
                    <p>Organize os produtos em categorias para melhor navegação</p>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo $total_categorias; ?></span>
                    <span class="stat-label">Total de Categorias</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $categorias_com_produtos; ?></span>
                    <span class="stat-label">Categorias com Produtos</span>
                </div>
                <div class="stat-card warning">
                    <span class="stat-number"><?php echo $tipos_ativos; ?></span>
                    <span class="stat-label">Tipos Ativos</span>
                </div>
                <div class="stat-card info">
                    <span class="stat-number"><?php echo count($tipos); ?></span>
                    <span class="stat-label">Tipos Disponíveis</span>
                </div>
            </div>

            <!-- Mensagens -->
            <?php if ($mensagem): ?>
                <div class="message <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            <div class="admin-cards-container">
                <div class="admin-sections">
                    <!-- Formulário de Adicionar/Editar Categoria -->
                    <section class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-plus-circle"></i>
                                <span id="formTitle">Adicionar Nova Categoria</span>
                            </h2>
                        </div>
                        <div class="section-content">
                            <form method="POST" id="categoriaForm">
                                <input type="hidden" name="id" id="categoria_id">

                                <div class="form-group">
                                    <label for="nome">Nome da Categoria</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                        placeholder="Ex: Pizzas Salgadas, Bebidas, Sobremesas..." required>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_id">Tipo de Categoria</label>
                                    <select class="form-control" id="tipo_id" name="tipo_id" required>
                                        <option value="">Selecione um tipo</option>
                                        <?php foreach ($tipos as $tipo): ?>
                                            <option value="<?php echo $tipo['id']; ?>">
                                                <?php echo htmlspecialchars($tipo['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text">O tipo define se a categoria é para produtos salgados, doces, bebidas, etc.</small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="adicionar_categoria" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-plus"></i> Adicionar Categoria
                                    </button>
                                    <button type="button" class="btn btn-warning" id="cancelEdit" style="display: none;">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Tipos de Categoria -->
                    <section class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-layer-group"></i> Tipos de Categoria</h2>
                        </div>
                        <div class="section-content">
                            <div class="tipos-grid">
                                <?php foreach ($tipos as $tipo):
                                    $categorias_tipo = array_filter($categorias, function ($cat) use ($tipo) {
                                        return $cat['tipo_id'] == $tipo['id'];
                                    });
                                ?>
                                    <div class="tipo-card">
                                        <div class="tipo-header">
                                            <h3><?php echo htmlspecialchars($tipo['nome']); ?></h3>
                                            <span class="badge"><?php echo count($categorias_tipo); ?> categorias</span>
                                        </div>
                                        <div class="tipo-categorias">
                                            <?php if (count($categorias_tipo) > 0): ?>
                                                <?php foreach ($categorias_tipo as $categoria): ?>
                                                    <span class="categoria-tag">
                                                        <?php echo htmlspecialchars($categoria['nome']); ?>
                                                        <?php if (isset($contagem_produtos[$categoria['id']])): ?>
                                                            <span class="produto-count"><?php echo $contagem_produtos[$categoria['id']]; ?></span>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="sem-categorias">Nenhuma categoria cadastrada</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Lista de Categorias -->
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-list"></i> Todas as Categorias</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Produtos</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($categorias)): ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                                Nenhuma categoria cadastrada
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <tr>
                                                <td><?php echo $categoria['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($categoria['nome']); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="tipo-badge"><?php echo htmlspecialchars($categoria['tipo_nome']); ?></span>
                                                </td>
                                                <td>
                                                    <?php if (isset($contagem_produtos[$categoria['id']])): ?>
                                                        <span class="produto-count-badge">
                                                            <?php echo $contagem_produtos[$categoria['id']]; ?> produtos
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="sem-produtos">0 produtos</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <button class="btn btn-warning btn-sm"
                                                            onclick="editarCategoria(<?php echo $categoria['id']; ?>)"
                                                            title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                            onclick="confirmarExclusao(<?php echo $categoria['id']; ?>, '<?php echo htmlspecialchars($categoria['nome']); ?>')"
                                                            title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Tem certeza que deseja excluir esta categoria?</p>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="id" id="delete_id">
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" class="btn btn-warning" onclick="fecharModal('deleteModal')">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" name="excluir_categoria" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Confirmar Exclusão
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Dados das categorias
        const categoriasData = <?php echo json_encode($categorias); ?>;
        const contagemProdutos = <?php echo json_encode($contagem_produtos); ?>;

        // Função para editar categoria
        function editarCategoria(id) {
            const categoria = categoriasData.find(c => c.id == id);
            if (categoria) {
                // Preencher formulário com dados da categoria
                document.getElementById('categoria_id').value = categoria.id;
                document.getElementById('nome').value = categoria.nome;
                document.getElementById('tipo_id').value = categoria.tipo_id;

                // Mudar formulário para modo edição
                document.getElementById('formTitle').textContent = 'Editar Categoria';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Atualizar Categoria';
                document.getElementById('submitBtn').name = 'atualizar_categoria';
                document.getElementById('cancelEdit').style.display = 'inline-block';

                // Rolagem suave para o formulário
                document.getElementById('categoriaForm').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        // Cancelar edição
        document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('categoriaForm').reset();
            document.getElementById('categoria_id').value = '';
            document.getElementById('formTitle').textContent = 'Adicionar Nova Categoria';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus"></i> Adicionar Categoria';
            document.getElementById('submitBtn').name = 'adicionar_categoria';
            this.style.display = 'none';
        });

        // Função para confirmar exclusão
        function confirmarExclusao(id, nome) {
            const produtoCount = contagemProdutos[id] || 0;
            let message = `Tem certeza que deseja excluir a categoria "${nome}"?`;

            if (produtoCount > 0) {
                message += `\n\nATENÇÃO: Esta categoria possui ${produtoCount} produto(s) vinculado(s). A exclusão não será permitida.`;
            } else {
                message += "\n\nEsta ação não pode ser desfeita.";
            }

            document.getElementById('delete_id').value = id;
            document.getElementById('deleteMessage').textContent = message;
            document.getElementById('deleteModal').style.display = 'block';
        }

        // Fechar modal
        function fecharModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Fechar modais ao clicar no X ou fora
        document.querySelectorAll('.close').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                });
            });
        });

        window.addEventListener('click', function(event) {
            document.querySelectorAll('.modal').forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
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
</body>

</html>