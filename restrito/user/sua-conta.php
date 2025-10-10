<?php include "../verifica_login.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua Conta</title>
    <link rel="shortcur icon" href="assets/logo.svg" />
    <!-- CSS -->
    <link rel="stylesheet" href="../css/user.css">
    <!-- Fontes Oswald, Jaro e Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">
    <!-- Icones Font Awesome -->
    <script src="https://kit.fontawesome.com/18b2c31938.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos Globais - Dark Mode com Cores Quentes */
        :root {
            --primary-color: #FFA500;
            --secondary-color: #FFD700;
            --dark-color: #242424;
            --light-color: #1E1E1E;
            --text-color: #E0E0E0;
            --text-dark: #333;
            --success-color: #4CAF50;
            --error-color: #F84949;
            --border-color: #333;
            --container-width: 1400px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--dark-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background-color: var(--light-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--primary-color);
        }

        .header-container {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        header a {
            text-decoration: none;
        }

        .logo {
            display: flex;
            gap: 12px;
        }

        .logo img {
            height: 50px;
        }

        .logo h1 {
            font-family: Jaro;
            font-weight: 400;
            color: var(--primary-color);
            font-size: 1.86rem;
            transition: all 0.5s ease;
        }

        .logo:hover {
            h1 {
                text-shadow: 0 0 15px #ffa50080;
            }
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 550px;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        nav a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        nav ul a:hover {
            color: var(--primary-color);
            background-color: rgba(255, 165, 0, 0.1);
        }

        .container-usuario {
            display: flex;
            flex-direction: column;
            align-items: end;
            position: relative;
        }

        #button-user {
            display: none;
        }

        .btn-usuario label {
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;

            svg {
                transition: all 0.3s ease;
            }
        }

        .btn-usuario label:hover {
            svg {
                fill: #FFA500;
            }

        }

        .opt-usuario {
            display: none;
            position: absolute;
            width: 220px;
            margin-top: 55px;
            padding: 15px;
            background-color: #242424;
            box-shadow: #1E1E1E 0px 3px 10px;
            border-radius: 4px;
        }

        .nome-usuario p {
            color: #ffa500;
            font-family: Rajdhani;
            font-weight: 700;
            font-size: 21px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .opt-usuario ul {
            display: flex;
            flex-direction: column;
            gap: 8px;
            list-style: inside;

            .opt-user-link:hover {
                background-color: #242424;
                font-weight: 600;

            }
        }

        .opt-usuario hr {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .sair-usuario {
            font-size: 16px;
        }

        .sair-usuario a {
            padding: 5px;
            padding-left: 10px;
            padding-right: 11px;
        }

        .sair-usuario a:hover {
            color: #FFA500;
            background-color: #ffa5001a;
        }

        #button-user:checked~.opt-usuario {
            display: block;
        }

        .titulo-pagina {
            text-align: center;
            margin: 20px 0 20px 0;
            font-family: Oswald;
            font-weight: 400;
            font-size: 43px;
            color: var(--primary-color);
        }

        /* Footer */
        footer {
            background-color: var(--light-color);
            color: var(--text-color);
            padding: 20px 0;
            text-align: center;
            border-top: 1px solid var(--primary-color);

        }

        footer .logo {
            align-self: center;
        }

        .footer-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            flex-direction: row;
            gap: 10px;
            height: 100px;
            width: 50%;
        }

        footer h2 {
            color: #FFA500;
            font-family: Oswald;
            font-weight: 400;
        }

        .copyright-logo-container {
            display: flex;
            justify-content: space-evenly;
            flex-direction: column;
            align-self: center;
            height: 100px;
        }

        .contactenos-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            height: 100px;
            width: 392px;
        }

        .social-links-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 5px;
        }

        .social-links {
            display: flex;
            padding: 4px;
            color: var(--text-color);
            font-weight: 300;
            font-size: 1.3rem;
            text-decoration: none;
            text-align: center;
            align-self: self-start;
        }

        .social-links p {
            font-family: Oswald;
            font-weight: 300;
        }

        .social-links svg {
            transition: all 0.3s ease;
        }

        .social-links svg:hover {
            fill: #FFA500;
        }

        .logo-conta {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-conta img {
            height: 50px;
        }

        .logo-conta h1 {
            font-family: 'Jaro', sans-serif;
            color: var(--primary-color);
            font-size: 28px;
        }

        .voltar-btn {
            background: var(--dark-color);
            color: var(--text-color);
            border: 2px solid var(--primary-color);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .voltar-btn:hover {
            background: var(--primary-color);
            color: #000;
        }

        .conta-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            margin: 20px 0 150px 0;
        }

        /* Menu Lateral */
        .menu-lateral {
            display: flex;
            flex-direction: column;
            background: var(--light-color);
            border-radius: 12px;
            padding: 10px;
            height: fit-content;
        }

        .menu-lateral h3 {
            color: var(--primary-color);
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .menu-lateral ul {
            list-style: none;
        }

        .menu-lateral a {
            display: block;
            padding: 12px 15px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 500;
        }

        .menu-lateral a:hover,
        .menu-lateral a.ativo {
            background: var(--primary-color);
            color: #000;
        }

        /* Conteúdo Principal */
        .conteudo-conta {
            background: var(--light-color);
            border-radius: 12px;
            padding: 30px;
        }

        .aba-conteudo {
            display: none;
            align-items: center;
        }

        .aba-conteudo.ativo {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 22px;
        }

        .aba-conteudo h2 {
            color: var(--primary-color);
            font-family: Rajdhani;
            font-weight: 700;
            font-size: 27px;
        }

        /* Formulários */
        .aba-conteudo.ativo form {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 22px;
        }

        .forms {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 3px;
            color: var(--primary-color);
            font-family: Rajdhani;
            font-size: 20px;
            font-weight: 600;
        }

        .save-cancel {
            display: flex;
            flex-direction: row;
            justify-content: center;
            width: 100%;

            .form-group {
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                gap: 10px;
            }
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: var(--dark-color);
            border: 2px solid #333;
            border-radius: 8px;
            color: var(--text-color);
            font-size: 18px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 500;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .form-control:read-only {
            background: #2a2a2a;
            color: #888;
        }

        .btn-editar {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .btn-editar:hover {
            background: var(--primary-color);
            color: #000;
        }

        .btn-salvar {
            background: var(--primary-color);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-salvar:hover {
            background: var(--secondary-color);

        }

        .btn-cancelar {
            background: transparent;
            color: var(--text-color);
            border: 2px solid #666;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancelar:hover {
            border-color: var(--text-color);
        }

        /* Mensagens */
        .mensagem {
            position: fixed;
            bottom: 23px;
            padding: 14px;
            border-radius: 8px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            backdrop-filter: blur(20px);
            opacity: 0;
        }

        .mensagem.sucesso {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .mensagem.erro {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        @keyframes sumir {
            0% {
                opacity: 0;
            }

            5% {
                opacity: 1;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .sumir {
            animation: sumir ease 4s
        }

        /* Informações do usuário */
        .info-usuario {
            background: var(--dark-color);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .info-valor {
            color: var(--text-color);
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .conta-container {
                grid-template-columns: 1fr;
            }

            .menu-lateral {
                order: 2;
            }

            .conteudo-conta {
                order: 1;
            }
        }
    </style>
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
                    <li><a href="../index.php">Início</a></li>
                    <li><a href="../index.php">Cardápio</a></li>
                    <!-- <li><a href="#">Monte sua Pizza</a></li> -->
                    <li><a href="../index.php">Carrinho</a></li>
                    <li><a href="../index.php">Esperando a Pizza?</a></li>
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
                                Olá, <?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Visitante'; ?>!
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
                        $check_sql = "SELECT id FROM clientes WHERE email = ? AND id != ?";
                        $check_stmt = mysqli_prepare($conn, $check_sql);
                        mysqli_stmt_bind_param($check_stmt, "si", $email, $id_usuario);
                        mysqli_stmt_execute($check_stmt);
                        mysqli_stmt_store_result($check_stmt);

                        if (mysqli_stmt_num_rows($check_stmt) > 0) {
                            $mensagem = "Este e-mail já está em uso por outro usuário.";
                            $tipo_mensagem = "erro";
                        } else {
                            // Atualizar dados
                            $update_sql = "UPDATE clientes SET nome = ?, telefone = ?, email = ? WHERE id = ?";
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
                        $check_senha_sql = "SELECT senha FROM clientes WHERE id = ?";
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
                            $update_senha_sql = "UPDATE clientes SET senha = ? WHERE id = ?";
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
                $sql = "SELECT nome, email, telefone FROM clientes WHERE id = ?";
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