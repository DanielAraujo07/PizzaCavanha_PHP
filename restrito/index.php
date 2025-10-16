<?php
include "verifica_login.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza do Cavanha</title>
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
            box-shadow: 0 2px 10px #0000004d;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--primary-color);
        }
        header a {
            text-decoration: none;
        }

        .header-container {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        nav {
            display: flex;
            justify-content: flex-end;
            gap: 10%;
            align-items: center;
            width: 800px;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 10px;
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
                    <img src="assets/logo.svg" alt="Logo">
                    <h1>Pizza do Cavanha</h1>
                </div>
            </a>
            <nav>
                <ul>
                    <li><a href="#" class="nav-link" data-page="home">Início</a></li>
                    <li><a href="#" class="nav-link" data-page="cardapio">Cardápio</a></li>
                    <li><a href="#" class="nav-link" data-page="carrinho">Carrinho</a></li>

                    <?php if ($_SESSION['class_nivel'] !== 1): ?>
                        <li><a href="funcionario/index.php">Área do Funcionário</a></li>
                    <?php endif; ?>

                    <?php if (($_SESSION['class_nivel'] == 1) || ($_SESSION['class_nivel'] == 6)): ?>
                        <li><a href="pong.php">Esperando a Pizza?</a></li>
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
                        </ul>
                        <hr>
                        <div class="sair-usuario">
                            <a href="../logout.php">Sair</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Página Inicial -->
    <section id="home" class="page active">
        <div class="container">
            <div class="hero">
                <h2>Deu fome? Deixa o Cavanha cuidar.</h2>
                <p>.</p>
                <div class="hero-buttons-container">
                    <a href="#" class="btn btn-primary nav-link" data-page="monte-sua-pizza">Monte sua Pizza</a>
                    <a href="#" class="btn btn-secondary nav-link" data-page="cardapio">Ver Cardápio</a>
                </div>
            </div>

            <div class="destaques">
                <h2>Nossos Destaques</h2>
                <div class="pizzas-destaque">
                    <div class="pizza-item">
                        <img src="https://images.unsplash.com/photo-1595854341625-f33ee10dbf94?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                            alt="Pizza Margherita">
                        <h3>Margherita</h3>
                        <p>Molho de tomate, mussarela fresca e manjericão</p>
                    </div>
                    <div class="pizza-item">
                        <img src="https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Pizza Pepperoni">
                        <h3>Pepperoni</h3>
                        <p>Molho de tomate, mussarela e pepperoni</p>
                    </div>
                    <div class="pizza-item">
                        <img src="https://images.unsplash.com/photo-1552539618-7eec9b4d1796?q=80&w=1402&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Pizza Vegetariana">
                        <h3>Vegetariana</h3>
                        <p>Molho de tomate, queijo vegano, tomate e legumes frescos</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Página Monte sua Pizza -->
    <section id="monte-sua-pizza" class="page">
        <div class="container">
            <h1 class="titulo-pagina">Monte sua Pizza</h1>

            <div class="pizza-builder-container">
                <div class="pizza-canvas-container">
                    <canvas id="pizza-canvas" width="488" height="488"></canvas>
                </div>

                <div class="ingredientes-panel">
                    <div class="tamanho-pizza">
                        <h3>Tamanho:</h3>
                        <div class="tamanho-options">
                            <label>
                                <input type="radio" name="tamanho" value="pequena">
                                Pequena
                            </label>
                            <label>
                                <input type="radio" name="tamanho" value="media" checked>
                                Média
                            </label>
                            <label>
                                <input type="radio" name="tamanho" value="grande">
                                Grande
                            </label>
                        </div>
                    </div>

                    <div class="ingredientes-list">
                        <h3>Ingredientes:</h3>
                        <div class="ingredientes-grid" id="ingredientes-grid">
                            <!-- Os ingredientes serão adicionados via Database -->
                        </div>
                    </div>

                    <div class="ingredientes-selecionados">
                        <h3>Sua Pizza:</h3>
                        <ul id="ingredientes-selecionados-list">
                            <!-- Os ingredientes selecionados serão adicionados aqui -->
                        </ul>
                    </div>

                    <div class="total-pedido">
                        <h3>Total: R$<span id="total-pedido">30.00</span></h3>
                        <button id="btn-finalizar" class="btn" disabled>Adicionar ao Carrinho</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Página Cardápio -->
    <section id="cardapio" class="page">
        <div class="container">
            <h1 class="titulo-pagina">Nosso Cardápio</h1>

            <div class="menu-categories"> <!-- Adicionar novos produtos sem ser pizza -->
                <button class="category-btn active" data-category="todas">Todas</button>
                <button class="category-btn" data-category="salgadas">Salgadas</button>
                <button class="category-btn" data-category="doces">Doces</button>
                <button class="category-btn" data-category="vegetarianas">Vegetarianas</button>
                <button class="category-btn" data-category="sobremesas">Sobremesas</button>
                <button class="category-btn" data-category="bebidas">Bebidas</button>

            </div>

            <div class="menu-items" id="menu-items">
                <!-- Os itens do cardápio serão adicionados via Database -->
            </div>
        </div>
    </section>

    <!-- Página Carrinho -->
    <section id="carrinho" class="page">
        <div class="container">
            <h1 class="titulo-pagina">Seu Carrinho</h1>

            <div class="carrinho-container">
                <div class="carrinho-itens" id="carrinho-itens">
                    <!-- Itens do carrinho serão adicionados aqui -->
                </div>

                <div class="carrinho-total">
                    <h3>Total: R$<span id="carrinho-total" style="font-size: 1.8rem; font-family: Rajdhani; font-weight: 700; color: #FFA500;">0.00</span></h3>
                </div>

                <div class="carrinho-acoes">
                    <a href="#" class="nav-link" data-page="home"><button class="btn btn-secondary"
                            id="continuar-comprando">Continuar Comprando</button></a>
                    <button class="btn btn-primary" id="finalizar-pedido">Finalizar Pedido</button>
                </div>
            </div>
        </div>

        <!-- Popup de Checkout (NOVO) -->
        <div class="confirmacao-overlay" id="checkout-overlay">
            <div class="checkout-container">
                <div class="checkout-header">
                    <h2>Finalizar Pedido</h2>
                    <button class="fechar-checkout" id="fechar-checkout">×</button>
                </div>

                <div class="checkout-content">
                    <!-- Opções de Entrega -->
                    <div class="checkout-section">
                        <h3>Opção de Entrega</h3>
                        <div class="opcoes-entrega">
                            <label class="opcao-entrega">
                                <input type="radio" name="tipo-entrega" value="2" checked>
                                <div class="opcao-content">
                                    <h4>🛵 Retirar na Loja</h4>
                                    <p>Busque seu pedido no estabelecimento</p>
                                </div>
                            </label>

                            <label class="opcao-entrega">
                                <input type="radio" name="tipo-entrega" value="1">
                                <div class="opcao-content">
                                    <h4>🚚 Delivery</h4>
                                    <p>Entregamos no seu endereço</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Formulário de Endereço (aparece apenas para delivery) -->
                    <div class="checkout-section" id="form-endereco" style="display: none;">
                        <h3>Endereço de Entrega</h3>
                        <form id="form-dados-entrega">
                            <div class="form-group">
                                <input type="text" name="cidade" placeholder="Cidade" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="distrito" placeholder="Distrito" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="bairro" placeholder="Bairro" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="rua" placeholder="Rua" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="numero" placeholder="Número" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="complemento" placeholder="Complemento (opcional)">
                            </div>
                            <div class="form-group">
                                <textarea name="observacao" placeholder="Observações para a entrega (opcional)" rows="3"></textarea>
                            </div>
                        </form>
                    </div>

                    <!-- Opções de Retirada (aparece apenas para retirada) -->
                    <div class="checkout-section" id="opcoes-retirada">
                        <h3>Como prefere retirar?</h3>
                        <div class="opcoes-retirada">
                            <label class="opcao-retirada">
                                <input type="radio" name="tipo-retirada" value="mesa" checked>
                                <div class="opcao-content">
                                    <h4>🍽️ Entrega na Mesa</h4>
                                    <p>Nós levamos seu pedido até sua mesa</p>
                                </div>
                            </label>

                            <label class="opcao-retirada">
                                <input type="radio" name="tipo-retirada" value="balcao">
                                <div class="opcao-content">
                                    <h4>🏪 Buscar no Balcão</h4>
                                    <p>Você retira pessoalmente no balcão</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Forma de Pagamento -->
                    <div class="checkout-section">
                        <h3>Forma de Pagamento</h3>
                        <div class="opcoes-pagamento" id="opcoes-pagamento">
                            <!-- As opções serão carregadas via JavaScript -->
                        </div>
                    </div>

                    <!-- Resumo do Pedido -->
                    <div class="checkout-section resumo-pedido">
                        <h3>Resumo do Pedido</h3>
                        <div id="resumo-checkout">
                            <!-- Resumo será preenchido via JavaScript -->
                        </div>
                        <div class="total-checkout">
                            <strong>Total: R$<span id="total-checkout">0.00</span></strong>
                        </div>
                    </div>
                </div>

                <div class="checkout-footer">
                    <button class="btn btn-secondary" id="voltar-carrinho">Voltar</button>
                    <button class="btn btn-primary" id="confirmar-pedido">Confirmar Pedido</button>
                </div>
            </div>
        </div>

        <!-- Popup de Confirmação (já existente) -->
        <div class="confirmacao-overlay" id="confirmacao-overlay-carrinho">
            <div class="confirmacao-container">
                <img src="assets/seloCavanha_Recomendado.png" alt="Pedido Confirmado" class="fade-in">
                <h2 class="fade-in">Pedido Confirmado!</h2>
                <p id="confirmacao-texto-carrinho" class="fade-in"></p>
                <button id="confirmacao-btn-carrinho" class="fade-in">Voltar ao Início</button>
            </div>
        </div>
    </section>


    <!-- Popup de Tamanho e Adicionais -->
    <div class="confirmacao-overlay" id="confirmacao-overlay-adicionais">
        <div class="adicionais-overlay">
            <div class="overlay-content">
                <div class="voltar">
                    <button type="button" class="btn-voltar" id="btn-voltar-adicionais"><i class="fa fa-angle-left"></i></button>
                </div>

                <!-- Tamanho -->
                <div class="popup-sections">
                    <div class="tamanho-section">
                        <h3>Escolha o Tamanho:</h3>
                        <div class="tamanho-options-popup" id="tamanho-options-popup">
                            <!-- Os tamanhos serão carregados via JavaScript -->
                        </div>
                    </div>

                    <!-- Adicionais -->
                    <div class="adicionais-section">
                        <h3>Algum Adicional?</h3>

                        <div class="adicionais-container" id="adicionais-container">
                            <!-- Os adicionais serão carregados aqui via JavaScript -->
                        </div>
                        <!-- RESUMO DO PEDIDO -->
                        <div class="adicionais-resumo">
                            <div class="resumo-info">
                                <div class="left">
                                    <h3>Resumo do Pedido:</h3>
                                    <div class="resumo-item">
                                        <span class="item-nome" id="resumo-item-nome"></span>
                                        <span class="item-preco" id="resumo-item-preco"></span>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="tamanho-selecionado" id="tamanho-selecionado">
                                        <!-- Tamanho selecionado aparecerá aqui -->
                                    </div>
                                    <div class="adicionais-selecionados" id="adicionais-selecionados">
                                        <!-- Adicionais selecionados aparecerão aqui -->
                                    </div>
                                </div>
                            </div>
                            <div class="total-adicionais">
                                <strong style="font-family: Rajdhani">Total: R$<span id="total-com-adicionais" style="font-family: Rajdhani">0.00</span></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="adicionais-botoes" style="display: flex; justify-content: center; align-items: center;">
                    <button type="button" class="post-content-button" id="btn-continuar-observacao">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup de Observação -->
    <div class="confirmacao-overlay" id="confirmacao-overlay-observacao">
        <div class="obs-overlay">
            <div class="overlay-content">
                <div class="voltar">
                    <button type="button" class="btn-voltar" id="btn-voltar-observacao"><i class="fa fa-angle-left"></i></button>
                </div>
                <h2>Alguma Observação?</h2>
                <form id="form-observacao">
                    <textarea class="form-control" name="observacao" maxlength="255"
                        placeholder="Tirar tomate, molho de tomate, mussarela, manjericão [...]"></textarea>
                    <button type="submit" class="post-content-button">ADICIONAR</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="copyright-logo-container">
                <div class="logo">
                    <img src="assets/logo-2.svg" alt="Logo">
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
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.carrinho) {
                window.carrinho = [];
            }

            initCarrinho();

            showPage('home');
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    showPage(page);
                });
            });

            initCardapio();
            initPizzaBuilder();
        });
        // Botão continuar comprando
        document.getElementById('continuar-comprando').addEventListener('click', function() {
            showPage('cardapio');
        });

        // Botão finalizar pedido
        document.getElementById('finalizar-pedido').addEventListener('click', function() {
            if (carrinho.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }

            const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);

            // Mostrar animação de confirmação
            const overlayCarrinho = document.getElementById('confirmacao-overlay-carrinho');
            const textoConfirmacaoCarrinho = document.getElementById('confirmacao-texto-carrinho');
            const textoAdicionarCarrinho = document.getElementById('add-carrinho-texto');

            // Texto personalizado com os detalhes do pedido
            textoConfirmacaoCarrinho.textContent = `Seu pedido, no valor de R$ ${total.toFixed(2)}, foi recebido com sucesso! O pagamento será realizado no momento da entrega (via Pix, cartão ou dinheiro) e, em breve, estará na sua casa!`;
            textoAdicionarCarrinho.textContent = `${item.nome} Está no Carrinho!`

            // Mostrar o overlay
            overlayCarrinho.classList.add('ativo');

            // Configurar o botão de confirmação
            document.getElementById('confirmacao-btn-carrinho').addEventListener('click', function() {
                // Limpar carrinho após finalizar
                carrinho = [];
                atualizarCarrinho();
                atualizarContadorCarrinho();
                localStorage.removeItem('carrinho');
                // Esconder o overlay
                overlayCarrinho.classList.remove('ativo');
                // Voltar para a página inicial
                showPage('home');
            });
        });

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
        // Variáveis globais
        let cardapio = {};
        let ingredientes = [];
        let tamanhosPizza = [];

        // Função para carregar dados da API
        async function carregarDadosAPI(url) {
            try {
                const response = await fetch(url);
                const data = await response.json();
                return data.success ? data.data : null;
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
                return null;
            }
        }

        // Variáveis globais para o checkout
        let formasPagamento = [];
        let enderecoEntrega = null;
        let formaPagamentoSelecionada = null;

        // Função para carregar formas de pagamento
        async function carregarFormasPagamento() {
            try {
                const response = await fetch('api/formapag_api.php');
                const data = await response.json();

                if (data.success) {
                    formasPagamento = data.data;
                    atualizarOpcoesPagamento();
                } else {
                    console.error('Erro ao carregar formas de pagamento');
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }

        // Função para atualizar opções de pagamento no HTML
        function atualizarOpcoesPagamento() {
            const container = document.getElementById('opcoes-pagamento');
            container.innerHTML = '';

            formasPagamento.forEach(pagamento => {
                const opcao = document.createElement('div');
                opcao.className = 'opcao-pagamento';
                opcao.innerHTML = `
            <h4>${pagamento.nome}</h4>
        `;
                opcao.addEventListener('click', () => selecionarFormaPagamento(pagamento));
                container.appendChild(opcao);
            });
        }

        // Função para selecionar forma de pagamento
        function selecionarFormaPagamento(pagamento) {
            formaPagamentoSelecionada = pagamento;

            // Remover seleção anterior
            document.querySelectorAll('.opcao-pagamento').forEach(opcao => {
                opcao.classList.remove('selecionado');
            });

            // Adicionar seleção atual
            event.currentTarget.classList.add('selecionado');

            // Habilitar botão de confirmar se tudo estiver preenchido
            verificarFormularioCompleto();
        }

        // Função para mostrar/ocultar formulário de endereço baseado no tipo de entrega
        function configurarTipoEntrega() {
            const opcoesEntrega = document.querySelectorAll('input[name="tipo-entrega"]');
            const formEndereco = document.getElementById('form-endereco');
            const opcoesRetirada = document.getElementById('opcoes-retirada');

            opcoesEntrega.forEach(opcao => {
                opcao.addEventListener('change', function() {
                    if (this.value === '1') { // Delivery
                        formEndereco.style.display = 'block';
                        opcoesRetirada.style.display = 'none';
                        // Tornar campos de endereço obrigatórios
                        document.querySelectorAll('#form-dados-entrega input, #form-dados-entrega textarea').forEach(campo => {
                            if (campo.name !== 'complemento' && campo.name !== 'observacao') {
                                campo.required = true;
                            }
                        });
                    } else { // Retirada
                        formEndereco.style.display = 'none';
                        opcoesRetirada.style.display = 'block';
                        // Remover obrigatoriedade dos campos de endereço
                        document.querySelectorAll('#form-dados-entrega input, #form-dados-entrega textarea').forEach(campo => {
                            campo.required = false;
                        });
                    }
                    verificarFormularioCompleto();
                });
            });
        }

        // Função para verificar se o formulário está completo
        function verificarFormularioCompleto() {
            const tipoEntrega = document.querySelector('input[name="tipo-entrega"]:checked').value;
            const btnConfirmar = document.getElementById('confirmar-pedido');

            let formularioCompleto = formaPagamentoSelecionada !== null;

            if (tipoEntrega === '1') { // Delivery
                const form = document.getElementById('form-dados-entrega');
                const camposObrigatorios = form.querySelectorAll('input[required]');
                formularioCompleto = formularioCompleto && Array.from(camposObrigatorios).every(campo => campo.value.trim() !== '');
            }

            btnConfirmar.disabled = !formularioCompleto;
        }

        // Função para atualizar resumo do pedido no checkout
        function atualizarResumoCheckout() {
            const resumoContainer = document.getElementById('resumo-checkout');
            const totalElement = document.getElementById('total-checkout');

            if (!window.carrinho || window.carrinho.length === 0) {
                resumoContainer.innerHTML = '<p>Seu carrinho está vazio</p>';
                totalElement.textContent = '0.00';
                return;
            }

            let html = '';
            let total = 0;

            window.carrinho.forEach(item => {
                const subtotal = item.preco * item.quantidade;
                total += subtotal;

                html += `
            <div class="item-resumo">
                <div>
                    <strong>${item.nome}</strong>
                    <div style="font-size: 12px; color: #AAA;">Qtd: ${item.quantidade}</div>
                </div>
                <div>R$ ${subtotal.toFixed(2)}</div>
            </div>
        `;
            });

            resumoContainer.innerHTML = html;
            totalElement.textContent = total.toFixed(2);
        }

        // Função para coletar dados do endereço
        function coletarDadosEndereco() {
            const tipoEntrega = document.querySelector('input[name="tipo-entrega"]:checked').value;

            if (tipoEntrega === '1') { // Delivery
                const form = document.getElementById('form-dados-entrega');
                const formData = new FormData(form);

                return {
                    cidade: formData.get('cidade'),
                    distrito: formData.get('distrito'),
                    bairro: formData.get('bairro'),
                    rua: formData.get('rua'),
                    numero: formData.get('numero'),
                    complemento: formData.get('complemento'),
                    observacao: formData.get('observacao')
                };
            } else { // Retirada
                const tipoRetirada = document.querySelector('input[name="tipo-retirada"]:checked').value;
                return {
                    tipoRetirada: tipoRetirada,
                    observacao: tipoRetirada === 'mesa' ? 'Entrega na mesa' : 'Retirada no balcão'
                };
            }
        }

        // Função para finalizar o pedido
        async function finalizarPedido() {
            try {
                const tipoEntrega = document.querySelector('input[name="tipo-entrega"]:checked').value;
                const dadosEndereco = coletarDadosEndereco();
                const total = window.carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);

                // Preparar dados do pedido
                const pedidoData = {
                    id_cliente: <?php echo $_SESSION['id']; ?>,
                    id_estado: 1, // Em Processamento
                    id_formapag: formaPagamentoSelecionada.id,
                    valor: total,
                    tipo_entrega: parseInt(tipoEntrega),
                    dados_entrega: dadosEndereco,
                    itens: window.carrinho
                };

                // Enviar para a API
                const response = await fetch('api/finalizar_pedido_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(pedidoData)
                });

                const result = await response.json();

                if (result.success) {
                    // Pedido criado com sucesso
                    mostrarConfirmacaoPedido(result.pedido_id, total);

                    // Limpar carrinho
                    window.carrinho = [];
                    localStorage.removeItem('carrinho');
                    atualizarCarrinho();
                    atualizarContadorCarrinho();

                } else {
                    alert('Erro ao finalizar pedido: ' + result.message);
                }

            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao finalizar pedido. Tente novamente.');
            }
        }

        // Função para mostrar confirmação do pedido
        function mostrarConfirmacaoPedido(pedidoId, total) {
            const overlay = document.getElementById('confirmacao-overlay-carrinho');
            const textoConfirmacao = document.getElementById('confirmacao-texto-carrinho');

            textoConfirmacao.textContent = `Pedido #${pedidoId} confirmado! Valor: R$ ${total.toFixed(2)}. Em breve estará pronto!`;
            overlay.classList.add('ativo');

            // Fechar checkout
            document.getElementById('checkout-overlay').classList.remove('ativo');
        }

        // Função para abrir o checkout
        function abrirCheckout() {
            if (!window.carrinho || window.carrinho.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }

            // Carregar formas de pagamento se ainda não carregou
            if (formasPagamento.length === 0) {
                carregarFormasPagamento();
            }

            // Atualizar resumo
            atualizarResumoCheckout();

            // Resetar seleções
            formaPagamentoSelecionada = null;
            document.querySelectorAll('.opcao-pagamento').forEach(opcao => {
                opcao.classList.remove('selecionado');
            });

            // Mostrar checkout
            document.getElementById('checkout-overlay').classList.add('ativo');
        }

        // Configurar event listeners do checkout
        function configurarCheckout() {
            // Botão finalizar pedido no carrinho
            document.getElementById('finalizar-pedido').addEventListener('click', abrirCheckout);

            // Fechar checkout
            document.getElementById('fechar-checkout').addEventListener('click', function() {
                document.getElementById('checkout-overlay').classList.remove('ativo');
            });

            document.getElementById('voltar-carrinho').addEventListener('click', function() {
                document.getElementById('checkout-overlay').classList.remove('ativo');
            });

            // Confirmar pedido
            document.getElementById('confirmar-pedido').addEventListener('click', finalizarPedido);

            // Configurar validação em tempo real
            document.getElementById('form-dados-entrega').addEventListener('input', verificarFormularioCompleto);

            // Configurar tipo de entrega
            configurarTipoEntrega();
        }

        // Inicializar checkout quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            configurarCheckout();
        });

        // Página "Cardápio"
        async function initCardapio() {
            // Carregar cardápio do banco de dados
            cardapio = await carregarDadosAPI('api/cardapio_api.php');

            const menuItems = document.getElementById('menu-items');
            const categoryBtns = document.querySelectorAll('.category-btn');

            // Mostrar categoria inicial
            mostrarCategoria('todas');

            // Configurar botões de categoria
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const category = this.getAttribute('data-category');
                    mostrarCategoria(category);
                });
            });

            function mostrarCategoria(category) {
                menuItems.innerHTML = '';

                if (cardapio[category] && cardapio[category].length > 0) {
                    cardapio[category].forEach(item => {
                        const menuItem = document.createElement('div');
                        menuItem.className = 'menu-item';
                        menuItem.innerHTML = `
                    <img src="${item.imagem}" alt="${item.nome}" onerror="this.src='assets/placeholder-pizza.jpg'">
                    <div class="menu-item-info">
                        <div class="nome-desc">
                            <h3>${item.nome}</h3>
                            <p>${item.descricao}</p>
                        </div>
                        <div class="menu-item-price">
                            <span class="price">R$${item.preco.toFixed(2)}</span>
                            <button class="add-to-cart" data-id="${item.id}">Adicionar</button>
                        </div>
                    </div>
                `;

                        const addButton = menuItem.querySelector('.add-to-cart');
                        addButton.addEventListener('click', () => {
                            mostrarOverlayObservacao(item);
                        });

                        menuItems.appendChild(menuItem);
                    });
                } else {
                    menuItems.innerHTML = '<div class="sem-itens"><p>Nenhum item disponível nesta categoria</p></div>';
                }
            }
            // Variáveis globais para controlar o fluxo
            let itemAtual = null;
            let adicionaisSelecionados = [];
            let precoBaseItem = 0;
            let tamanhoSelecionado = null;

            // Função para mostrar o popup de adicionais
            // Função para mostrar o popup de tamanho e adicionais
            function mostrarPopupAdicionais(item) {
                console.log('🎯 Mostrando popup de tamanho e adicionais para:', item.nome);

                itemAtual = item;
                adicionaisSelecionados = [];
                precoBaseItem = item.preco;
                tamanhoSelecionado = null; // Nova variável para armazenar o tamanho

                const overlay = document.getElementById('confirmacao-overlay-adicionais');
                const containerAdicionais = document.getElementById('adicionais-container');
                const containerTamanho = document.getElementById('tamanho-options-popup');
                const resumoNome = document.getElementById('resumo-item-nome');
                const resumoPreco = document.getElementById('resumo-item-preco');

                if (!overlay || !containerAdicionais || !containerTamanho) {
                    console.error('❌ Elementos do popup não encontrados');
                    mostrarOverlayObservacao(item); // Fallback
                    return;
                }

                // Atualizar resumo do item principal
                resumoNome.textContent = item.nome;
                resumoPreco.textContent = `R$ ${item.preco.toFixed(2)}`;

                // Carregar tamanhos
                carregarTamanhosNoPopup(containerTamanho, item);

                // Carregar adicionais
                carregarAdicionaisNoPopup(containerAdicionais);

                // Atualizar total
                atualizarTotalAdicionais();

                // Mostrar overlay
                overlay.classList.add('ativo');

                // Configurar event listeners
                configurarEventListenersAdicionais();
            }

            // Nova função para carregar os tamanhos no popup
            function carregarTamanhosNoPopup(container, item) {
                container.innerHTML = '';

                if (!tamanhosPizza || tamanhosPizza.length === 0) {
                    container.innerHTML = '<div class="sem-tamanhos">Nenhum tamanho disponível</div>';
                    return;
                }

                tamanhosPizza.forEach(tamanho => {
                    const tamanhoElement = document.createElement('div');
                    tamanhoElement.className = 'tamanho-option';
                    tamanhoElement.innerHTML = `
            <div class="nome-preco">
                <div class="nome">${tamanho.nome.charAt(0).toUpperCase() + tamanho.nome.slice(1)}</div>
                <div class="preco">(R$ ${tamanho.preco_base.toFixed(2)})</div>
            </div>
            <hr>
            <div class="descricao">${obterDescricaoTamanho(tamanho.nome)}</div>
            <input type="radio" name="tamanho-pizza" class="tamanho-checkbox" 
                   data-nome="${tamanho.nome}" data-preco="${tamanho.preco_base}">
        `;

                    container.appendChild(tamanhoElement);
                });

                // Selecionar tamanho médio por padrão
                const tamanhoMedio = container.querySelector('[data-nome="media"]');
                if (tamanhoMedio) {
                    tamanhoMedio.checked = true;
                    tamanhoMedio.closest('.tamanho-option').classList.add('selecionado');
                    tamanhoSelecionado = {
                        nome: 'media',
                        preco_base: parseFloat(tamanhoMedio.dataset.preco)
                    };
                }
            }

            // Função auxiliar para descrições dos tamanhos
            function obterDescricaoTamanho(nomeTamanho) {
                const descricoes = {
                    'pequena': '4 fatias  -  25cm',
                    'media': '6 fatias  -  30cm',
                    'grande': '8 fatias  -  35cm'
                };
                return descricoes[nomeTamanho] || '';
            }

            // Função para carregar os adicionais no popup
            function carregarAdicionaisNoPopup(container) {
                container.innerHTML = '';

                if (!ingredientes || ingredientes.length === 0) {
                    container.innerHTML = '<div class="sem-adicionais">Nenhum adicional disponível no momento.</div>';
                    return;
                }

                ingredientes.forEach(adicional => {
                    const adicionalElement = document.createElement('div');
                    adicionalElement.className = 'adicional-item';
                    adicionalElement.innerHTML = `
            <div class="nome-preco">
                <div class="nome">${adicional.nome}</div>
                <div class="preco">+ R$ ${adicional.preco.toFixed(2)}</div>
            </div>
            <input type="checkbox" class="adicional-checkbox" data-id="${adicional.id}" data-nome="${adicional.nome}" data-preco="${adicional.preco}">
        `;

                    container.appendChild(adicionalElement);
                });
            }

            // Função para configurar os event listeners do popup de adicionais
            function configurarEventListenersAdicionais() {
                console.log('🔗 Configurando event listeners...');

                // Event Listeners para Tamanho
                document.querySelectorAll('.tamanho-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        if (!e.target.classList.contains('tamanho-checkbox')) {
                            const radio = this.querySelector('.tamanho-checkbox');

                            // Marcar este radio e desmarcar outros
                            document.querySelectorAll('.tamanho-checkbox').forEach(r => {
                                r.checked = false;
                                r.closest('.tamanho-option').classList.remove('selecionado');
                            });

                            radio.checked = true;
                            this.classList.add('selecionado');

                            // Atualizar tamanho selecionado
                            tamanhoSelecionado = {
                                nome: radio.dataset.nome,
                                preco_base: parseFloat(radio.dataset.preco)
                            };

                            atualizarTotalAdicionais();
                            atualizarResumoTamanho();
                        }
                    });
                });

                // Event Listeners para Adicionais
                document.querySelectorAll('.adicional-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        if (!e.target.classList.contains('adicional-checkbox')) {
                            const checkbox = this.querySelector('.adicional-checkbox');
                            const estavaSelecionado = checkbox.checked;

                            checkbox.checked = !estavaSelecionado;

                            if (checkbox.checked) {
                                this.classList.add('selecionado');
                                adicionarAdicional({
                                    id: checkbox.dataset.id,
                                    nome: checkbox.dataset.nome,
                                    preco: parseFloat(checkbox.dataset.preco)
                                });
                            } else {
                                this.classList.remove('selecionado');
                                removerAdicional(checkbox.dataset.id);
                            }

                            atualizarTotalAdicionais();
                        }
                    });
                });

                // Botões de navegação (mantém o mesmo)
                const btnVoltar = document.getElementById('btn-voltar-adicionais');
                if (btnVoltar) {
                    btnVoltar.onclick = function() {
                        console.log('❌ Cancelando personalização');
                        document.getElementById('confirmacao-overlay-adicionais').classList.remove('ativo');
                        adicionaisSelecionados = [];
                        itemAtual = null;
                        tamanhoSelecionado = null;
                    };
                }

                const btnContinuar = document.getElementById('btn-continuar-observacao');
                if (btnContinuar) {
                    btnContinuar.onclick = function() {
                        console.log('➡️ Continuando para observação');
                        document.getElementById('confirmacao-overlay-adicionais').classList.remove('ativo');
                        mostrarOverlayObservacaoComAdicionais();
                    };
                }
            }

            // Função para adicionar um adicional
            function adicionarAdicional(adicional) {
                if (!adicionaisSelecionados.find(a => a.id === adicional.id)) {
                    adicionaisSelecionados.push(adicional);
                    atualizarListaAdicionaisSelecionados();
                }
            }

            // Função para remover um adicional
            function removerAdicional(adicionalId) {
                adicionaisSelecionados = adicionaisSelecionados.filter(a => a.id != adicionalId);
                atualizarListaAdicionaisSelecionados();
            }

            // Função para atualizar a lista de adicionais selecionados
            function atualizarListaAdicionaisSelecionados() {
                const container = document.getElementById('adicionais-selecionados');
                container.innerHTML = '';

                adicionaisSelecionados.forEach(adicional => {
                    const elemento = document.createElement('div');
                    elemento.className = 'adicional-selecionado';
                    elemento.innerHTML = `
            <span>${adicional.nome}</span>
            <span>
                + R$ ${adicional.preco.toFixed(2)}
                <span class="remover" data-id="${adicional.id}">×</span>
            </span>
        `;
                    container.appendChild(elemento);
                });

                // Event listeners para remover adicionais
                container.querySelectorAll('.remover').forEach(remover => {
                    remover.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const adicionalId = this.dataset.id;
                        removerAdicional(adicionalId);

                        // Desmarcar o checkbox correspondente
                        const checkbox = document.querySelector(`.adicional-checkbox[data-id="${adicionalId}"]`);
                        if (checkbox) {
                            checkbox.checked = false;
                            checkbox.closest('.adicional-item').classList.remove('selecionado');
                        }

                        atualizarTotalAdicionais();
                    });
                });
            }

            // Função para atualizar o total com adicionais
            function atualizarTotalAdicionais() {
                if (!itemAtual) {
                    console.error('❌ itemAtual não definido');
                    return;
                }

                const precoTamanho = tamanhoSelecionado ? tamanhoSelecionado.preco_base : 0;
                const totalAdicionais = adicionaisSelecionados.reduce((sum, adicional) => sum + adicional.preco, 0);
                const totalFinal = itemAtual.preco + precoTamanho + totalAdicionais;

                console.log('💰 Calculando total:', {
                    precoBase: itemAtual.preco,
                    precoTamanho: precoTamanho,
                    totalAdicionais: totalAdicionais,
                    totalFinal: totalFinal
                });

                const totalElement = document.getElementById('total-com-adicionais');
                if (totalElement) {
                    totalElement.textContent = totalFinal.toFixed(2);
                }

                // Habilitar/desabilitar botão Continuar
                const btnContinuar = document.getElementById('btn-continuar-observacao');
                if (btnContinuar) {
                    btnContinuar.disabled = totalFinal <= 0;
                }
            }

            function atualizarResumoTamanho() {
                const container = document.getElementById('tamanho-selecionado');

                if (tamanhoSelecionado) {
                    container.innerHTML = `
            <p>${tamanhoSelecionado.nome.charAt(0).toUpperCase() + tamanhoSelecionado.nome.slice(1)}</p>
            <p>+ R$ ${tamanhoSelecionado.preco_base.toFixed(2)}</p>
        `;
                } else {
                    container.innerHTML = '';
                }
            }

            // Função para mostrar o popup de observação (após adicionais)
            function mostrarOverlayObservacaoComAdicionais() {
                console.log('📝 Indo para observação após adicionais');

                const overlay = document.getElementById('confirmacao-overlay-observacao');
                const textarea = overlay.querySelector('textarea');
                const form = document.getElementById('form-observacao');

                if (!overlay || !form) {
                    console.error('❌ Popup de observação não encontrado');
                    return;
                }

                textarea.value = '';
                overlay.classList.add('ativo');

                // Remover event listeners anteriores para evitar duplicação
                form.onsubmit = null;

                // Configurar o formulário
                form.onsubmit = function(e) {
                    e.preventDefault();
                    console.log('✅ Formulário de observação submetido');

                    const observacao = textarea.value.trim();
                    console.log('💬 Observação:', observacao);
                    console.log('🍕 Item atual:', itemAtual);
                    console.log('➕ Adicionais selecionados:', adicionaisSelecionados);

                    // Criar item com adicionais
                    const itemComAdicionais = criarItemComAdicionais(itemAtual, adicionaisSelecionados, observacao);
                    console.log('🛒 Item final para carrinho:', itemComAdicionais);

                    // Adicionar ao carrinho
                    if (typeof adicionarAoCarrinho === 'function') {
                        adicionarAoCarrinho(itemComAdicionais, observacao, true);
                        console.log('✅ Item adicionado ao carrinho com sucesso!');
                    } else {
                        console.error('❌ Função adicionarAoCarrinho não encontrada!');
                        // Fallback
                        alert('Erro ao adicionar ao carrinho. Função não encontrada.');
                    }

                    // Fechar overlay
                    overlay.classList.remove('ativo');

                    // Limpar variáveis temporárias
                    adicionaisSelecionados = [];
                    itemAtual = null;
                };

                // Botão Voltar do popup de observação
                const btnVoltarObservacao = document.getElementById('btn-voltar-observacao');
                if (btnVoltarObservacao) {
                    btnVoltarObservacao.onclick = function() {
                        console.log('↩️ Voltando para adicionais');
                        overlay.classList.remove('ativo');
                        mostrarPopupAdicionais(itemAtual); // Voltar para adicionais
                    };
                }
            }

            // Função para criar o item com adicionais
            function criarItemComAdicionais(item, adicionais, observacao) {
                console.log('🔨 Criando item com tamanho e adicionais...');

                const precoTamanho = tamanhoSelecionado ? tamanhoSelecionado.preco_base : 0;
                const totalAdicionais = adicionais.reduce((sum, adicional) => sum + adicional.preco, 0);

                // Construir nome com tamanho
                const nomeComTamanho = tamanhoSelecionado ?
                    `${item.nome} - ${tamanhoSelecionado.nome.charAt(0).toUpperCase() + tamanhoSelecionado.nome.slice(1)}` :
                    item.nome;

                // Construir descrição
                let descricao = item.descricao;
                if (tamanhoSelecionado) {
                    descricao += '';
                }
                if (adicionais.length > 0) {
                    const nomesAdicionais = adicionais.map(a => a.nome).join(', ');
                    descricao += '';
                }

                // Criar ID único
                const tamanhoId = tamanhoSelecionado ? tamanhoSelecionado.nome : 'padrao';
                const adicionaisIds = adicionais.map(a => a.id).sort().join('_');
                const uniqueId = `${item.id}_${tamanhoId}_${adicionaisIds}_${Date.now()}`;

                const itemFinal = {
                    id: uniqueId,
                    nome: nomeComTamanho,
                    descricao: descricao,
                    preco: item.preco + precoTamanho + totalAdicionais,
                    imagem: item.imagem,
                    observacao: observacao,
                    adicionais: [...adicionais],
                    tamanho: tamanhoSelecionado ? {
                        ...tamanhoSelecionado
                    } : null,
                    quantidade: 1
                };

                console.log('✅ Item criado com tamanho:', itemFinal);
                return itemFinal;
            }
            // Função para mostrar o overlay de observação
            function mostrarOverlayObservacao(item) {
                console.log('🎯 Verificando tipo do item:', item.nome);

                // 🔴 VERIFICAR SE É A PIZZA PERSONALIZADA (mais critérios)
                const ehPizzaPersonalizada =
                    item.nome.toLowerCase().includes('personalizada') ||
                    item.id === 1 || // ID específico
                    item.descricao.toLowerCase().includes('monte sua') ||
                    item.descricao.toLowerCase().includes('personalizada');

                if (ehPizzaPersonalizada) {
                    console.log('Item é Pizza Personalizada, redirecionando ao monte sua pizza');

                    // Fechar qualquer overlay aberto
                    const overlayAdicionais = document.getElementById('confirmacao-overlay-adicionais');
                    const overlayObservacao = document.getElementById('confirmacao-overlay-observacao');

                    if (overlayAdicionais) overlayAdicionais.classList.remove('ativo');
                    if (overlayObservacao) overlayObservacao.classList.remove('ativo');

                    showPage('monte-sua-pizza');
                    return;
                }

                // Verificar se é uma pizza salgada (pode ter adicionais salgados)
                const ehPizzaSalgada = item.nome.toLowerCase().includes('pizza') ||
                    item.descricao.toLowerCase().includes('pizza') ||
                    item.id != 1;

                if (ehPizzaSalgada) {
                    mostrarPopupAdicionais(item);
                } else {
                    // Para não-pizzas, ir direto para observação
                    const overlay = document.getElementById('confirmacao-overlay-observacao');
                    const textarea = overlay.querySelector('textarea');
                    const form = overlay.querySelector('#form-observacao');

                    textarea.value = '';
                    overlay.classList.add('ativo');

                    form.onsubmit = function(e) {
                        e.preventDefault();
                        const observacao = textarea.value.trim();
                        adicionarAoCarrinho(item, observacao, false);
                        overlay.classList.remove('ativo');
                    };

                    document.getElementById('btn-voltar-observacao').onclick = function() {
                        overlay.classList.remove('ativo');
                    };
                }
            }
        }

        function initCarrinho() {
            console.log('📦 Inicializando página do carrinho...');

            // Carregar do localStorage para window.carrinho
            try {
                const carrinhoSalvo = localStorage.getItem('carrinho');
                if (carrinhoSalvo) {
                    window.carrinho = JSON.parse(carrinhoSalvo);
                    console.log('✅ Carrinho carregado:', window.carrinho);
                } else {
                    window.carrinho = [];
                    console.log('ℹ️ Nenhum carrinho salvo encontrado');
                }
            } catch (error) {
                console.error('❌ Erro ao carregar carrinho:', error);
                window.carrinho = [];
            }

            // Atualizar a interface
            atualizarCarrinho();
            atualizarContadorCarrinho();
        }

        function adicionarEventosCarrinho() {
            console.log('🔗 Adicionando eventos do carrinho...');

            // Botões de diminuir
            document.querySelectorAll('.diminuir').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    if (!isNaN(index)) {
                        alterarQuantidade(index, -1);
                    }
                });
            });

            // Botões de aumentar
            document.querySelectorAll('.aumentar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    if (!isNaN(index)) {
                        alterarQuantidade(index, 1);
                    }
                });
            });

            // Botões de remover
            document.querySelectorAll('.remover-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    if (!isNaN(index)) {
                        removerItem(index);
                    }
                });
            });
        }

        // Função adicionarAoCarrinho
        function adicionarAoCarrinho(item, observacao = '', mostrarAlerta = true) {
            console.log('🛒 Iniciando adição ao carrinho...');
            console.log('Item recebido:', item);

            // Garantir que carrinho é um ARRAY
            if (!window.carrinho || !Array.isArray(window.carrinho)) {
                console.warn('⚠️ Carrinho não é array, inicializando...');
                window.carrinho = [];
            }

            // Validar item
            if (!item || typeof item !== 'object') {
                console.error('❌ Item inválido:', item);
                return false;
            }

            // Criar ID único mais robusto
            const itemIdBase = item.id ? item.id.toString().split('_')[0] : 'sem_id';
            const adicionaisIds = item.adicionais ?
                item.adicionais.map(a => a.id).sort().join('_') : '';
            const observacaoHash = observacao ?
                btoa(observacao).substring(0, 10) : '';

            const itemUniqueId = `${itemIdBase}_${adicionaisIds}_${observacaoHash}_${Date.now()}`;

            console.log('🔍 Procurando item existente...');
            console.log('ID único gerado:', itemUniqueId);
            console.log('Carrinho atual:', window.carrinho);

            let itemExistente = null;
            let itemIndex = -1;

            // Buscar item existente
            for (let i = 0; i < window.carrinho.length; i++) {
                const produto = window.carrinho[i];

                // Comparação mais precisa
                const mesmoNome = produto.nome === item.nome;
                const mesmaObservacao = (produto.observacao || '') === (observacao || '');
                const mesmosAdicionais = JSON.stringify(produto.adicionais || []) ===
                    JSON.stringify(item.adicionais || []);

                if (mesmoNome && mesmaObservacao && mesmosAdicionais) {
                    itemExistente = produto;
                    itemIndex = i;
                    break;
                }
            }

            if (itemExistente) {
                // Se já existe, aumenta a quantidade
                console.log('➕ Item existente encontrado, aumentando quantidade');
                itemExistente.quantidade += 1;
                window.carrinho[itemIndex] = itemExistente;
            } else {
                // Se não existe, adiciona novo item
                console.log('✅ Adicionando novo item ao carrinho');
                const novoItem = {
                    id: itemUniqueId,
                    nome: item.nome || 'Item sem nome',
                    descricao: item.descricao || '',
                    preco: typeof item.preco === 'number' ? item.preco : 0,
                    imagem: item.imagem || './assets/placeholder-pizza.jpg',
                    observacao: observacao || '',
                    adicionais: Array.isArray(item.adicionais) ? [...item.adicionais] : [],
                    quantidade: 1
                };

                window.carrinho.push(novoItem);
                console.log('Novo item adicionado:', novoItem);
            }

            // Salvar no localStorage
            try {
                localStorage.setItem('carrinho', JSON.stringify(window.carrinho));
                console.log('💾 Carrinho salvo. Total de itens:', window.carrinho.length);
            } catch (error) {
                console.error('❌ Erro ao salvar no localStorage:', error);
            }

            atualizarCarrinho();
            atualizarContadorCarrinho();
            return true;
        }

        function atualizarCarrinho() {
            console.log('🔄 Atualizando interface do carrinho...');

            const carrinhoItens = document.getElementById('carrinho-itens');
            const carrinhoTotal = document.getElementById('carrinho-total');

            if (!carrinhoItens || !carrinhoTotal) {
                console.error('❌ Elementos do carrinho não encontrados');
                return;
            }

            if (!window.carrinho || !Array.isArray(window.carrinho) || window.carrinho.length === 0) {
                carrinhoItens.innerHTML = '<div class="carrinho-vazio">Seu carrinho está vazio</div>';
                carrinhoTotal.textContent = '0.00';
                console.log('🛒 Carrinho vazio');
                return;
            }

            carrinhoItens.innerHTML = '';

            window.carrinho.forEach((item, index) => {
                // Verificar se tem adicionais
                const adicionaisHTML = item.adicionais && item.adicionais.length > 0 ?
                    `<p class="adicionais"><i class="fa fa-plus-square-o" aria-hidden="true" style="margin-right: 10px;"></i>${item.adicionais.map(a => a.nome).join(', ')}</p>` :
                    '';

                // Verificar se tem observação
                const observacaoHTML = item.observacao && item.observacao.trim() !== '' ?
                    `<p class="observacao"><i class="fa-regular fa-comment" style="margin-right: 10px;"></i>${item.observacao}</p>` :
                    '';

                const itemElement = document.createElement('div');
                itemElement.className = 'carrinho-item';
                itemElement.innerHTML = `
            <div class="item-info" style="width: 500px;">
                <img src="${item.imagem}" alt="${item.nome}" onerror="this.src='assets/placeholder-pizza.jpg'">
                <div class="item-detalhes">
                    <h3>${item.nome}</h3>
                    <p class="descricao">${item.descricao}</p>
                    ${adicionaisHTML}
                    ${observacaoHTML}
                    <p class="preco">R$ ${item.preco.toFixed(2)}</p>
                </div>
            </div>
            <div class="item-acoes">
                <div class="quantidade-controle">
                    <button class="diminuir" data-index="${index}"><p>-</p></button>
                    <span>${item.quantidade}</span>
                    <button class="aumentar" data-index="${index}"><p>+</p></button>
                </div>
                <button class="remover-item" data-index="${index}">×</button>
            </div>
            <div class="item-preco">
                R$ ${(item.preco * item.quantidade).toFixed(2)}
            </div>
        `;

                carrinhoItens.appendChild(itemElement);
            });

            const total = window.carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
            carrinhoTotal.textContent = total.toFixed(2);

            // Adicionar eventos
            adicionarEventosCarrinho();

            console.log('✅ Interface do carrinho atualizada');
        }

        function alterarQuantidade(index, mudanca) {
            console.log('📊 Alterando quantidade...');

            if (index < 0 || index >= window.carrinho.length) {
                console.error('❌ Índice inválido:', index);
                return;
            }

            const novoValor = window.carrinho[index].quantidade + mudanca;

            if (novoValor < 1) {
                // Remove o item se a quantidade for menor que 1
                window.carrinho.splice(index, 1);
                console.log('🗑️ Item removido do carrinho');
            } else {
                // Atualiza a quantidade
                window.carrinho[index].quantidade = novoValor;
                console.log('📊 Quantidade alterada para:', novoValor);
            }

            localStorage.setItem('carrinho', JSON.stringify(window.carrinho));

            // Atualizar interface
            atualizarCarrinho();
            atualizarContadorCarrinho();
        }

        function removerItem(index) {
            console.log('🗑️ Removendo item...');

            if (index < 0 || index >= window.carrinho.length) {
                console.error('❌ Índice inválido:', index);
                return;
            }

            window.carrinho.splice(index, 1);

            localStorage.setItem('carrinho', JSON.stringify(window.carrinho));

            // Atualizar interface
            atualizarCarrinho();
            atualizarContadorCarrinho();

            console.log('✅ Item removido');
        }

        function atualizarContadorCarrinho() {
            const totalItens = window.carrinho.reduce((sum, item) => sum + item.quantidade, 0);
            const carrinhoLink = document.querySelector('[data-page="carrinho"]');

            if (carrinhoLink) {
                carrinhoLink.innerHTML = totalItens > 0 ? `Carrinho (${totalItens})` : 'Carrinho';
            }

            console.log('🔢 Contador atualizado:', totalItens);
        }

        // Página "Monte sua Pizza"
        async function initPizzaBuilder() {
            // Carregar ingredientes do banco de dados
            ingredientes = await carregarDadosAPI('api/ingredientes_api.php');
            // Carregar tamanhos do banco de dados
            tamanhosPizza = await carregarDadosAPI('api/tamanhos_api.php');

            const ingredientesGrid = document.getElementById('ingredientes-grid');
            ingredientes.forEach(ing => {
                const btn = document.createElement('button');
                btn.className = 'ingrediente-btn';
                btn.innerHTML = `
            <img src="${ing.imagem}" alt="${ing.nome}" onerror="this.src='assets/placeholder-ingrediente.png'">
            <span>${ing.nome}</span>
        `;
                btn.addEventListener('click', () => adicionarIngrediente(ing));
                ingredientesGrid.appendChild(btn);
            });

            // Configurar os radio buttons de tamanho
            const tamanhoContainer = document.querySelector('.tamanho-options');
            tamanhoContainer.innerHTML = ''; // Limpar opções existentes

            tamanhosPizza.forEach(tamanho => {
                const label = document.createElement('label');
                label.innerHTML = `
            <input type="radio" name="tamanho" value="${tamanho.nome}" ${tamanho.nome === 'media' ? 'checked' : ''}>
            ${tamanho.nome.charAt(0).toUpperCase() + tamanho.nome.slice(1)}
        `;
                tamanhoContainer.appendChild(label);
            });

            // Canvas da pizza
            const canvas = document.getElementById('pizza-canvas');
            const ctx = canvas.getContext('2d');

            // Imagem da pizza base
            const pizzaBaseImg = new Image();
            pizzaBaseImg.src = './assets/pizzaBase.svg';


            // Array para armazenar os ingredientes desenhados
            let ingredientesDesenhados = [];

            pizzaBaseImg.onload = function() {
                redesenharPizza();
            };

            function redesenharPizza() {
                // Limpa o canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Desenha a pizza base (centralizada)
                const baseLargura = 488;
                const baseAltura = 488;
                const xBase = (canvas.width - baseLargura) / 2;
                const yBase = (canvas.height - baseAltura) / 2;

                ctx.drawImage(pizzaBaseImg, xBase, yBase, baseLargura, baseAltura);

                // Redesenha os ingredientes
                ingredientesDesenhados.forEach(ing => {
                    const img = new Image();
                    img.src = ing.imagem;
                    img.onload = function() {
                        ctx.drawImage(img, ing.x, ing.y, ing.tamanho, ing.tamanho);
                    };
                });
            }

            const ingredientesSelecionados = [];
            const ingredientesList = document.getElementById('ingredientes-selecionados-list');
            const totalPedido = document.getElementById('total-pedido');
            const btnFinalizar = document.getElementById('btn-finalizar');

            // Adiciona event listeners para os radio buttons de tamanho
            const tamanhoRadios = document.querySelectorAll('input[name="tamanho"]');
            tamanhoRadios.forEach(radio => {
                radio.addEventListener('change', calcularTotal);
            });

            function adicionarIngrediente(ingrediente) {
                const jaExiste = ingredientesSelecionados.some(ing => ing.id === ingrediente.id);

                if (!jaExiste) {
                    ingredientesSelecionados.push(ingrediente);

                    // Adiciona o ingrediente ao array de desenho
                    const tamanhoIngrediente = 500;
                    const x = canvas.width / 2 - tamanhoIngrediente / 2;
                    const y = canvas.height / 2 - tamanhoIngrediente / 2;

                    ingredientesDesenhados.push({
                        ...ingrediente,
                        x: x,
                        y: y,
                        tamanho: tamanhoIngrediente
                    });

                    atualizarListaIngredientes();
                    redesenharPizza();
                    calcularTotal();
                }
            }

            function removerIngrediente(index) {
                ingredientesSelecionados.splice(index, 1);
                ingredientesDesenhados.splice(index, 1);
                atualizarListaIngredientes();
                redesenharPizza();
                calcularTotal();
            }

            function atualizarListaIngredientes() {
                ingredientesList.innerHTML = '';
                if (ingredientesSelecionados.length === 0) {
                    const li = document.createElement('li');
                    li.textContent = 'Nenhum ingrediente selecionado ainda';
                    li.style.color = '#aaa';
                    ingredientesList.appendChild(li);
                    btnFinalizar.disabled = true;
                    return;
                }

                ingredientesSelecionados.forEach((ing, index) => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        ${ing.nome} - R$${ing.preco.toFixed(2)}
                        <button onclick="removerIngrediente(${index})">×</button>
                    `;
                    ingredientesList.appendChild(li);
                });

                btnFinalizar.disabled = false;
            }

            function calcularTotal() {
                const tamanhoSelecionado = document.querySelector('input[name="tamanho"]:checked').value;
                const tamanho = tamanhosPizza.find(t => t.nome === tamanhoSelecionado);

                if (!tamanho) {
                    console.error('Tamanho não encontrado');
                    return;
                }

                const precoBase = tamanho.preco_base;
                const precoIngredientes = ingredientesSelecionados.reduce((total, ing) => total + ing.preco, 0);
                const total = precoBase + precoIngredientes;

                document.getElementById('total-pedido').textContent = total.toFixed(2);
            }

            function limparPizza() {
                ingredientesSelecionados.length = 0;
                ingredientesDesenhados.length = 0;
                document.querySelector('input[name="tamanho"][value="media"]').checked = true;
                atualizarListaIngredientes();
                redesenharPizza();
                calcularTotal();
            }

            btnFinalizar.addEventListener('click', function() {
                if (ingredientesSelecionados.length === 0) {
                    alert('Por favor, adicione pelo menos um ingrediente!');
                    return;
                }

                // Mostrar overlay de observação
                mostrarOverlayObservacaoPizza();
            });

            function mostrarOverlayObservacaoPizza() {
                const overlay = document.getElementById('confirmacao-overlay-observacao');
                const textarea = overlay.querySelector('textarea');

                // Limpar o textarea
                textarea.value = '';

                // Mostrar overlay
                overlay.classList.add('ativo');

                // Configurar o formulário
                const form = overlay.querySelector('form');
                form.onsubmit = function(e) {
                    e.preventDefault();
                    const observacao = textarea.value.trim();

                    // Adicionar a pizza ao carrinho com a observação
                    adicionarPizzaAoCarrinho(observacao);

                    // Esconder o overlay
                    overlay.classList.remove('ativo');
                };

                // Fechar ao clicar fora (opcional)
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        overlay.classList.remove('ativo');
                    }
                });
            }

            function adicionarPizzaAoCarrinho(observacao = '') {
                // Obter o tamanho selecionado
                const tamanho = document.querySelector('input[name="tamanho"]:checked').value;
                const tamanhoTexto = {
                    pequena: 'Pizza Pequena',
                    media: 'Pizza Média',
                    grande: 'Pizza Grande'
                } [tamanho];

                // Criar descrição dos ingredientes
                const ingredientesDesc = ingredientesSelecionados.map(ing => ing.nome).join(', ');

                // 🔴 CORREÇÃO: Usar a função adicionarAoCarrinho em vez de manipular diretamente
                const pizzaPersonalizada = {
                    id: Date.now(),
                    nome: tamanhoTexto,
                    descricao: `${ingredientesDesc}`,
                    preco: parseFloat(totalPedido.textContent),
                    imagem: './assets/logo-square.svg',
                    observacao: observacao,
                    quantidade: 1,
                    personalizada: true
                };

                // 🔴 CORREÇÃO: Usar a função unificada
                adicionarAoCarrinho(pizzaPersonalizada, observacao, false);

                // Limpar a pizza para o próximo pedido
                limparPizza();
                showPage('carrinho');
            }

            window.removerIngrediente = removerIngrediente;
        }
    </script>
</body>

</html>