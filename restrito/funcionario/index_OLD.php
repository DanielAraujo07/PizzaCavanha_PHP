<?php 
include "../verifica_login.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza do Cavanha</title>
    <link rel="shortcur icon" href="assets/logo.svg" />
    <!-- <link rel="stylesheet" href="css/style.css">  LINK DO CSS -->
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

        nav {
            display: flex;
            justify-content: flex-end;
            gap: 10%;
            align-items: center;
            width: 650px;
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
            width: 240px;
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

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--text-dark);
        }

        .btn-primary:hover {
            background-color: #e69500;
            transform: translateY(-2px);
            box-shadow: 0 0px 8px 4px rgba(255, 165, 0, 0.3);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background-color: #e6c700;
            transform: translateY(-2px);
            box-shadow: 0 0px 8px 4px rgba(255, 215, 0, 0.3);
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #FFA500;
            font-size: 1.2rem;
        }

        .sem-itens {
            text-align: center;
            padding: 40px;
            color: #aaa;
            font-style: italic;
        }

        .error-api {
            text-align: center;
            padding: 20px;
            background-color: #ffebee;
            color: #c62828;
            border-radius: 5px;
            margin: 10px 0;
        }

        /* Páginas */
        .page {
            display: none;
            padding: 40px 0;
        }

        .page.active {
            display: block;
        }

        /* Página Inicial */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
            border-radius: 10px;
            margin-bottom: 40px;
            border: 1px solid var(--primary-color);
            box-shadow: 0px 0px 27px 2px #FFA60070;
        }

        .hero h2 {
            font-family: Rajdhani;
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--primary-color);

        }

        .hero p {
            font-size: 1rem;
            color: #00000000;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .destaques {
            text-align: center;
            margin-bottom: 40px;
        }

        .destaques h2 {
            font-size: 2rem;
            font-family: Oswald;
            font-weight: 400;
            margin-bottom: 30px;
            color: var(--primary-color);
        }

        .pizzas-destaque {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .pizza-item {
            background-color: var(--light-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            max-width: 300px;
            border: 1px solid var(--border-color);
        }

        .pizza-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(255, 165, 0, 0.2);
            border-color: var(--primary-color);
        }

        .pizza-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .pizza-item h3 {
            padding: 15px;
            color: var(--primary-color);
        }

        .pizza-item p {
            padding: 0 15px 15px;
            color: #aaa;
        }

        /* Página Monte sua Pizza */
        .titulo-pagina {
            text-align: center;
            margin: -20px 0 40px 0;
            font-family: Oswald;
            font-weight: 400;
            font-size: 43px;
            color: var(--primary-color)
        }

        .pizza-builder-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .pizza-canvas-container {
            flex: 1;
            min-width: 300px;
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
        }

        #pizza-canvas {
            background-color: #2a2a2a;
            border-radius: 50%;
            max-width: 100%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .ingredientes-panel {
            flex: 1;
            min-width: 300px;
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
        }

        .tamanho-pizza h3,
        .ingredientes-list h3,
        .ingredientes-selecionados h3 {
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .tamanho-options {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .tamanho-options label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            color: var(--text-color);
        }

        .ingredientes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .ingrediente-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: none;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: rgba(255, 165, 0, 0.1);
        }

        .ingrediente-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            background-color: rgba(255, 165, 0, 0.2);
        }

        .ingrediente-btn img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .ingrediente-btn span {
            color: var(--text-color);
        }

        .ingredientes-selecionados ul {
            list-style: none;
            margin-top: 10px;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .ingredientes-selecionados li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .ingredientes-selecionados li:last-child {
            border-bottom: none;
        }

        .ingredientes-selecionados li button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: bold;
            cursor: pointer;
            padding: 0 5px;
        }

        .total-pedido {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid var(--primary-color);
            text-align: right;
        }

        .total-pedido h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        #btn-finalizar {
            background-color: var(--success-color);
            color: white;
            padding: 12px 25px;
            font-size: 1rem;
            margin-top: 15px;
        }

        #btn-finalizar:hover {
            background-color: #3d8b40;
        }

        #btn-finalizar:disabled {
            background-color: #555;
            cursor: not-allowed;
            transform: none;
        }

        /* Página Cardápio */

        .menu-categories {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .category-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            background-color: #333;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .category-btn.active {
            background-color: var(--primary-color);
            color: var(--text-dark);
        }

        .menu-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .menu-item {
            background-color: var(--light-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .menu-item:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .menu-item-info {
            padding: 20px;
        }

        .menu-item-info h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .menu-item-info p {
            color: #aaa;
            margin-bottom: 15px;
        }

        .menu-item-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .add-to-cart {
            background-color: var(--primary-color);
            color: var(--text-dark);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: #e69500;
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

        /* Página Carrinho */
        .carrinho h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }

        .carrinho-container {
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .carrinho-vazio {
            text-align: center;
            padding: 40px 0;
            color: #aaa;
        }

        .carrinho-itens {
            margin-bottom: 30px;
        }

        .carrinho-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .carrinho-item:last-child {
            border-bottom: none;
        }

        .item-info {
            width: 250px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .item-info img {
            border: var(--primary-color) solid 2px;
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 5px;
        }

        .item-detalhes h3 {
            color: var(--primary-color);
            font-size: 23px;
            font-family: Rajdhani;
            font-weight: 700;
            margin-bottom: -2px;
        }

        .item-detalhes p {
            color: #c7c7c7;
        }

        .item-detalhes .descricao {
            color: #FFF;
            line-height: 1.2;
            margin-bottom: 3px;
            font-size: 18px;
            font-weight: 600;
            font-family: Rajdhani;
        }

        .item-detalhes .observacao,
        .adicionais {
            font-style: italic;
            line-height: 1.2;
            max-width: 40ch;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 3px;
        }

        .item-detalhes .preco {
            color: #FFF;
            font-weight: bold;
            font-size: 18px;
            font-weight: 600;
            font-family: Rajdhani;
        }

        .item-acoes {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quantidade-controle {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantidade-controle button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FFA500;
            border: none;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: .3s all ease;

            p {
                font-weight: 700;
                font-size: 16px;
                color: #000;
                margin-bottom: 3px;
            }
        }

        .quantidade-controle button:hover {
            background-color: #ffc353ff;
        }

        .quantidade-controle span {
            min-width: 30px;
            text-align: center;
            font-family: Rajdhani;
            font-weight: 600;
            font-size: 18px;
            color: #FFF;
        }

        .remover-item {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .item-preco {
            font-family: Rajdhani;
            font-weight: 700;
            font-size: 24px;
            color: #FFA500;
        }

        .carrinho-total {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #FFA500;
        }

        .carrinho-total h3 {
            font-size: 1.8rem;
            font-family: Rajdhani;
            font-weight: 700;
            color: #FFA500;
        }

        .carrinho-acoes {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
        }

        .checkout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .checkout-overlay.ativo {
            display: flex;
        }

        .checkout-container {
            background: #1E1E1E;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .checkout-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid #333;
        }

        .checkout-header h2 {
            color: #FFA500;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 24px;
        }

        .fechar-checkout {
            background: none;
            border: none;
            color: #FFF;
            font-size: 30px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .fechar-checkout:hover {
            color: #FFA500;
        }

        .checkout-content {
            padding: 30px;
        }

        .checkout-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #333;
        }

        .checkout-section h3 {
            color: #FFA500;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 18px;
        }

        /* Opções de Entrega */
        .opcoes-entrega {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .opcao-entrega {
            flex: 1;
            min-width: 200px;
        }

        .opcao-entrega input[type="radio"] {
            display: none;
        }

        .opcao-entrega .opcao-content {
            background: #242424;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .opcao-entrega input[type="radio"]:checked+.opcao-content {
            border-color: #FFA500;
            background: rgba(255, 165, 0, 0.1);
        }

        .opcao-entrega .opcao-content h4 {
            color: #FFF;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .opcao-entrega .opcao-content p {
            color: #AAA;
            font-size: 14px;
        }

        /* Formulário de Endereço */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background: #242424;
            border: 2px solid #333;
            border-radius: 8px;
            color: #FFF;
            font-family: 'Rajdhani', sans-serif;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #FFA500;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Opções de Retirada */
        .opcoes-retirada {
            display: flex;
            gap: 15px;
        }

        .opcao-retirada {
            flex: 1;
        }

        .opcao-retirada input[type="radio"] {
            display: none;
        }

        .opcao-retirada .opcao-content {
            background: #242424;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .opcao-retirada input[type="radio"]:checked+.opcao-content {
            border-color: #FFA500;
            background: rgba(255, 165, 0, 0.1);
        }

        /* Formas de Pagamento */
        .opcoes-pagamento {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .opcao-pagamento {
            background: #242424;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .opcao-pagamento.selecionado {
            border-color: #FFA500;
            background: rgba(255, 165, 0, 0.1);
        }

        .opcao-pagamento h4 {
            color: #FFF;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            margin-bottom: 5px;
        }

        /* Resumo do Pedido */
        .resumo-pedido {
            background: #242424;
            border-radius: 12px;
            padding: 20px;
        }

        .item-resumo {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }

        .item-resumo:last-child {
            border-bottom: none;
        }

        .total-checkout {
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #FFA500;
            font-size: 18px;
            color: #FFA500;
        }

        /* Footer do Checkout */
        .checkout-footer {
            display: flex;
            justify-content: space-between;
            padding: 20px 30px;
            border-top: 1px solid #333;
            gap: 15px;
        }

        .checkout-footer .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-footer .btn-secondary {
            background: #333;
            color: #FFF;
        }

        .checkout-footer .btn-secondary:hover {
            background: #444;
        }

        .checkout-footer .btn-primary {
            background: #FFA500;
            color: #000;
            font-weight: 700;
        }

        .checkout-footer .btn-primary:hover {
            background: #FFB733;
            transform: translateY(-2px);
        }

        .checkout-footer .btn-primary:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }

        .confirmacao-container {
            background-color: #1E1E1E;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 0 20px rgba(255, 165, 0, 0.3);
            transform: translateY(20px);
            transition: transform 0.3s ease;
            border: 2px solid #FFA500;
        }

        .confirmacao-overlay.ativo .confirmacao-container {
            transform: translateY(0);
        }

        .confirmacao-container img {
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
            animation: pulse 1.5s infinite;
        }

        .confirmacao-container h2 {
            color: #FFA500;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        .confirmacao-container p {
            color: #E0E0E0;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .confirmacao-container-cart {
            background-color: #1E1E1E;
            position: relative;
            border-radius: 15px;
            padding: 30px;
            max-width: 700px;
            width: 90%;
            height: 35%;
            text-align: center;
            transform: translateY(20px);
            transition: transform 0.3s ease;
            border: 2px solid #FFA500;
            overflow: hidden;
        }

        .confirmacao-container-cart img {
            width: 300px;
            animation: cart-run 3.5s infinite;
            position: absolute;
        }

        .confirmacao-container-cart h2 {
            color: #FFA500;
            margin-top: 275px;
            font-size: 2.5rem;
        }

        .confirmacao-container-cart p {
            color: #E0E0E0;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .confirmacao-container button {
            background-color: #FFA500;
            color: #2c3e50;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .confirmacao-container button:hover {
            background-color: #FFD700;
            transform: translateY(-2px);
        }

        .confirmacao-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #0E0E0E73;
            backdrop-filter: blur(12px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .confirmacao-overlay.ativo {
            display: flex;
            opacity: 1;
        }

        .overlay-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            width: 100%;
            padding: 20px;
        }

        .overlay-content h2 {
            color: #FFA500;
            font-size: 33px;
            margin-top: -40px;
            font-family: Oswald;
            font-weight: 400;
            text-shadow: 0px 4px 4px #0000001C;
        }

        /* "Personalize sua Pizza" */

        .adicionais-overlay {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #1d1d1dc7;
            padding: 15px;
            height: 720px;
            border-radius: 39px;
            box-shadow: 0px 4px 4px #00000040;
        }

        .adicionais-overlay .overlay-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 720px;
            margin: 0;
            padding: 0;

            h2 {
                margin-top: 5px;
            }
        }

        .popup-sections {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 30px;
        }

        .tamanho-section,
        .adicionais-section {
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .adicionais-overlay .popup-sections h3 {
            color: #FFA500;
            font-family: Rajdhani;
            font-size: 27px;
            font-weight: 600;
            margin-bottom: -5px;
        }

        .tamanho-options-popup {
            display: flex;
            flex-direction: row;
            gap: 10px;
            height: 98px;
            width: 607px;
            background-color: #2a2a2a;
            border-radius: 10px;
            border: 1px solid #333;
            padding: 5px;
        }

        .tamanho-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 20px 10px 20px;
            border: 2px solid #333;
            border-radius: 10px;
            background-color: #242424;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            gap: 2px;
        }

        .tamanho-option:hover {
            border-color: #FFA500;
            transform: translateY(-2px);
        }

        .tamanho-option.selecionado {
            border-color: #FFA500;
            background-color: #ffa5001a;

            hr {
                color: #ffa500;
                width: 90%;
            }
        }

        .tamanho-option .nome {
            font-family: Rajdhani;
            font-weight: 600;
            font-size: 21px;
            color: #FFA500;
        }

        .tamanho-option .preco {
            color: #FFF;
            font-size: 18px;
            font-family: Rajdhani;
            font-weight: 500;
        }

        .tamanho-option .descricao {
            color: #aaa;
            font-size: 0.8em;
        }

        .tamanho-option .nome-preco {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tamanho-option hr {
            width: 0%;
            color: #00000000;
            transition: all 0.3s ease;
        }

        .tamanho-checkbox {
            display: none;
        }

        .adicionais-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: 250px;
            overflow-y: auto;
            padding: 6px;
            border: 2px solid #333;
            border-radius: 10px 10px 0 0;
            background-color: #2a2a2a;
        }

        .item-adicionais-info {
            flex-direction: row;
        }

        .adicional-item {
            display: flex;
            flex-direction: row;
            align-items: center;
            height: 46px;
            padding: 15px;
            border: 2px solid #333;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            background-color: #242424;
        }

        .adicional-item:hover {
            border-color: #FFA500;
        }

        .adicional-item.selecionado {
            border-color: #FFA500;
            background-color: rgba(255, 165, 0, 0.1);
        }

        .adicional-item .nome-preco {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .adicional-item .nome {
            font-family: Rajdhani;
            font-size: 23px;
            font-weight: 600;
            color: #FFA500;
        }

        .adicional-item .preco {
            font-family: Rajdhani;
            font-size: 18px;
            font-weight: 500;
            color: #FFF;
        }

        .adicional-checkbox {
            display: none;
        }

        .adicionais-resumo {
            display: flex;
            flex-direction: column;
            width: 607px;
            height: 150px;
            background-color: #242424;
            padding: 5px 12px;
            border-radius: 0 0 10px 10px;
            border: 2px solid #333;
            border-top-width: 0;
        }

        .adicionais-resumo .resumo-info {
            display: flex;
            flex-direction: row;
            justify-content: space-between;

            h3 {
                font-family: Rajdhani;
                font-size: 24px;
                font-weight: 600;
                color: #FFA500;
            }

            .left {
                display: flex;
                flex-direction: column;
            }

            .right {
                display: flex;
                flex-direction: column;
            }
        }



        .resumo-item {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .resumo-item {

            .item-nome,
            .item-preco {
                font-family: Rajdhani;
                font-size: 20px;
                font-weight: 500;
                color: #FFF;
            }
        }

        .tamanho-selecionado {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: 95%;
            font-size: 0.9em;
            color: #FFF;
            border-bottom: 1px solid #444;
        }

        .tamanho-selecionado:empty {
            display: none;
        }


        .adicionais-selecionados {
            min-height: 60px;
            max-height: 100px;
            overflow-y: auto;
            width: 300px;
        }


        .adicional-selecionado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #444;
            font-size: 0.9em;
            color: #FFF;
        }

        .adicional-selecionado:last-child {
            border-bottom: none;
        }

        .adicional-selecionado .remover {
            color: #FF4444;
            cursor: pointer;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .adicional-selecionado .remover:hover {
            background-color: #FF4444;
            color: white;
        }

        .total-adicionais {
            text-align: right;
            font-size: 24px;
            border-top: 2px solid #FFA500;
            color: #FFA500;
        }

        .adicionais-botoes {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .overlay-content .voltar {
            width: 100%;
        }

        .btn-voltar {
            position: fixed;
            background-color: #00000000;
            width: 28px;
            height: 28px;
            font-weight: 100;
            font-size: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;

            i {
                transition: ease .3s all;
                color: #333;
            }
        }

        .btn-voltar:hover {
            i {
                color: #FFA500;
            }
        }

        .btn-continuar {
            background-color: #FFA500;
            color: black;
        }

        .btn-continuar:hover {
            background-color: #FF8C00;
        }

        .btn-continuar:disabled {
            background-color: #666;
            cursor: not-allowed;
        }

        .observacao-botoes {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 15px;
        }

        /* Scrollbar personalizada */
        .adicionais-container::-webkit-scrollbar {
            width: 5px;
        }

        .adicionais-container::-webkit-scrollbar-track {
            background: #2a2a2a;
        }

        .adicionais-container::-webkit-scrollbar-thumb {
            background: #FFA500;
            border-radius: 4px;
        }

        .adicionais-container::-webkit-scrollbar-thumb:hover {
            background: #FF8C00;
        }

        .obs-overlay {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            background-color: #1d1d1dc7;
            width: 520px;
            height: 460px;
            border-radius: 39px;
            box-shadow: 0px 4px 4px #00000040;
        }

        .obs-overlay form {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 328px;
            width: 422px;
        }

        textarea {
            height: 254px;
            width: 420px;
            padding: 5%;
            resize: none;
            outline: none;
            border: #242424 1px solid;
            font-size: 17px;
            box-shadow: 0px 3px 4px #00000040;
            line-height: 1.5;
            background-color: #242424;
            color: #fff;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        textarea:hover {
            border: #FFA500 1px solid;
            box-shadow: 0px 0px 20px #ffa5004d;
        }

        textarea:not(:placeholder-shown) {
            border: #FFA500 1px solid;
            box-shadow: 0px 0px 20px #ffa5004d;
        }

        .post-content-button {
            height: 50px;
            width: 213px;
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

        @keyframes cart-run {
            0% {
                /* transform: translateX(0%); */
                left: -400px;
            }

            100% {
                /* transform: translateX(100%); */
                left: calc(100% + 100px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        .sumir {
            animation: sumir ease .5s
        }

        /* Página Cardápio */
        .cardapio h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }

        .menu-categories {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .category-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            background-color: #333;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .category-btn.active {
            background-color: var(--primary-color);
            color: var(--text-dark);
        }

        .menu-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .menu-item {
            background-color: var(--light-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .menu-item:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .menu-item-info {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .menu-item-info h3 {
            color: #FFA500;
            margin-bottom: 4px;
            font-size: 24px;
            font-weight: 700;
            font-family: Rajdhani;
        }

        .menu-item-info p {
            color: #aaa;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .menu-item-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-weight: bold;
            font-family: Rajdhani;
            font-size: 1.3rem;
            color: #FFA500;
        }

        .add-to-cart {
            background-color: var(--primary-color);
            color: var(--text-dark);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: var(--secondary-color);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #e69500;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero {
                padding: 60px 20px;
            }

            .hero h2 {
                font-size: 2rem;
            }

            .carrinho-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .item-acoes {
                width: 100%;
                justify-content: space-between;
            }

            .menu-items {
                grid-template-columns: 1fr;
            }

            .pizza-builder-container {
                flex-direction: column;
            }

            #pizza-canvas {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="#" class="nav-link" data-page="home">
                <div class="logo">
                    <img src="../assets/logo.svg" alt="Logo">
                    <h1>Pizza do Cavanha</h1>
                </div>
            </a>
            <nav>
                <ul>
                    <li><a href="#" class="nav-link">Início</a></li>
                                        <?php if ($_SESSION['class_nivel'] >= 4): ?>
                        <li><a href="admin/">Painel Admin</a></li>
                    <?php endif; ?>
                                                <?php if ($_SESSION['class_nivel'] >= 2): ?>
                                <li><a href="funcionario/pedidos.php" class="opt-user-link">Gerenciar Pedidos</a></li>
                            <?php endif; ?>

                            <?php if ($_SESSION['class_nivel'] >= 3): ?>
                                <li><a href="funcionario/cozinha.php" class="opt-user-link">Cozinha</a></li>
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
                            <li><a href="user/sua-conta.php" class="opt-user-link">Sua Conta</a></li>
                            <li><a href="user/seus-pedidos.php" class="opt-user-link">Seus Pedidos</a></li>
                            <li><a href="../index.php" class="opt-user-link">Voltar à Home</a></li>

                            <!-- Links extras para funcionários -->
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
    TELA DOS FUNCIONÁRIOS
    <script>
                function showPage(pageId) {
            // Esconder todas as páginas
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });

            // Mostrar a página selecionada
            document.getElementById(pageId).classList.add('active');

            // Rolagem suave para o topo
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>