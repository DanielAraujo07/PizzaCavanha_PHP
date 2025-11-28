<?php
// ATIVAR EXIBIÇÃO DE ERROS (remova depois de corrigir)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
include "restrito/conexao.php";

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // Redirecionar baseado no nível de acesso
    if ($_SESSION['class_nivel'] >= 2) {
        header("Location: restrito/funcionario/");
    } else {
        header("Location: restrito/index.php");
    }
    exit();
}

// Processar Login
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $senha = hash('sha512', $_POST['senha']);

    // Buscar usuário com informações da classe
    $sql = "SELECT u.*, uc.nome as class_nome, uc.nivel as class_nivel 
            FROM users u 
            JOIN user_classes uc ON u.class_id = uc.id 
            WHERE u.email = ? AND u.senha = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $senha);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $usuario = mysqli_fetch_assoc($result);
        
        // Configurar sessão
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['class_id'] = $usuario['class_id'];
        $_SESSION['class_nome'] = $usuario['class_nome'];
        $_SESSION['class_nivel'] = $usuario['class_nivel'];
        $_SESSION['logado'] = true;

        // Funcionários
        if ($usuario['class_nivel'] >= 2) {
            header("Location: restrito/funcionario/");
        } 
        // Clientes
        else {
            header("Location: restrito/index.php");
        }
        exit();
        
    } else {
        $login_error = "Usuário ou Senha Inválidos";
    }
}

// Processar Cadastro (sempre cria como Cliente - nivel 1)
if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $nomeCorrigido = ucwords(strtolower($nome));
    $nome = $nomeCorrigido;

    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = hash('sha512', $_POST['senha']);
    $class_id = 1; // Sempre cliente para novos cadastros

    // Verificar se email já existe
    $check_sql = "SELECT email FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $login_error = "Este E-mail já foi cadastrado";
    } else {
        // Inserir novo usuário como Cliente
        $insert_sql = "INSERT INTO users (nome, senha, email, telefone, class_id) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "ssssi", $nome, $senha, $email, $telefone, $class_id);

        if (mysqli_stmt_execute($insert_stmt)) {
            $cadastro_success = "Cadastro realizado com sucesso!";
            // Não redireciona automaticamente, mostra mensagem de sucesso
        } else {
            $cadastro_error = "Erro ao cadastrar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seja bem Vindo!</title>
    <link rel="shortcur icon" href="restrito/assets/logo.svg" />
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

    <link rel="stylesheet" href="restrito/assets/css/login.css">
</head>

<body>
    <!-- Página Login -->
    <section id="login" class="page active">
        <div class="container-popup">
            <div class="popup-login">
                <div class="popup-content">
                    <div class="header-popup">
                        <div class="logo-login">
                            <img src="restrito/assets/logo.svg" alt="Logo">
                            <h1>Pizza do Cavanha</h1>
                        </div>
                        <div class="line-1"></div>
                        <div class="nome-popup">
                            <h2>LOGIN</h2>
                        </div>
                    </div>
                    <form action="index.php" method="POST">
                        <div class="container-inputs">
                            <div class="input-group">
                                <div class="tipo-input">
                                    <h3>E-mail</h3>
                                </div>
                                <input type="email" class="form-control" name="email" placeholder="user@email.com" maxlength="64" required>
                            </div>
                            <div class="input-group">
                                <div class="tipo-input">
                                    <h3>Senha</h3>
                                </div>
                                <input type="password" class="form-control" name="senha" placeholder="" maxlength="255" required style="font-size: 15px;">
                            </div>
                        </div>
                        <div class="sumir error-message"><?php if (isset($login_error)) echo $login_error; ?></div>
                        <div class="buttons-form">
                            <button type="submit" class="post-content-button">ENTRAR</button>
                            <div class="link-popup">
                                <a href="#" class="nav-link" data-page="cadastro">Ainda não tenho uma conta</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Página Cadastro -->
    <section id="cadastro" class="page">
        <div class="container-popup">
            <div class="popup-login">
                <div class="popup-content">
                    <div class="header-popup">
                        <div class="logo-login">
                            <img src="restrito/assets/logo.svg" alt="Logo">
                            <h1>Pizza do Cavanha</h1>
                        </div>
                        <div class="line-1"></div>
                        <div class="nome-popup">
                            <h2>CADASTRE-SE</h2>
                        </div>
                    </div>
                    <form action="index.php" method="POST">
                        <div class="container-inputs">
                            <div class="nome-telefone">
                                <div class="input-group">
                                    <div class="tipo-input">
                                        <h3>Nome</h3>
                                    </div>
                                    <input type="text" class="form-control-nome" name="nome" placeholder="Nome e Sobrenome" maxlength="45" required style="width: 240px">
                                </div>
                                <div class="input-group">
                                    <div class="tipo-input">
                                        <h3>Telefone</h3>
                                    </div>
                                    <input type="tel" class="form-control-telefone" name="telefone" placeholder="(11) 99999-9999" maxlength="15" inputmode="numeric" required style="width: 160px">
                                </div>
                            </div>
                            <!-- Tirando o auto complete do email -->
                            <input type="email" name="fake-email" style="display: none;">
                            <div class="input-group">
                                <div class="tipo-input">
                                    <h3>E-mail</h3>
                                </div>
                                <input type="email" class="form-control" name="email" placeholder="user@email.com" maxlength="64" required>
                            </div>
                            <div class="input-group">
                                <div class="tipo-input">
                                    <h3>Senha</h3>
                                </div>
                                <input type="password" class="form-control" name="senha" placeholder="" maxlength="255" required style="font-size: 15px;">
                            </div>
                        </div>
                        <div class="error-message"></div>
                        <div class="buttons-form">
                            <button type="submit" class="post-content-button">CADASTRAR</button>
                            <div class="link-popup">
                                <a href="#" class="nav-link" data-page="login">Eu já tenho uma conta</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
    <script>
        // Navegação entre páginas
        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            document.getElementById(pageId).classList.add('active');
        }

        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                showPage(page);
            });
        });

        let campoTelefone = document.querySelector(".form-control-telefone");
        
        function formatarTelefone() {
            let valor = campoTelefone.value.replace(/\D/g, '');
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

            campoTelefone.value = valor.slice(0, 15);
        }
        
        campoTelefone.addEventListener('input', formatarTelefone);
        
        campoTelefone.addEventListener('keypress', function(e) {
            if (e.key.match(/[0-9()\-\s]/) ||
                e.key === 'Backspace' ||
                e.key === 'Tab' ||
                e.key === 'Enter' ||
                e.key === 'ArrowLeft' ||
                e.key === 'ArrowRight' ||
                e.key === 'Delete') {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        });

        // Se houve cadastro com sucesso, volta para a página de login
        <?php if (isset($cadastro_success)): ?>
            setTimeout(() => {
                showPage('login');
            }, 1000);
        <?php endif; ?>
    </script>

</html>