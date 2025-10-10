<?php
include "../verifica_login.php";
include "../conexao.php";

// Buscar pedidos do usuário
$id_usuario = $_SESSION['id'];

// Query para buscar pedidos com todas as informações
$sql = "
    SELECT 
        p.id as pedido_id,
        p.valor as total,
        p.horario,
        e.id as entrega_id,
        e.endereco,
        et.tipo as tipo_entrega,
        fp.nome as forma_pagamento,
        es.nome as estado,
        es.id as estado_id
    FROM pedido p
    LEFT JOIN entrega e ON p.id_entrega = e.id
    LEFT JOIN tipo_entrega et ON e.id_tipo = et.id
    LEFT JOIN formapag fp ON p.id_formapag = fp.id
    LEFT JOIN estados es ON p.id_estado = es.id
    WHERE p.id_cliente = ?
    ORDER BY p.horario DESC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Para cada pedido, buscar os itens
foreach ($pedidos as &$pedido) {
    $sql_itens = "
        SELECT 
            i.id as item_id,
            i.nome,
            i.quantidade,
            i.valor as preco_unitario,
            i.descricao,
            i.observacao,
            (i.quantidade * i.valor) as subtotal
        FROM pedido_itens pi
        LEFT JOIN itens i ON pi.id_item = i.id
        WHERE pi.id_pedido = ?
    ";

    $stmt_itens = mysqli_prepare($conn, $sql_itens);
    mysqli_stmt_bind_param($stmt_itens, "i", $pedido['pedido_id']);
    mysqli_stmt_execute($stmt_itens);
    $result_itens = mysqli_stmt_get_result($stmt_itens);
    $pedido['itens'] = mysqli_fetch_all($result_itens, MYSQLI_ASSOC);

    // Para cada item, buscar ingredientes/adicionais
    foreach ($pedido['itens'] as &$item) {
        $sql_ingredientes = "
            SELECT ing.nome
            FROM ingredientes_itens ii
            LEFT JOIN ingredientes ing ON ii.id_ingrediente = ing.id
            WHERE ii.id_item = ?
        ";

        $stmt_ing = mysqli_prepare($conn, $sql_ingredientes);
        mysqli_stmt_bind_param($stmt_ing, "i", $item['item_id']);
        mysqli_stmt_execute($stmt_ing);
        $result_ing = mysqli_stmt_get_result($stmt_ing);
        $ingredientes = mysqli_fetch_all($result_ing, MYSQLI_ASSOC);

        $item['ingredientes'] = array_map(function ($ing) {
            return $ing['nome'];
        }, $ingredientes);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seus Pedidos</title>
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
            font-size: 23px;
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
            font-size: 19px;
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

        .conteudo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 155px;
        }

        .titulo-pagina {
            text-align: center;
            margin: 20px 0 40px 0;
            font-family: Oswald;
            font-weight: 400;
            font-size: 43px;
            color: var(--primary-color)
        }

        .pedidos {
            display: flex;
            flex-direction: column;
            gap: 120px;
        }

        .pedido {
            display: flex;
            flex-direction: column;
            width: 1020px;
            margin-bottom: 20px;
        }

        .pedido-header {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            background-color: #FFA500;
            border-radius: 25px 25px 0 0;

            .pedido_id {
                font-family: Rajdhani;
                font-size: 38px;
                font-weight: 900;
                color: #FFF;
            }
        }

        .pedido-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-width: 0 3px 3px;
            border-radius: 0 0 8px 8px;
            border: #FFA500 solid;
            background-color: #222;
        }

        .pedido-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 16px;
            margin-bottom: 5px;
        }

        .left {
            .itens {
                background-color: #2B2B2B;
                overflow-y: auto;
                max-height: 240px;
                min-height: 240px;
                width: 500px;
                box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.15);
            }

            .itens-content {
                display: flex;
                flex-direction: column;
                gap: 7px;
                margin: 10px 15px;
            }

            .item {
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 20px;

                .top {
                    display: flex;
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: center;

                    .qtd-nome {
                        color: #FFA500;
                        font-size: 32px;
                        font-family: Rajdhani;
                        font-weight: 700;
                        max-width: 350px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .preco {
                        color: white;
                        font-size: 32px;
                        font-family: Rajdhani;
                        font-weight: 600;
                        word-wrap: break-word;
                    }

                }

                .bottom {
                    display: flex;
                    flex-direction: column;
                    margin-top: -5px;
                    gap: 15px;

                    .observacao-container,
                    .ingredientes-container {
                        border-left: #FFF solid 2px;
                        margin-left: 5px;
                    }

                    .observacao {
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .observacao,
                    .ingredientes {
                        font-family: Rajdhani;
                        font-size: 27px;
                        font-weight: 500;
                        line-height: 1.2;
                        margin-left: 15px;
                        max-width: 90%;
                        color: #FFF;
                    }
                }
            }
        }

        .right {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: end;
            width: 400px;

            .entrega,
            .data-hora,
            .pagamento {
                display: flex;
                flex-direction: row;
                justify-content: space-evenly;
                align-items: center;
                background-color: #2B2B2B;
                height: 42px;
                box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.15);


                hr {
                    color: #FFF;
                    height: 100%;
                }
            }

            .top,
            .bottom {
                display: flex;
                flex-direction: column;
                align-items: end;
                gap: 10px;
                width: 100%;
            }

            .entrega {
                width: 100%;

                .delivery-retirada h3 {
                    font-family: Rajdhani;
                    font-size: 32px;
                    font-weight: 600;
                    color: #FFF;

                }

                .endereco-mesa h3 {
                    font-family: Rajdhani;
                    font-size: 32px;
                    font-weight: 700;
                    color: #FFA500;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    max-width: 235px;
                }
            }

            .data-hora {
                width: 240px;

                h3 {
                    font-family: Rajdhani;
                    font-size: 32px;
                    font-weight: 700;
                    color: #FFA500;
                }
            }

            .pagamento {
                width: 100%;

                .formapag h3 {
                    font-family: Rajdhani;
                    font-size: 30px;
                    font-weight: 600;
                    color: #FFF;
                }

                .preco h3 {
                    font-family: Rajdhani;
                    font-size: 30px;
                    font-weight: 700;
                    color: #FFA500;
                    max-width: 235px;
                }

            }
        }

        .status-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: -3px;

            .status-pedido {
                color: #FFA500;
                font-size: 32px;
                font-family: Rajdhani;
                font-weight: 500;
                margin-top: 4px;
                margin-bottom: -4px;
                word-wrap: break-word;
            }

            .status-visual {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: baseline;
                padding: 0 15px 0 15px;
                background-color: #2B2B2B;
                border: #FFA500 solid;
                height: 40px;
                width: 530px;
                border-width: 3px 3px 0 3px;
                border-radius: 15px 15px 0 0;

                .ball {
                    height: 16px;
                    width: 16px;
                    border-radius: 8px;
                    background-color: #D9D9D9;
                }

                hr {
                    background-color: #D9D9D9;
                    width: 100%;
                }

                /*
                .em-processamento {
                    color: #FFA500;

                    .ball {
                        background-color: #FFA500;
                        box-shadow: 0px 0px 10px #ffa5004d;
                    }
                }

                .preparando {
                    color: #FFA500;

                    .ball {
                        background-color: #FFA500;
                        box-shadow: 0px 0px 10px #ffa5004d;
                    }
                }
                */
            }
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

        /* Classes para pedidos minimizados */
        .pedido.minimizado .pedido-container {
            display: none;
        }

        .pedido.minimizado .pedido-header {
            border-radius: 25px;
            margin-bottom: 20px;
        }

        .toggle-pedido {
            background: none;
            border: none;
            color: #FFF;
            font-size: 20px;
            cursor: pointer;
            margin-left: 15px;
            transition: transform 0.3s ease;
        }

        .pedido.minimizado .toggle-pedido {
            transform: rotate(180deg);
        }

        .header-com-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Estados da barra de progresso */
        .status-visual .ball {
            height: 16px;
            width: 16px;
            border-radius: 8px;
            background-color: #666;
            transition: all 0.3s ease;
        }

        .status-visual .ball.ativo {
            background-color: #FFA500;
            box-shadow: 0px 0px 10px #ffa5004d;
        }

        .status-visual .ball.concluido {
            background-color: #FFA500;
            box-shadow: 0px 0px 10px #ffa5004d;
        }

        .status-visual hr {
            background-color: #666;
            height: 2px;
            border: none;
            transition: all 0.3s ease;
        }

        .status-visual hr.ativo {
            background-color: #FFA500;
        }

        .status-visual hr.concluido {
            background-color: #FFA500;
        }

        /* Cores para diferentes estados */
        .status-pedido.cancelado {
            color: #f44336;
        }

        .sem-pedidos {
            text-align: center;
            padding: 60px 20px;
            color: #888;
            font-family: 'Rajdhani', sans-serif;
            font-size: 24px;
        }

        .sem-pedidos i {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
            color: #FFA500;
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
                            <li><a href="sua-conta.php" class="opt-user-link">Sua Conta</a></li>
                            <li><a href="#" class="opt-user-link">Seus Pedidos</a></li>
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
    <div class="container">
        <h1 class="titulo-pagina">Seus Pedidos</h1>
        <div class="container">
            <div class="conteudo">
                <div class="pedidos">
                    <?php if (empty($pedidos)): ?>
                        <div class="sem-pedidos">
                            <p style="font-size: 18px; margin-top: 10px;">Nenhum pedido encontrado.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php
                            $estaFinalizado = in_array($pedido['estado_id'], [4, 5]); // 4=Entregue, 5=Cancelado
                            $classeMinimizado = $estaFinalizado ? 'minimizado' : '';
                            ?>

                            <div class="pedido <?php echo $classeMinimizado; ?>" data-pedido-id="<?php echo $pedido['pedido_id']; ?>">
                                <div class="pedido-header">
                                    <div class="header-com-toggle">
                                        <h2 class="pedido_id">PEDIDO #<?php echo $pedido['pedido_id']; ?></h2>
                                        <?php if ($estaFinalizado): ?>
                                            <button class="toggle-pedido">▼</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="pedido-container">
                                    <div class="pedido-content">
                                        <div class="left">
                                            <div class="itens">
                                                <div class="itens-content">
                                                    <?php foreach ($pedido['itens'] as $item): ?>
                                                        <div class="item">
                                                            <div class="item-content">
                                                                <div class="top">
                                                                    <h3 class="qtd-nome"><?php echo $item['quantidade']; ?> × <?php echo htmlspecialchars($item['nome']); ?></h3>
                                                                    <h3 class="preco">R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></h3>
                                                                </div>
                                                                <div class="bottom">
                                                                    <?php if (!empty($item['ingredientes'])): ?>
                                                                        <div class="ingredientes-container">
                                                                            <h3 class="ingredientes"><?php echo htmlspecialchars(implode(', ', $item['ingredientes'])); ?></h3>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($item['observacao'])): ?>
                                                                        <div class="observacao-container">
                                                                            <h3 class="observacao"><?php echo htmlspecialchars($item['observacao']); ?></h3>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="top">
                                                <div class="entrega">
                                                    <div class="delivery-retirada">
                                                        <h3><?php echo htmlspecialchars($pedido['tipo_entrega']); ?></h3>
                                                    </div>
                                                    <hr>
                                                    <div class="endereco-mesa">
                                                        <h3><?php echo htmlspecialchars($pedido['endereco']); ?></h3>
                                                    </div>
                                                </div>
                                                <div class="data-hora">
                                                    <h3><?php echo date('d/m/y ∙ H:i', strtotime($pedido['horario'])); ?></h3>
                                                </div>
                                            </div>
                                            <div class="bottom">
                                                <div class="pagamento">
                                                    <div class="formapag">
                                                        <h3><?php echo htmlspecialchars($pedido['forma_pagamento']); ?></h3>
                                                    </div>
                                                    <hr>
                                                    <div class="preco">
                                                        <h3>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="status-container">
                                        <h3 class="status-pedido <?php echo strtolower(str_replace(' ', '-', $pedido['estado'])); ?>">
                                            <?php echo htmlspecialchars($pedido['estado']); ?>
                                        </h3>
                                        <div class="status-visual">
                                            <?php
                                            $estados = [
                                                1 => ['nome' => 'em-processamento', 'posicao' => 1],
                                                2 => ['nome' => 'preparando', 'posicao' => 2],
                                                3 => ['nome' => 'enviado', 'posicao' => 3],
                                                4 => ['nome' => 'entregue', 'posicao' => 4],
                                                5 => ['nome' => 'cancelado', 'posicao' => 0] // Cancelado não mostra barra
                                            ];

                                            $estado_atual = $pedido['estado_id'];
                                            $mostrar_barra = $estado_atual != 5; // Não mostra barra se cancelado

                                            if ($mostrar_barra):
                                            ?>
                                                <!-- Em Processamento -->
                                                <div class="<?php echo $estados[1]['nome']; ?>">
                                                    <div class="ball <?php echo $estado_atual >= 1 ? ($estado_atual > 1 ? 'concluido' : 'ativo') : ''; ?>"></div>
                                                </div>
                                                <hr class="<?php echo $estado_atual > 2 ? 'concluido' : ($estado_atual == 2 ? 'ativo' : ''); ?>">

                                                <!-- Preparando -->
                                                <div class="<?php echo $estados[2]['nome']; ?>">
                                                    <div class="ball <?php echo $estado_atual >= 2 ? ($estado_atual > 2 ? 'concluido' : 'ativo') : ''; ?>"></div>
                                                </div>
                                                <hr class="<?php echo $estado_atual > 3 ? 'concluido' : ($estado_atual == 3 ? 'ativo' : ''); ?>">

                                                <!-- Enviado -->
                                                <div class="<?php echo $estados[3]['nome']; ?>">
                                                    <div class="ball <?php echo $estado_atual >= 3 ? ($estado_atual > 3 ? 'concluido' : 'ativo') : ''; ?>"></div>
                                                </div>
                                                <hr class="<?php echo $estado_atual > 4 ? 'concluido' : ($estado_atual == 4 ? 'ativo' : ''); ?>">

                                                <!-- Entregue -->
                                                <div class="<?php echo $estados[4]['nome']; ?>">
                                                    <div class="ball <?php echo $estado_atual >= 4 ? 'concluido' : ''; ?>"></div>
                                                </div>

                                            <?php else: ?>
                                                <!-- Estado Cancelado -->

                                                <div>
                                                    <div class="ball" style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d;"></div>
                                                </div>
                                                <hr style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d; ">
                                                <div>
                                                    <div class="ball" style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d;"></div>
                                                </div>
                                                <hr style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d; ">
                                                <div>
                                                    <div class="ball" style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d;"></div>
                                                </div>
                                                <hr style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d; ">
                                                <div>
                                                    <div class="ball" style="background-color: #f44336; box-shadow: 0px 0px 10px #f443364d;"></div>
                                                </div>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
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
        // Sistema de minimizar/maximizar pedidos finalizados
        document.querySelectorAll('.toggle-pedido').forEach(btn => {
            btn.addEventListener('click', function() {
                const pedido = this.closest('.pedido');
                pedido.classList.toggle('minimizado');

                // Atualizar ícone
                this.textContent = pedido.classList.contains('minimizado') ? '▼' : '▼';
            });
        });

        // Função para atualizar status em tempo real (opcional)
        function atualizarStatusPedidos() {
            // Esta função pode ser usada para atualizações em tempo real
            // se você implementar WebSockets ou AJAX polling no futuro
            console.log('Status dos pedidos verificados');
        }

        // Atualizar a cada 30 segundos (opcional)
        setInterval(atualizarStatusPedidos, 60000);
    </script>
</body>