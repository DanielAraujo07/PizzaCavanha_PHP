<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível 5+ para gerenciar usuários e classes)
if ($_SESSION['class_nivel'] < 5) {
    header('Location: ../index.php');
    exit();
}

// Processar formulários
$mensagem = '';
$tipo_mensagem = '';

// CRUD de Usuários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adicionar usuário
    if (isset($_POST['adicionar_usuario'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
        $senha = hash('sha512', $_POST['senha']);
        $class_id = intval($_POST['class_id']);

        // Verificar se email já existe
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $mensagem = "Este e-mail já está cadastrado!";
            $tipo_mensagem = "error";
        } else {
            $sql = "INSERT INTO users (nome, email, telefone, senha, class_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $nome, $email, $telefone, $senha, $class_id);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Usuário adicionado com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao adicionar usuário: " . mysqli_error($conn);
                $tipo_mensagem = "error";
            }
        }
    }

    // Atualizar usuário
    if (isset($_POST['atualizar_usuario'])) {
        $id = intval($_POST['id']);
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
        $class_id = intval($_POST['class_id']);

        // Se senha foi fornecida, atualizar
        if (!empty($_POST['senha'])) {
            $senha = hash('sha512', $_POST['senha']);
            $sql = "UPDATE users SET nome=?, email=?, telefone=?, senha=?, class_id=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssii", $nome, $email, $telefone, $senha, $class_id, $id);
        } else {
            $sql = "UPDATE users SET nome=?, email=?, telefone=?, class_id=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssii", $nome, $email, $telefone, $class_id, $id);
        }

        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Usuário atualizado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar usuário: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Excluir usuário
    if (isset($_POST['excluir_usuario'])) {
        $id = intval($_POST['id']);

        // Não permitir excluir a si mesmo
        if ($id == $_SESSION['id']) {
            $mensagem = "Você não pode excluir sua própria conta!";
            $tipo_mensagem = "error";
        } else {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Usuário excluído com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao excluir usuário: " . mysqli_error($conn);
                $tipo_mensagem = "error";
            }
        }
    }

    // CRUD de Classes de Usuário
    // Adicionar classe
    if (isset($_POST['adicionar_classe'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $nivel = intval($_POST['nivel']);

        $sql = "INSERT INTO user_classes (nome, nivel) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $nome, $nivel);

        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Classe de usuário adicionada com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao adicionar classe: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Atualizar classe
    if (isset($_POST['atualizar_classe'])) {
        $id = intval($_POST['id']);
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $nivel = intval($_POST['nivel']);

        $sql = "UPDATE user_classes SET nome=?, nivel=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $nome, $nivel, $id);

        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Classe de usuário atualizada com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar classe: " . mysqli_error($conn);
            $tipo_mensagem = "error";
        }
    }

    // Excluir classe
    if (isset($_POST['excluir_classe'])) {
        $id = intval($_POST['id']);

        // Verificar se há usuários usando esta classe
        $check_sql = "SELECT COUNT(*) as total FROM users WHERE class_id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "i", $id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        $count = mysqli_fetch_assoc($result)['total'];

        if ($count > 0) {
            $mensagem = "Não é possível excluir esta classe pois existem usuários vinculados a ela!";
            $tipo_mensagem = "error";
        } else {
            $sql = "DELETE FROM user_classes WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "Classe de usuário excluída com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao excluir classe: " . mysqli_error($conn);
                $tipo_mensagem = "error";
            }
        }
    }
}

// Buscar classes de usuário
$sql_classes = "SELECT * FROM user_classes ORDER BY nivel";
$result_classes = mysqli_query($conn, $sql_classes);
$classes = mysqli_fetch_all($result_classes, MYSQLI_ASSOC);

// Buscar usuários com paginação
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($conn, $_GET['busca']) : '';
$filtro_classe = isset($_GET['classe']) ? intval($_GET['classe']) : '';

// Paginação
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 25;
$offset = ($pagina - 1) * $limite;

// Query para contar total COM FILTROS (para paginação)
$sql_count = "SELECT COUNT(*) as total 
              FROM users u 
              LEFT JOIN user_classes uc ON u.class_id = uc.id 
              WHERE 1=1";

if (!empty($busca)) {
    $sql_count .= " AND (u.nome LIKE '%$busca%' OR u.email LIKE '%$busca%')";
}

if (!empty($filtro_classe)) {
    $sql_count .= " AND u.class_id = $filtro_classe";
}

$result_count = mysqli_query($conn, $sql_count);
$total_usuarios_filtrados = mysqli_fetch_assoc($result_count)['total'];
$total_paginas = ceil($total_usuarios_filtrados / $limite);

