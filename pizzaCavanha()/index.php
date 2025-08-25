<?php
// Adicione no início do arquivo (antes de qualquer HTML)
session_start();

// Processar Login
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $senha = hash('sha512', $_POST['senha']);

            include "restrito/conexao.php";

            $sql = "SELECT * FROM `clientes` WHERE email = '$email' AND senha = '$senha'";

            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) == 1) {
                session_start();
                $_SESSION['email'] = $email;
                header("location: restrito");
            } else {
                echo "<div class='alert alert-danger mt-3 text-center'>Usuário ou Senha inválidos!</div>";
            }
        }

// Processar Cadastro
if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $nomeCorrigido = ucwords(strtolower($nome));
    $nome = $nomeCorrigido;

    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = hash('sha512', $_POST['senha']);
    
    include "restrito/conexao.php";
    
    // Verificar se email já existe
    $check_sql = "SELECT email FROM clientes WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $cadastro_error = "Este e-mail já está cadastrado!";
    } else {
        // Inserir novo usuário
        $insert_sql = "INSERT INTO clientes (nome, senha, email, telefone) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "ssss", $nome, $senha, $email, $telefone);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $cadastro_success = "Cadastro realizado com sucesso!";
            // Redirecionar ou mostrar mensagem
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
    <link rel="shortcur icon" href="restrito/assets/logo.svg" />
    <title>Seja bem Vindo!</title>
    <!-- Fontes Oswald, Jaro e Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">
    <style>
        /* Página de Login */
        :root {
            --primary-color: #FFA500;
            --secondary-color: #FFD700;
            --dark-color: #242424;
            --light-color: #1E1E1E;
            --text-color: #E0E0E0;
            --text-dark: #333;
            --success-color: #4CAF50;
            --border-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url(restrito/assets/fundoPizza.png);
            background-size: cover;
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Páginas */
        .page {
            display: none;
            padding: 40px 0;
        }

        .page.active {
            display: block;
        }
        
        .container-popup {
            display: flex;
            height: 90vh;
            width: 100vw;
            justify-content: center;
            align-items: center;
        }

        .popup-login{
            display: flex;
            flex-direction: column;
            height: 700px;
            width: 526px;
            background-color: #1E1E1E;
            border-radius: 35px;
            box-shadow: 0px 4px 4px #00000040;
            justify-content: space-evenly;
        }

        .popup-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 630px;
            align-items: center;
        }

        .header-popup {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            height: 180px;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            height: 550px;
        }

        .header-popup a {
            text-decoration: none;
            width: 330px;
            height: 72px;
        }

        .logo-login {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            gap: 10px;
        }

        .logo-login img {
            height: 68px;
        }

        .logo-login h1 {
            font-family: Jaro;
            font-weight: 400;
            color: var(--primary-color);
            font-size: 35px;
            text-shadow: 0 0 7px rgba(255, 165, 0, 0.5);
        }
        
        .header-popup .line-1 {
            width: 360px; 
            height: 1px; 
            box-shadow: 0px 0px 8px #FFA500; 
            margin-top: 8px;
            outline: 1px #FFA520 solid; 
            outline-offset: -1px;
        }

        .nome-popup h2 {
            color: #FFA500;
            font-size: 30px;
            font-family: Rajdhani;
            font-weight: 700;
            line-height: 46px;
            word-wrap: break-word;
            text-shadow: 0px 0px 30px #fff2db40;
        }

        .tipo-input h3 {
            color: #FFA520;
            font-size: 20px;
            font-family: Oswald;
            font-weight: 400;
            line-height: 46px;
            margin-left: 14px;
            word-wrap: break-word;
        }

        .input-group {
            margin-bottom: 10px;
        }

        .input-group input {
            outline: #242424 solid 2px;
            border: none;
            padding-left: 10px;
            color: #D9D9D9;
            font-family: Rajdhani;
            font-weight: 600;
            font-size: 20px;
            width: 415px;
            height: 55px;
            background-color: #242424; 
            box-shadow: 0px 2px 4px #00000040; 
            border-radius: 12px;
            transition: ease 0.5s;
        }

        .input-group input:focus {
            outline: #FFA500 solid 2px;
        }

        .nome-telefone {
            display: flex;
            flex-direction: row;
            width: 415px;
            justify-content: space-between;
        }

        .buttons-form {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 90px;
        }

        .post-content-button {
            height: 55px;
            width: 305px;
            border-radius: 36px;
            border: #242424 solid 2px;
            background-color: #242424;
            color: #fff;
            font-family: Rajdhani;
            font-size: 26px;
            font-weight: 500;
            box-shadow: 0px 3px 4px #00000040;
            transition: all 0.5s ease;
        }

        .post-content-button:hover {
            border: #ffA500 solid 2px;
            color: #FFA500;
            transform: translateY(-2px);
        }

        .buttons-form a {
            text-decoration: none;
            color: #FFF;
            font-family: Oswald;
            font-weight: 400;
            font-size: 18px;
            transition: .3s;
        }

        .buttons-form a:hover {
            color: #FFA500;
        }

        /* Cor de fundo do autocomplete */
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #242424 inset;
            -webkit-text-fill-color: #D9D9D9 !important;
            border: #FFA500 solid 2px;
        }

    </style>
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
                                <input type="email" class="form-control" name="email" placeholder="" maxlength="128" required>
                            </div>
                            <div class="input-group">
                                <div class="tipo-input">
                                    <h3>Senha</h3>
                                </div>
                                <input type="password" class="form-control" name="senha" placeholder="" maxlength="255" required style="font-size: 15px;">
                            </div>
                        </div>
                        <div class="error-message" style="margin: 12px; text-align: center; font-family: Oswald; font-size: 19px;"></div>
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
                                <input type="email" class="form-control" name="email" placeholder="" maxlength="64" required>
                            </div>
                            <div class="input-group">
                                <div class="tipo-input">
                                    <h3>Senha</h3>
                                </div>
                                <input type="password" class="form-control" name="senha" placeholder="" maxlength="255" required style="font-size: 15px;">
                            </div>
                        </div>
                        <div class="error-message" style="margin: 12px; text-align: center; font-family: Oswald; font-size: 19px;"></div>
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
    // Função para mostrar a página selecionada
    function showPage(pageId) {
        // Esconde todas as páginas
        document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
        });
        
        // Mostra a página solicitada
        document.getElementById(pageId).classList.add('active');
    }

    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');
            showPage(page);
        });
    });

let campoTelefone = document.querySelector(".form-control-telefone");
// Função para formatar o telefone
function formatarTelefone() {
    let valor = campoTelefone.value.replace(/\D/g, ''); // Remove tudo que não é dígito
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
    
    campoTelefone.value = valor.slice(0, 15); // Limita ao máximo de 15 caracteres
}
// Evento para formatar enquanto digita
campoTelefone.addEventListener('input', formatarTelefone);
// Evento para validar a entrada (permite apenas números e formatação)
campoTelefone.addEventListener('keypress', function(e) {
    // Permite: números (0-9), backspace, tab, enter, setas, delete
    // e os caracteres especiais que fazem parte da formatação ( ) - e espaço
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



</script>
</html>