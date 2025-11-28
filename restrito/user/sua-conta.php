<?php include "../verifica_login.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua Conta</title>
    <link rel="shortcur icon" href="assets/logo.svg" />
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

    <link rel="stylesheet" href="../assets/css/user.css">
</head>

<body>
    <header>
        <div class="container header-container">
            <a href="../index.php">
                <div class="logo">
                    <img src="../assets/logo.svg" alt="Logo">
                    <h1>Pizza do Cavanha</h1>
                </div>
            </a>
            <nav>
                <ul>
                    <li><a href="../index.php" class="nav-link" data-page="home">Início</a></li>
                    <li><a href="../index.php" class="nav-link" data-page="cardapio">Cardápio</a></li>
                    <li><a href="../index.php" class="nav-link" data-page="carrinho">Carrinho</a></li>

                    <?php if ($_SESSION['class_nivel'] !== 1): ?>
                        <li><a href="../funcionario/index.php">Área do Funcionário</a></li>
                    <?php endif; ?>

                    <?php if (($_SESSION['class_nivel'] == 1) || ($_SESSION['class_nivel'] == 6)): ?>
                        <li><a href="../pong.php">Esperando a Pizza?</a></li>
                    <?php endif; ?>

                </ul>

                <div class="container-usuario">
                    <input type="checkbox" id="button-user">
                    <div class="btn-usuario">
                        <label for="button-user" class="imagem-usuario">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="#E0E0E0" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                            </svg>
                        </label>
                    </div>
                    <div class="opt-usuario">
                        <div class="nome-usuario">
                            <p>
                                Olá, <?php echo htmlspecialchars($_SESSION['nome']); ?>!
                            </p>
                        </div>
                        <ul>
                            <li><a href="#" class="opt-user-link">Sua Conta</a></li>
                            <li><a href="seus-pedidos.php" class="opt-user-link">Seus Pedidos</a></li>
                        </ul>
                        <hr>
                        <div class="sair-usuario">
                            <a href="../../logout.php">Sair</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- Container Principal -->
    <div class="container">
        <h2 class="titulo-pagina">Minha Conta</h2>
        <div class="conta-container">
            <!-- Menu Lateral -->
            <nav class="menu-lateral">
                <ul>
                    <li><a href="#" class="nav-aba ativo" data-aba="dados-pessoais">📋 Dados Pessoais</a></li>
                    <li><a href="#" class="nav-aba" data-aba="alterar-senha">🔒 Alterar Senha</a></li>
                </ul>
            </nav>

            <!-- Conteúdo -->
            <div class="conteudo-conta">
                <?php
                // Processar atualização de dados
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    include "../conexao.php";

                    if (isset($_POST['atualizar_dados'])) {
                        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
                        $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
                        $email = mysqli_real_escape_string($conn, $_POST['email']);
                        $id_usuario = $_SESSION['id'];

                        // Verificar se email já existe (exceto para o usuário atual)
                        $check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                        $check_stmt = mysqli_prepare($conn, $check_sql);
                        mysqli_stmt_bind_param($check_stmt, "si", $email, $id_usuario);
                        mysqli_stmt_execute($check_stmt);
                        mysqli_stmt_store_result($check_stmt);

                        if (mysqli_stmt_num_rows($check_stmt) > 0) {
                            $mensagem = "Este e-mail já está em uso por outro usuário.";
                            $tipo_mensagem = "erro";
                        } else {
                            // Atualizar dados
                            $update_sql = "UPDATE users SET nome = ?, telefone = ?, email = ? WHERE id = ?";
                            $update_stmt = mysqli_prepare($conn, $update_sql);
                            mysqli_stmt_bind_param($update_stmt, "sssi", $nome, $telefone, $email, $id_usuario);

                            if (mysqli_stmt_execute($update_stmt)) {
                                $_SESSION['nome'] = $nome;
                                $_SESSION['email'] = $email;
                                $mensagem = "Dados atualizados com sucesso!";
                                $tipo_mensagem = "sucesso";
                            } else {
                                $mensagem = "Erro ao atualizar dados: " . mysqli_error($conn);
                                $tipo_mensagem = "erro";
                            }
                        }
                    }

                    if (isset($_POST['alterar_senha'])) {
                        $senha_atual = hash('sha512', $_POST['senha_atual']);
                        $nova_senha = $_POST['nova_senha'];
                        $confirmar_senha = $_POST['confirmar_senha'];
                        $id_usuario = $_SESSION['id'];

                        // Verificar senha atual
                        $check_senha_sql = "SELECT senha FROM users WHERE id = ?";
                        $check_senha_stmt = mysqli_prepare($conn, $check_senha_sql);
                        mysqli_stmt_bind_param($check_senha_stmt, "i", $id_usuario);
                        mysqli_stmt_execute($check_senha_stmt);
                        $result = mysqli_stmt_get_result($check_senha_stmt);
                        $usuario = mysqli_fetch_assoc($result);

                        if ($usuario['senha'] !== $senha_atual) {
                            $mensagem = "Senha atual incorreta.";
                            $tipo_mensagem = "erro";
                        } elseif ($nova_senha !== $confirmar_senha) {
                            $mensagem = "As novas senhas não coincidem.";
                            $tipo_mensagem = "erro";
                        } elseif (strlen($nova_senha) < 6) {
                            $mensagem = "A nova senha deve ter pelo menos 6 caracteres.";
                            $tipo_mensagem = "erro";
                        } else {
                            // Atualizar senha
                            $nova_senha_hash = hash('sha512', $nova_senha);
                            $update_senha_sql = "UPDATE users SET senha = ? WHERE id = ?";
                            $update_senha_stmt = mysqli_prepare($conn, $update_senha_sql);
                            mysqli_stmt_bind_param($update_senha_stmt, "si", $nova_senha_hash, $id_usuario);

                            if (mysqli_stmt_execute($update_senha_stmt)) {
                                $mensagem = "Senha alterada com sucesso!";
                                $tipo_mensagem = "sucesso";
                            } else {
                                $mensagem = "Erro ao alterar senha: " . mysqli_error($conn);
                                $tipo_mensagem = "erro";
                            }
                        }
                    }
                }

                // Buscar dados atualizados do usuário
                $id_usuario = $_SESSION['id'];
                $sql = "SELECT nome, email, telefone FROM users WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id_usuario);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $usuario = mysqli_fetch_assoc($result); // REMOVI O $conn DO PARÂMETRO
                ?>

                <!-- Aba: Dados Pessoais -->
                <div id="dados-pessoais" class="aba-conteudo ativo">
                    <h2>Meus Dados Pessoais</h2>

                    <form method="POST" action="">
                        <div class="forms">
                            <div class="form-group">
                                <label for="nome">Nome Completo</label>
                                <input type="text" id="nome" name="nome" class="form-control"
                                    value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="tel" id="telefone" name="telefone" class="form-control"
                                    value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
                            </div>
                        </div>
                        <div class="save-cancel">
                            <div class="form-group">
                                <button type="submit" name="atualizar_dados" class="btn-salvar">
                                    Salvar Alterações
                                </button>
                                <button type="button" class="btn-cancelar" onclick="window.location.reload()">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Aba: Alterar Senha -->
                <div id="alterar-senha" class="aba-conteudo">
                    <h2>Alterar Senha</h2>

                    <form method="POST" action="">
                        <div class="forms">
                            <div class="form-group">
                                <label for="senha_atual">Senha Atual</label>
                                <input type="password" id="senha_atual" name="senha_atual" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="nova_senha">Nova Senha</label>
                                <input type="password" id="nova_senha" name="nova_senha" class="form-control"
                                    minlength="6" required>
                                <small style="color: #888; font-size: 12px;">Mínimo 6 caracteres</small>
                            </div>

                            <div class="form-group">
                                <label for="confirmar_senha">Confirmar Nova Senha</label>
                                <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control"
                                    minlength="6" required>
                            </div>
                        </div>
                        <div class="save-cancel">
                            <div class="form-group">
                                <button type="submit" name="alterar_senha" class="btn-salvar">
                                    Alterar Senha
                                </button>
                                <button type="button" class="btn-cancelar" onclick="window.location.reload()">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Mensagens -->
            <?php if (isset($mensagem)): ?>
                <div class="sumir mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="copyright-logo-container">
                <div class="logo">
                    <img src="../assets/logo-2.svg" alt="Logo">
                    <h1 style="color: var(--primary-color);">Pizza do Cavanha</h1>
                </div>
                <p>© 2025 Pizza do Cavanha. Todos os direitos reservados.</p>
            </div>
            <hr style="height: 90px">
            <div class="contactenos-container">
                <h2>Fale Conosco:</h2>
                <div class="social-links-container">
                    <a href="https://github.com/DanielAraujo07" target="_blank" class="social-links">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#FFF" class="bi bi-github" viewBox="0 0 16 16">
                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8" />
                        </svg>
                    </a>
                    <a href="https://wa.me/3199782383" target="_blank" class="social-links">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#FFF" class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                        </svg>
                    </a>
                    <a href="mailto:daniel271207.produtividade@gmail.com" target="_blank" class="social-links">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#FFF" role="img" viewBox="0 0 24 24">
                            <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navegação entre abas
        document.querySelectorAll('.nav-aba').forEach(aba => {
            aba.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove classe ativa de todas as abas
                document.querySelectorAll('.nav-aba').forEach(a => {
                    a.classList.remove('ativo');
                });
                document.querySelectorAll('.aba-conteudo').forEach(conteudo => {
                    conteudo.classList.remove('ativo');
                });

                // Adiciona classe ativa na aba clicada
                this.classList.add('ativo');
                const abaId = this.getAttribute('data-aba');
                document.getElementById(abaId).classList.add('ativo');
            });
        });

        // Formatação de telefone
        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', function() {
                let valor = this.value.replace(/\D/g, '');
                let tamanho = valor.length;

                if (tamanho > 0) {
                    valor = '(' + valor;
                    if (tamanho > 2) {
                        valor = [valor.slice(0, 3), ') ', valor.slice(3)].join('');
                        if (tamanho > 7) {
                            valor = [valor.slice(0, 10), '-', valor.slice(10)].join('');
                        }
                    }
                }

                this.value = valor.slice(0, 15);
            });
        }

        // Validação de senha em tempo real
        const novaSenha = document.getElementById('nova_senha');
        const confirmarSenha = document.getElementById('confirmar_senha');

        if (novaSenha && confirmarSenha) {
            function validarSenhas() {
                if (novaSenha.value !== confirmarSenha.value) {
                    confirmarSenha.style.borderColor = 'var(--error-color)';
                } else {
                    confirmarSenha.style.borderColor = 'var(--success-color)';
                }
            }

            novaSenha.addEventListener('input', validarSenhas);
            confirmarSenha.addEventListener('input', validarSenhas);
        }
    </script>
</body>

</html>