// Query para buscar usuários (apenas para a página atual)
$sql_usuarios = "SELECT u.*, uc.nome as classe_nome, uc.nivel as classe_nivel 
                 FROM users u 
                 LEFT JOIN user_classes uc ON u.class_id = uc.id 
                 WHERE 1=1";

if (!empty($busca)) {
    $sql_usuarios .= " AND (u.nome LIKE '%$busca%' OR u.email LIKE '%$busca%')";
}

if (!empty($filtro_classe)) {
    $sql_usuarios .= " AND u.class_id = $filtro_classe";
}

$sql_usuarios .= " ORDER BY u.nome LIMIT $limite OFFSET $offset";
$result_usuarios = mysqli_query($conn, $sql_usuarios);
$usuarios = mysqli_fetch_all($result_usuarios, MYSQLI_ASSOC);

// 🎯 CONTADORES PARA ESTATÍSTICAS - BUSCAR DO BANCO COMPLETO (SEM FILTROS)
$sql_estatisticas = "SELECT 
    COUNT(*) as total_usuarios,
    SUM(CASE WHEN uc.nivel = 1 THEN 1 ELSE 0 END) as total_clientes,
    SUM(CASE WHEN uc.nivel > 1 THEN 1 ELSE 0 END) as total_funcionarios
    FROM users u 
    LEFT JOIN user_classes uc ON u.class_id = uc.id";

$result_estatisticas = mysqli_query($conn, $sql_estatisticas);
$estatisticas = mysqli_fetch_assoc($result_estatisticas);

