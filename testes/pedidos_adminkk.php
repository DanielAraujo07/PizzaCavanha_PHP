<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="shortcur icon" href="assets/logo.svg"/>
    <!-- Fontes Oswald, Jaro e Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">
    <!-- Font Awesome para ícones -->
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
            padding: 15px 0;
        }

        header a {
            text-decoration: none;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo img {
            height: 50px;
        }

        .logo h1 {
            font-family: Jaro;
            font-weight: 400;
            color: var(--primary-color);
            font-size: 1.9rem;
            text-shadow: 0 0 5px rgba(255, 165, 0, 0.5);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 680px;
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
            height: 45px;
            width: 45px;
            border: #929292 2px solid;
            border-radius: 50%;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-usuario label img {
            height: 35px;
            width: 35px;
            border-radius: 50%;
        }

        .btn-usuario label:hover {
            border: var(--primary-color) solid 2px;
            background-color: rgba(255, 165, 0, 0.1);
            box-shadow: #ffa6008e 0 0 12px -1px;
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
            margin-left: 10px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 18px;
        }

        .opt-usuario hr {
            margin-top: 3px;
            margin-bottom: 10px;
        }

        .sair-usuario {
            font-size: 16px;
        }

        .sair-usuario a {
            padding: 5px;
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

        /* Páginas */
        .page {
            display: none;
            padding: 40px 0;
        }

        .page.active {
            display: block;
        }

        .titulo-pagina {
            text-align: center;
            margin-bottom: 25px;
            font-family: Oswald;
            font-weight: 400;
            font-size: 35px;
            color: var(--primary-color)
        }

        /* Pedidos */
        .pedidos-container {
            width:  100%;
            height: auto;
        }
        
        .pedido {
            width:  100%;
            height: auto;
            background-color: #1D1D1D;
            border-radius: 24px;
        }
        
        .pedido-header {
            background-color: #FFA500;
            border-top-left-radius: 24px; 
            border-top-right-radius: 24px;
            height: 55px;
            text-align: center;
        }

        .pedido_id-hora {
            font-family: Rajdhani;
            font-weight: 600;
            font-size: 38px;
            color: #FFF;
        }

        .pedido-info {
            display: flex;
            flex-direction: column;
            margin: 12px;
            gap: 22px;
        }

        .titulo-dados-pedido {
            color: #FFA500;
            font-size: 35px;
            font-family: Rajdhani;
            font-weight: 600;
            word-wrap: break-word;
        }

        .container-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .itens-header, .cliente-header, .entrega-header {
            display: flex;
            height: 43px;
            width: auto;
            background: #1D1D1D99; 
            box-shadow: 0px 1px 8px #00000040; 
            border-top-left-radius: 15px; 
            border-top-right-radius: 15px;
            border-bottom: 1px #FFA500 solid; 
            backdrop-filter: blur(20px);
            justify-content: center;
            align-items: center;
            z-index: 20;
        }

        .itens-container, .cliente-container, .entrega-container, .formapag-container, .preco-container, .status-atual {
            background-color: #282828;
        }

        .itens-container {
            display: flex;
            flex-direction: column;
            border: #FFA500 2px solid;
            border-radius: 15px;
            width: 754px;
            max-height: 460px;
        }
        
        .itens {
            display: grid;
            justify-content: center;
            align-self: center;
            width: 100%;
            gap: 14px;
            overflow: hidden;
            overflow-y: scroll;
        }

        .itens::-webkit-scrollbar {
            width: 5px;
            background-color: #FFA500;
            border-radius: 5px;
        }

        .itens::-webkit-scrollbar-thumb {
            background-color: #FFA500;
            border-radius: 5px;
        }

        .item {
            height: auto;
            width: 724px;
            background-color: #202020;
            border-radius: 10px;
        }

        .item-info {
            display: flex;
            margin: 10px;
            margin-top: 0px;
            justify-content: space-between;
        }

        .item-info {
            .container-1 {
                display: flex;
                flex-direction: column;
                gap: 14px;
                max-width: 335px;
            }
            .container-2 {
                display: flex;
                align-items: center;
            }
        }

        .item_qtd-nome {
            font-family: Oswald;
            color: #FFF;
            font-size: 30px;
            font-weight: 400;
        }

        .desc-preco {
            border-left: #FFF solid 1px;
        }

        .desc, .preco {
            font-family: Rajdhani;
            font-weight: 500;
            font-size: 26px;
            color: #FFF;
            line-height: 1.9rem;
            word-wrap: break-word;
            margin-left: 8px;
        }

        .preco {
            font-size: 30px;
        }

        .observacao-container {
            width: 352px;
            height: 222px;
            border-radius: 8px;
            margin-top: 10px;
            background-color: #282828;
        }

        .observacao {
            margin: 10px;
            margin-top: 5px;
            color: #ffffff;
            line-height: 1.4rem;
        }

        .cliente-entrega {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cliente-container {
            border: #FFA500 2px solid;
            border-radius: 15px;
            min-width: 561px;
            width: 561px;
            height: auto;
        }

        .nome-container, .tel-container, .login-container {
            display: flex;
            flex-direction: row;
            gap: 10px;
            width: auto;
            .nome-space, .tel-space, .login-space {
                display: flex;
                background-color: #202020;
                border-radius: 12px;
                height: 48px;
                align-items: center;
            }
        }

        .cliente-info {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 12px;
            width: auto;
        }

        .cliente-info h3.cliente-info {
            color: #FFF;
            font-size: 27px;
            font-family: Rajdhani;
            font-weight: 600;
            word-wrap: break-word;
        }

        .cliente-info-titulo {
            color: #FFF;
            font-family: Oswald;
            font-size: 30px;
            font-weight: 400;
            word-wrap: break-word;
        }

        .entrega-container {
            border: #FFA500 2px solid;
            border-radius: 15px;
            min-width: 561px;
            width: 561px;
        }

        .entrega-content{
            margin: 8px;
        }

        .tipo-entrega{
            color: #fff;
            font-size: 30px;
            font-family: Oswald;
            margin-top: -7px;
            margin-bottom: 2px;
            font-weight: 400;
            word-wrap: break-word;
            text-align: center;
        }

        .info-entrega-container {
            background-color: #202020;
            border-radius: 12px;
            padding: 5px;
        }
        
        .info-entrega {
            display: flex;
            justify-content: center;
            background-color: #282828;
            border-radius: 8px;
        }

        .local_entrega {
            color: #FFF;
            max-width: 95%;
            text-align: justify;
        }

        .pagamento-container, .status-container {
            display: flex;
            flex-direction: row;
        }
        
        .preco-container, .formapag-container, .status-atual, .atualizar-btn {
            border: #FFA500 solid 2px;

        }

        .container-bottom {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .pagamento-container {
            display: flex;
            width: auto;
            flex-direction: row;
            gap: 5px;
        }

        .preco-container, .status-atual {
            height: 54px;
            border-radius: 14px 4px 4px 14px;
            align-items: center;
        }

        .preco-container h2 {
            margin-right: 85px; 
            margin-left: 10px
        }

        .formapag-container, .atualizar-btn {
            height: 54px;
            border-radius: 4px 14px 14px 4px;
            align-items: center;
        }

        .formapag-container h2 {
            margin-right: 8px; 
            margin-left: 5px; 
            font-weight: 500;
        }

        .status-container {
            display: flex;
            flex-direction: row;
            width: 561px;
            gap: 5px;
        }

        .status-atual {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 406px;
        }

        .status_pedido {
            color: #FFF; 
            font-family: Rajdhani; 
            font-size: 27px; 
            font-weight: 600; 
            margin-left: 15px; 
            margin-right: 15px;
        }

        .atualizar-btn {
            width: 150px;
            background-color: #282828;
            color: #fff;
            h3 {
                font-family: Oswald;
                font-size: 30px;
                font-weight: 400;
                height: 100%;
            }
            transition: all 0.3s ease;
        }

        .atualizar-btn:hover {
            background-color: #FFA500;
            color: #282828;
            
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
            justify-content: center;
            width: 145px;
            height: 46px;
        }

        .social-links {
            display: flex;
            gap: 15px;
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

        .social-links-icon {
            height: 36px;
            border-radius: 8px;
            padding: 3px;
            border: #fff 2px solid;
            transition: all 0.3s ease;
        }

        .social-links:hover {
            p {
                color: var(--primary-color);
                text-shadow: 0 0px 8px var(--primary-color);
            }

            .social-links-icon {
                border: #FFA500 2px solid;
            }
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Página Inicial -->
    <section id="pedidos" class="page active">
        <div class="container">
            <h1 class="titulo-pagina">Pedidos</h1>
            <div class="pedidos-container">
                <div class="pedido">
                    <div class="pedido-header">
                        <h2 class="pedido_id-hora"> PEDIDO #{id} - 11:02</h2>
                    </div>
                    <div class="pedido-info">
                        <div class="container-top">
                            <div class="itens-container">
                                <div class="itens-header">
                                    <h2 class="titulo-dados-pedido">Itens</h2>
                                </div>
                                <div class="itens">
                                    <div class="space" style="height: 5px;"></div>
                                    <div class="item">
                                        <div class="item-info">
                                            <div class="container-1">
                                                <h3 class="item_qtd-nome">1 × Pizza Grande</h3>
                                                <div class="desc-preco">
                                                    <h3 class="desc">Queijo, Azeitonas, Bacon, Pepperoni, Cogumelos, Tomate, Manjericão, Cebola, Pimentão, Alho</h3>
                                                </div>
                                                <div class="desc-preco">
                                                    <h3 class="preco">R$ 61,50</h3>
                                                </div>
                                            </div>
                                            <div class="container-2">
                                                <div class="observacao-container">
                                                    <p class="observacao">
                                                        Boa noite! Quero pedir, encarecidamente, para que vocês tirem o queijo, pois sou intolerante à lactose. Gostaria de pedir, também, para que vocês adicionassem guardanapos. Eles me são muito úteis. N sei se você percebeu, mas eu gosto muito de escrever. kk
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-info">
                                            <div class="container-1">
                                                <h3 class="item_qtd-nome">1 × Chocolate com Morango</h3>
                                                <div class="desc-preco">
                                                    <h3 class="desc">Chocolate ao leite e morangos frescos</h3>
                                                </div>
                                                <div class="desc-preco">
                                                    <h3 class="preco">R$ 48,50</h3>
                                                </div>
                                            </div>
                                            <div class="container-2">
                                                <div class="observacao-container">
                                                    <p class="observacao">

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cliente-entrega">
                                <div class="cliente-container">
                                    <div class="cliente-header">
                                        <h2 class="titulo-dados-pedido">Cliente #{id}</h2>
                                    </div>
                                    <div class="cliente-info">
                                        <div class="nome-container">
                                                <h3 class="cliente-info-titulo">Nome</h3>
                                                <div class="nome-space">
                                                    <h3 class="cliente-info">Daniel Araújo</h3>
                                                </div>
                                        </div>
                                        <div class="tel-container">
                                                <h3 class="cliente-info-titulo">Tel.</h3>
                                                <div class="tel-space">
                                                    <h3 class="cliente-info">(31) 9 9978-2383</h3>
                                                </div>
                                        </div>
                                        <div class="login-container">
                                                <h3 class="cliente-info-titulo">Login</h3>
                                                <div class="login-space" style="width: 451px;">
                                                    <h3 class="cliente-info">daniel271207.ac@gmail.com</h3>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="entrega-container">
                                    <div class="entrega-header">
                                        <h2 class="titulo-dados-pedido">Local de Entrega</h2>
                                    </div>
                                    <div class="entrega-content">
                                        <h3 class="tipo-entrega">Delivery</h3>
                                        <div class="info-entrega-container">
                                            <div class="info-entrega">
                                                <h3 class="local_entrega">
                                                    Ipatinga, MG; Bairro Iguaçu; Rua Caetés; nº 320, Apto 704; “Em frente à Regis Pizzaria”
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container-bottom">
                            <div class="pagamento-container">
                                <div class="preco-container">
                                    <h2 class="titulo-dados-pedido">R$ 123,00</h2>
                                </div>
                                <div class="formapag-container">
                                    <h2 class="titulo-dados-pedido">Cartão de Crédito</h2>
                                </div>
                            </div>
                            <div class="status-container">
                                <div class="status-atual">
                                    <h2 class="titulo-dados-pedido" style="margin-left: 15px">Status</h2>
                                    <h3 class="status_pedido">< Em Processamento ></h3>
                                </div>
                                <button class="atualizar-btn">
                                    <h3>Atualizar</h3>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>