$total_usuarios = $estatisticas['total_usuarios'];
$total_clientes = $estatisticas['total_clientes'];
$total_funcionarios = $estatisticas['total_funcionarios'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="shortcut icon" href="../assets/funcionario.png"/>

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
                <h1><i class="fa-solid fa-users"></i> Gerenciar Usuários</h1>
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
                <li><a href="categorias.php"><i class="fas fa-tag"></i> Categorias</a></li>
                <li><a href="#" class="active"><i class="fas fa-users"></i> Usuários</a></li>
                <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Gerenciar Usuários</h1>
                    <p>Administre usuários e classes de acesso do sistema</p>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="stats-cards">
                <div class="stat-card">
                    <span class="stat-number"><?php echo $total_usuarios; ?></span>
                    <span class="stat-label">Total de Usuários</span>
                </div>
                <div class="stat-card success">
                    <span class="stat-number"><?php echo $total_clientes; ?></span>
                    <span class="stat-label">Clientes</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($classes); ?></span>
                    <span class="stat-label">Classes de Acesso</span>
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
                <button class="tab-btn active" data-tab="crud">Gerenciar Usuários & Classes</button>
                <button class="tab-btn" data-tab="lista">Lista de Usuários</button>
            </div>

            <!-- Conteúdo da Aba de CRUD -->
            <div id="tab-crud" class="tab-content active">
                <div class="admin-sections">
                    <!-- Formulário de Gerenciar Usuários -->
                    <section class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-user-plus"></i>
                                <span id="formUsuarioTitle">Adicionar Novo Usuário</span>
                            </h2>
                        </div>
                        <div class="section-content">
                            <form method="POST" id="usuarioForm">
                                <input type="hidden" name="id" id="usuario_id">

                                <div class="form-group">
                                    <label for="nome">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                                </div>

                                <div class="form-group">
                                    <label for="senha">
                                        <span id="senhaLabel">Senha</span>
                                        <small style="color: var(--text-muted); font-weight: normal;"></small>
                                    </label>
                                    <input type="password" class="form-control" id="senha" name="senha">
                                </div>

                                <div class="form-group">
                                    <label for="class_id">Tipo de Usuário</label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">Selecione um tipo</option>
                                        <?php foreach ($classes as $classe): ?>
                                            <option value="<?php echo $classe['id']; ?>">
                                                <?php echo htmlspecialchars($classe['nome']); ?> (Nível <?php echo $classe['nivel']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="adicionar_usuario" class="btn btn-primary" id="submitUsuarioBtn">
                                        <i class="fas fa-plus"></i> Adicionar Usuário
                                    </button>
                                    <button type="button" class="btn btn-warning" id="cancelUsuarioEdit" style="display: none;">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <!-- Formulário de Gerenciar Classes -->
                    <section class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-user-tag"></i>
                                <span id="formClasseTitle">Adicionar Nova Classe</span>
                            </h2>
                        </div>
                        <div class="section-content">
                            <form method="POST" id="classeForm">
                                <input type="hidden" name="id" id="classe_id">

                                <div class="form-group">
                                    <label for="nome_classe">Nome da Classe</label>
                                    <input type="text" class="form-control" id="nome_classe" name="nome" required>
                                </div>

                                <div class="form-group">
                                    <label for="nivel">Nível de Acesso</label>
                                    <input type="number" class="form-control" id="nivel" name="nivel" min="1" max="10" required>
                                    <small style="color: var(--text-muted);">Nível 1 = Cliente, Nível 6 = Admin</small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="adicionar_classe" class="btn btn-primary" id="submitClasseBtn">
                                        <i class="fas fa-plus"></i> Adicionar Classe
                                    </button>
                                    <button type="button" class="btn btn-warning" id="cancelClasseEdit" style="display: none;">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

                <!-- Lista de Classes -->
                <section class="section-card" style="margin-top: 2rem;">
                    <div class="section-header">
                        <h2><i class="fas fa-list"></i> Classes de Usuário Cadastradas</h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Nível</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($classes as $classe): ?>
                                        <tr>
                                            <td><?php echo $classe['id']; ?></td>
                                            <td><?php echo htmlspecialchars($classe['nome']); ?></td>
                                            <td><?php echo $classe['nivel']; ?></td>
                                            <td>
                                                <div class="actions">
                                                    <button class="btn btn-warning btn-sm"
                                                        onclick="editarClasse(<?php echo $classe['id']; ?>)"
                                                        title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="confirmarExclusaoClasse(<?php echo $classe['id']; ?>, '<?php echo htmlspecialchars($classe['nome']); ?>')"
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

            <!-- Conteúdo da Aba de Lista -->
            <div id="tab-lista" class="tab-content">
                <!-- Filtros e Busca -->
                <section class="filters-section">
                    <form method="GET" class="filters-form">
                        <!-- Adicionar campo hidden para manter a aba -->
                        <input type="hidden" name="aba" value="lista">

                        <div class="form-group">
                            <label for="busca_lista">Buscar Usuários</label>
                            <input type="text" class="form-control" id="busca_lista" name="busca"
                                value="<?php echo htmlspecialchars($busca); ?>"
                                placeholder="Digite o nome ou e-mail do usuário...">
                        </div>

                        <div class="form-group">
                            <label for="classe_lista">Filtrar por Classe</label>
                            <select class="form-control" id="classe_lista" name="classe">
                                <option value="">Todas as classes</option>
                                <?php foreach ($classes as $classe): ?>
                                    <option value="<?php echo $classe['id']; ?>"
                                        <?php echo $filtro_classe == $classe['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($classe['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="?aba=lista" class="btn btn-warning">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </form>
                </section>

                <!-- Lista de Usuários -->
                <section class="section-card">
                    <div class="section-header">
                        <h2><i class="fas fa-users"></i> Usuários Cadastrados
                            <small style="font-size: 0.8rem; color: var(--text-muted);">
                                (<?php echo $total_usuarios; ?> usuários encontrados)
                            </small>
                        </h2>
                    </div>
                    <div class="section-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Telefone</th>
                                        <th>Classe</th>
                                        <th>Nível</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($usuarios)): ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                                Nenhum usuário encontrado
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <tr>
                                                <td><?php echo $usuario['id']; ?></td>
                                                <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                                                <td><?php echo htmlspecialchars($usuario['classe_nome']); ?></td>
                                                <td>
                                                    <span class="status-badge <?php echo $usuario['classe_nivel'] == 1 ? 'status-inactive' : 'status-active'; ?>">
                                                        Nível <?php echo $usuario['classe_nivel']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <button class="btn btn-warning btn-sm"
                                                            onclick="editarUsuarioLista(<?php echo $usuario['id']; ?>)"
                                                            title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="visualizarUsuario(<?php echo $usuario['id']; ?>)"
                                                            title="Visualizar">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <?php if ($usuario['id'] != $_SESSION['id']): ?>
                                                            <button class="btn btn-danger btn-sm"
                                                                onclick="confirmarExclusaoUsuario(<?php echo $usuario['id']; ?>, '<?php echo htmlspecialchars($usuario['nome']); ?>')"
                                                                title="Excluir">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-danger btn-sm" disabled title="Não é possível excluir sua própria conta">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <?php if ($total_paginas > 1): ?>
                            <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 2rem; padding: 1rem;">
                                <?php if ($pagina > 1): ?>
                                    <?php
                                    $params_anterior = array_merge($_GET, ['pagina' => $pagina - 1]);
                                    // Garantir que o parâmetro da aba seja preservado
                                    if (!isset($params_anterior['aba'])) {
                                        $params_anterior['aba'] = 'lista';
                                    }
                                    ?>
                                    <a href="?<?php echo http_build_query($params_anterior); ?>" class="btn btn-warning">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php endif; ?>

                                <span style="color: var(--text-light); font-weight: 600;">
                                    Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?>
                                </span>

                                <?php if ($pagina < $total_paginas): ?>
                                    <?php
                                    $params_proxima = array_merge($_GET, ['pagina' => $pagina + 1]);
                                    // Garantir que o parâmetro da aba seja preservado
                                    if (!isset($params_proxima['aba'])) {
                                        $params_proxima['aba'] = 'lista';
                                    }
                                    ?>
                                    <a href="?<?php echo http_build_query($params_proxima); ?>" class="btn btn-warning">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Modal de Visualização de Usuário -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes do Usuário</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão de Usuário -->
    <div id="deleteUsuarioModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p id="deleteUsuarioMessage">Tem certeza que deseja excluir este usuário?</p>
                <form method="POST" id="deleteUsuarioForm">
                    <input type="hidden" name="id" id="delete_usuario_id">
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" class="btn btn-warning" onclick="fecharModal('deleteUsuarioModal')">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" name="excluir_usuario" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Confirmar Exclusão
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão de Classe -->
    <div id="deleteClasseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p id="deleteClasseMessage">Tem certeza que deseja excluir esta classe?</p>
                <form method="POST" id="deleteClasseForm">
                    <input type="hidden" name="id" id="delete_classe_id">
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <button type="button" class="btn btn-warning" onclick="fecharModal('deleteClasseModal')">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" name="excluir_classe" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Confirmar Exclusão
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Dados dos usuários e classes
        const usuariosData = <?php echo json_encode($usuarios); ?>;
        const classesData = <?php echo json_encode($classes); ?>;

        // Sistema de Abas
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            // Verificar se há parâmetro de aba na URL
            const urlParams = new URLSearchParams(window.location.search);
            const abaParam = urlParams.get('aba');

            // Determinar qual aba ativar
            let abaAtiva = 'crud'; // padrão
            if (abaParam === 'lista') {
                abaAtiva = 'lista';
            }

            // Ativar aba correta
            tabBtns.forEach(btn => {
                if (btn.getAttribute('data-tab') === abaAtiva) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            tabContents.forEach(content => {
                if (content.id === `tab-${abaAtiva}`) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });

            // Event listeners para as abas
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Atualizar URL sem recarregar a página
                    const novaUrl = new URL(window.location);
                    novaUrl.searchParams.set('aba', targetTab);
                    // Remover parâmetro de página quando mudar de aba
                    if (targetTab === 'crud') {
                        novaUrl.searchParams.delete('pagina');
                    }
                    history.pushState({}, '', novaUrl);

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

        // Funções para Usuários
        function visualizarUsuario(id) {
            const usuario = usuariosData.find(u => u.id == id);
            if (usuario) {
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = `
                    <div class="product-detail">
                        <div class="product-info">
                            <h3>${usuario.nome}</h3>
                            <p><span class="info-label">ID:</span> ${usuario.id}</p>
                            <p><span class="info-label">E-mail:</span> ${usuario.email}</p>
                            <p><span class="info-label">Telefone:</span> ${usuario.telefone}</p>
                            <p><span class="info-label">Classe:</span> ${usuario.classe_nome}</p>
                            <p><span class="info-label">Nível:</span> 
                                <span class="status-badge ${usuario.classe_nivel == 1 ? 'status-inactive' : 'status-active'}">
                                    Nível ${usuario.classe_nivel}
                                </span>
                            </p>
                        </div>
                    </div>
                `;
                document.getElementById('viewModal').style.display = 'block';
            }
        }

        function editarUsuario(id) {
            const usuario = usuariosData.find(u => u.id == id);
            if (usuario) {
                // Preencher formulário com dados do usuário
                document.getElementById('usuario_id').value = usuario.id;
                document.getElementById('nome').value = usuario.nome;
                document.getElementById('email').value = usuario.email;
                document.getElementById('telefone').value = usuario.telefone;
                document.getElementById('class_id').value = usuario.class_id;

                // Mudar formulário para modo edição
                document.getElementById('formUsuarioTitle').textContent = 'Editar Usuário';
                document.getElementById('submitUsuarioBtn').innerHTML = '<i class="fas fa-save"></i> Atualizar Usuário';
                document.getElementById('submitUsuarioBtn').name = 'atualizar_usuario';
                document.getElementById('senhaLabel').innerHTML = 'Nova Senha <small style="color: var(--text-muted); font-weight: normal;">(Deixe em branco para manter a atual)</small>';
                document.getElementById('cancelUsuarioEdit').style.display = 'inline-block';

                // Rolagem suave para o formulário
                document.getElementById('usuarioForm').scrollIntoView({
                    behavior: 'smooth'
                });

                // Mudar para aba de CRUD
                document.querySelector('[data-tab="crud"]').click();
            }
        }

        function editarUsuarioLista(id) {
            const usuario = usuariosData.find(u => u.id == id);
            if (usuario) {
                // Preencher formulário com dados do usuário
                document.getElementById('usuario_id').value = usuario.id;
                document.getElementById('nome').value = usuario.nome;
                document.getElementById('email').value = usuario.email;
                document.getElementById('telefone').value = usuario.telefone;
                document.getElementById('class_id').value = usuario.class_id;

                // Mudar formulário para modo edição
                document.getElementById('formUsuarioTitle').textContent = 'Editar Usuário';
                document.getElementById('submitUsuarioBtn').innerHTML = '<i class="fas fa-save"></i> Atualizar Usuário';
                document.getElementById('submitUsuarioBtn').name = 'atualizar_usuario';
                document.getElementById('senhaLabel').innerHTML = 'Nova Senha <small style="color: var(--text-muted); font-weight: normal;">(Deixe em branco para manter a atual)</small>';
                document.getElementById('cancelUsuarioEdit').style.display = 'inline-block';

                // Mudar para aba de CRUD usando JavaScript para não recarregar a página
                document.querySelector('[data-tab="crud"]').click();

                // Rolagem suave para o formulário
                setTimeout(() => {
                    document.getElementById('usuarioForm').scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 300);
            }
        }

        function confirmarExclusaoUsuario(id, nome) {
            document.getElementById('delete_usuario_id').value = id;
            document.getElementById('deleteUsuarioMessage').textContent =
                `Tem certeza que deseja excluir o usuário "${nome}"? Esta ação não pode ser desfeita.`;
            document.getElementById('deleteUsuarioModal').style.display = 'block';
        }

        // Funções para Classes
        function editarClasse(id) {
            const classe = classesData.find(c => c.id == id);
            if (classe) {
                // Preencher formulário com dados da classe
                document.getElementById('classe_id').value = classe.id;
                document.getElementById('nome_classe').value = classe.nome;
                document.getElementById('nivel').value = classe.nivel;

                // Mudar formulário para modo edição
                document.getElementById('formClasseTitle').textContent = 'Editar Classe';
                document.getElementById('submitClasseBtn').innerHTML = '<i class="fas fa-save"></i> Atualizar Classe';
                document.getElementById('submitClasseBtn').name = 'atualizar_classe';
                document.getElementById('cancelClasseEdit').style.display = 'inline-block';

                // Rolagem suave para o formulário
                document.getElementById('classeForm').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        function confirmarExclusaoClasse(id, nome) {
            document.getElementById('delete_classe_id').value = id;
            document.getElementById('deleteClasseMessage').textContent =
                `Tem certeza que deseja excluir a classe "${nome}"? Esta ação não pode ser desfeita.`;
            document.getElementById('deleteClasseModal').style.display = 'block';
        }

        // Cancelar edições
        document.getElementById('cancelUsuarioEdit').addEventListener('click', function() {
            document.getElementById('usuarioForm').reset();
            document.getElementById('usuario_id').value = '';
            document.getElementById('formUsuarioTitle').textContent = 'Adicionar Novo Usuário';
            document.getElementById('submitUsuarioBtn').innerHTML = '<i class="fas fa-plus"></i> Adicionar Usuário';
            document.getElementById('submitUsuarioBtn').name = 'adicionar_usuario';
            document.getElementById('senhaLabel').textContent = 'Senha';
            this.style.display = 'none';
        });

        document.getElementById('cancelClasseEdit').addEventListener('click', function() {
            document.getElementById('classeForm').reset();
            document.getElementById('classe_id').value = '';
            document.getElementById('formClasseTitle').textContent = 'Adicionar Nova Classe';
            document.getElementById('submitClasseBtn').innerHTML = '<i class="fas fa-plus"></i> Adicionar Classe';
            document.getElementById('submitClasseBtn').name = 'adicionar_classe';
            this.style.display = 'none';
        });

        // Fechar modais
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

        // Formatação de telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = '(' + value;
                if (value.length > 3) {
                    value = value.slice(0, 3) + ') ' + value.slice(3);
                    if (value.length > 10) {
                        value = value.slice(0, 10) + '-' + value.slice(10);
                    }
                }
            }
            e.target.value = value.slice(0, 15);
        });
    </script>
</body>

</html>