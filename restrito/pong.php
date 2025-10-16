<?php include "verifica_login.php"; 
if ($_SESSION['class_nivel'] !== 1):
    if ($_SESSION['class_nivel'] !== 6):
        header('Location: index.php?msg=Agora-nao-eh-hora-de-jogar');
        exit();
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pong do Cavanha</title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #261a6c, #ad4727, #fd9c2d);
            background-size: 100vw 100vh;
            color: var(--text-color);
            line-height: 1.6;
            overflow: hidden;
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
            width: 700px;
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

        /* Páginas */
        .page {
            display: none;
            padding: 40px 0;
        }

        .page.active {
            display: block;
        }

        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .star {
            position: absolute;
            background-color: white;
            border-radius: 50%;
            animation: twinkle linear infinite;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 1;
            }
        }

                /* Jogo Pong */
        .cntr-gamecontainer {
            display: flex;
            justify-content: center;
        }

        #game-container {
            margin-top: 15vh;
            position: relative;
            width: 800px;
            height: 500px;
            background-color: #000;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        #game-canvas {
            background-color: #111;
            display: block;
        }

        #score {
            position: absolute;
            top: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            color: white;
            font-size: 40px;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            z-index: 2;
        }

        .player1 {
            color: #4fc3f7;
        }

        .player2 {
            color: #ff5252;
        }

        #middle-line {
            position: absolute;
            top: 0;
            left: 50%;
            width: 2px;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            z-index: 1;
        }

        #start-screen,
        #game-over {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            z-index: 10;
        }

        .pong-classico {
            font-size: 58px;
            font-family: Rajdhani;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .start-button {
            padding: 15px 30px;
            font-size: 20px;
            background: linear-gradient(45deg, #4fc3f7, #2196f3);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 11;
        }

        .start-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .controls {
            margin-top: 30px;
            text-align: center;
        }

        .controls p {
            margin: 10px 0;
            font-size: 18px;
        }

        .controles-container {
            display: flex;
            margin-top: 15vh;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            height: 500px;
            width: 56px;
        }

        .control-keys {
            height: 50%;
            width: 55px;
            border-radius: 10px;
            border: none;
            margin-top: 2px;
            margin-bottom: 2px;
            z-index: 12;
            transition: all 0.3s;
        }

        .control-keys.w-s {
            background-color: #4fc3f7;
            font-size: 36px;
            font-family: Jaro;
            font-weight: 500;
        }

        .control-keys.w-s:hover {
            background-color: #34a6db;
            box-shadow: 0 0px 10px #0000004d;
            color: #FFF;
        }

        .control-keys.up-down {
            background-color: #ff5252;
            font-size: 32px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-weight: 600;
        }

        .control-keys.up-down:hover {
            background-color: #f54242;
            box-shadow: 0 0px 10px #0000004d;
            color: #FFF;
        }

        .player1-key {
            color: #4fc3f7;
            font-weight: bold;
        }

        .player2-key {
            color: #ff5252;
            font-weight: bold;
        }

        .hidden {
            display: none !important;
        }

        #winner-message {
            font-size: 36px;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .winner-player1 {
            color: #4fc3f7;
            text-shadow: 0 0 10px rgba(79, 195, 247, 0.7);
        }

        .winner-player2 {
            color: #ff5252;
            text-shadow: 0 0 10px rgba(255, 82, 82, 0.7);
        }

    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="index.php" class="nav-link">
                <div class="logo">
                    <img src="assets/logo.svg" alt="Logo">
                    <h1>Pizza do Cavanha</h1>
                </div>
            </a>
            <nav>
                <ul>
                    <li><a href="index.php" class="nav-link" data-page="home">Início</a></li>
                    <li><a href="index.php" class="nav-link" data-page="cardapio">Cardápio</a></li>
                    <li><a href="index.php" class="nav-link" data-page="carrinho">Carrinho</a></li>

                    <?php if ($_SESSION['class_nivel'] !== 1): ?>
                        <li><a href="funcionario/index.php">Área do Funcionário</a></li>
                    <?php endif; ?>

                    <?php if (($_SESSION['class_nivel'] == 1) || ($_SESSION['class_nivel'] == 6)): ?>
                        <li><a href="#">Esperando a Pizza?</a></li>
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
    <div class="container">
        <div class="cntr-gamecontainer">
            <div class="cntr-controlescontainer">
                <div class="controles-container">
                    <button id="w-key" class="control-keys w-s">W</button>
                    <button id="s-key" class="control-keys w-s">S</button>
                </div>
            </div>
            <div id="game-container">
                <div id="middle-line"></div>
                <div id="score">
                    <span class="player1" id="player1-score">0</span>
                    <span>&nbsp;:&nbsp;</span>
                    <span class="player2" id="player2-score">0</span>
                </div>

                <canvas id="game-canvas" width="800" height="500"></canvas>

                <div id="start-screen">
                    <h1 class="pong-classico">PONG CLÁSSICO</h1>
                    <button id="start-button" class="start-button">INICIAR JOGO</button>
                    <div class="controls">
                        <p>Jogador 1: <span class="player1-key">W</span> (cima) e <span class="player1-key">S</span> (baixo)</p>
                        <p>Jogador 2: <span class="player2-key">↑</span> (cima) e <span class="player2-key">↓</span> (baixo)</p>
                    </div>
                </div>

                <div id="game-over" class="hidden">
                    <div id="winner-message"></div>
                    <button id="restart-button" class="start-button">JOGAR NOVAMENTE</button>
                </div>
            </div>
            <div class="cntr-controlescontainer">
                <div class="controles-container">
                    <button id="up-key" class="control-keys up-down">↑</button>
                    <button id="down-key" class="control-keys up-down">↓</button>
                </div>
            </div>
        </div>
    </div>
    <div class="stars" id="stars"></div>
    <script>
        // Estrelinhas piscando
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.getElementById('stars');
            const starsCount = 150;

            for (let i = 0; i < starsCount; i++) {
                const star = document.createElement('div');
                star.classList.add('star');

                // Posição aleatória
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;

                // Tamanho aleatório entre 1 e 3 pixels
                const size = Math.random() * 2 + 1;

                // Duração da animação aleatória
                const duration = Math.random() * 5 + 5;

                star.style.left = `${posX}%`;
                star.style.top = `${posY}%`;
                star.style.width = `${size}px`;
                star.style.height = `${size}px`;
                star.style.animationDuration = `${duration}s`;
                star.style.animationDelay = `${Math.random() * duration}s`;

                starsContainer.appendChild(star);
            }
        });

        // Configurações do jogo
        const canvas = document.getElementById('game-canvas');
        const ctx = canvas.getContext('2d');
        const startScreen = document.getElementById('start-screen');
        const gameOverScreen = document.getElementById('game-over');
        const startButton = document.getElementById('start-button');
        const restartButton = document.getElementById('restart-button');
        const player1ScoreElement = document.getElementById('player1-score');
        const player2ScoreElement = document.getElementById('player2-score');
        const winnerMessage = document.getElementById('winner-message');

        // Variáveis do jogo
        let gameRunning = false;
        let animationId;
        let player1Score = 0;
        let player2Score = 0;
        const winningScore = 5;

        // Objetos do jogo
        const ball = {
            x: canvas.width / 2,
            y: canvas.height / 2,
            radius: 10,
            speed: 5,
            dx: 5,
            dy: 5,
            color: '#fff',
            reset() {
                this.x = canvas.width / 2;
                this.y = canvas.height / 2;
                this.dx = this.speed * (Math.random() > 0.5 ? 1 : -1);
                this.dy = this.speed * (Math.random() > 0.5 ? 1 : -1);
            }
        };

        const paddle1 = {
            x: 20,
            y: canvas.height / 2 - 50,
            width: 15,
            height: 100,
            speed: 8,
            color: '#4fc3f7',
            upPressed: false,
            downPressed: false,
            update() {
                if (this.upPressed && this.y > 0) {
                    this.y -= this.speed;
                }
                if (this.downPressed && this.y < canvas.height - this.height) {
                    this.y += this.speed;
                }
            }
        };

        const paddle2 = {
            x: canvas.width - 35,
            y: canvas.height / 2 - 50,
            width: 15,
            height: 100,
            speed: 8,
            color: '#ff5252',
            upPressed: false,
            downPressed: false,
            update() {
                if (this.upPressed && this.y > 0) {
                    this.y -= this.speed;
                }
                if (this.downPressed && this.y < canvas.height - this.height) {
                    this.y += this.speed;
                }
            }
        };

        // Funções de desenho
        function drawBall() {
            ctx.beginPath();
            ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI * 2);
            ctx.fillStyle = ball.color;
            ctx.fill();
            ctx.closePath();

            // Efeito de brilho
            const gradient = ctx.createRadialGradient(
                ball.x, ball.y, ball.radius / 2,
                ball.x, ball.y, ball.radius
            );
            gradient.addColorStop(0, '#fff');
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0.3)');

            ctx.beginPath();
            ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI * 2);
            ctx.fillStyle = gradient;
            ctx.fill();
            ctx.closePath();
        }

        function drawPaddle(paddle) {
            // Efeito de gradiente
            const gradient = ctx.createLinearGradient(
                paddle.x, paddle.y,
                paddle.x + paddle.width, paddle.y + paddle.height
            );
            gradient.addColorStop(0, paddle.color);
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0.5)');

            ctx.beginPath();
            ctx.roundRect(paddle.x, paddle.y, paddle.width, paddle.height, 10);
            ctx.fillStyle = gradient;
            ctx.fill();
            ctx.closePath();

            // Borda brilhante
            ctx.beginPath();
            ctx.roundRect(paddle.x, paddle.y, paddle.width, paddle.height, 10);
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.8)';
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.closePath();
        }

        function drawMiddleLine() {
            ctx.beginPath();
            ctx.setLineDash([10, 15]);
            ctx.moveTo(canvas.width / 2, 0);
            ctx.lineTo(canvas.width / 2, canvas.height);
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.setLineDash([]);
        }

        // Funções de colisão
        function collision(b, p) {
            return (
                b.x + b.radius > p.x &&
                b.x - b.radius < p.x + p.width &&
                b.y + b.radius > p.y &&
                b.y - b.radius < p.y + p.height
            );
        }

        function updateBall() {
            // Movimento da bola
            ball.x += ball.dx;
            ball.y += ball.dy;

            // Colisão com as paredes superior e inferior
            if (ball.y + ball.radius > canvas.height || ball.y - ball.radius < 0) {
                ball.dy = -ball.dy;

                // Efeito sonoro (simulado com um beep)
                if (gameRunning) {
                    beep(200, 30, 0.1);
                }
            }

            // Colisão com as raquetes
            if (collision(ball, paddle1) || collision(ball, paddle2)) {
                ball.dx = -ball.dx * 1.05; // Aumenta a velocidade em 5% a cada rebatida

                // Adiciona um efeito de curva baseado na posição de contato
                const paddle = collision(ball, paddle1) ? paddle1 : paddle2;
                const hitPosition = (ball.y - (paddle.y + paddle.height / 2)) / (paddle.height / 2);
                ball.dy = hitPosition * 5;

                // Efeito sonoro
                if (gameRunning) {
                    beep(300, 50, 0.1);
                }
            }

            // Pontuação
            if (ball.x + ball.radius < 0) {
                player2Score++;
                updateScore();
                ball.reset();

                // Efeito sonoro de ponto
                if (gameRunning) {
                    beep(100, 200, 0.3);
                }
            } else if (ball.x - ball.radius > canvas.width) {
                player1Score++;
                updateScore();
                ball.reset();

                // Efeito sonoro de ponto
                if (gameRunning) {
                    beep(500, 200, 0.3);
                }
            }
        }

        function updateScore() {
            player1ScoreElement.textContent = player1Score;
            player2ScoreElement.textContent = player2Score;

            // Verifica se alguém ganhou
            if (player1Score >= winningScore || player2Score >= winningScore) {
                endGame();
            }
        }

        // Função de beep para efeitos sonoros simples
        function beep(freq, duration, volume) {
            try {
                const audioCtx = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                gainNode.gain.value = volume;
                oscillator.frequency.value = freq;
                oscillator.type = 'sine';

                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                }, duration);
            } catch (e) {
                console.log("Erro no áudio:", e);
            }
        }

        // Função principal do jogo
        function gameLoop() {
            // Limpa o canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Desenha os elementos
            drawMiddleLine();
            drawBall();
            drawPaddle(paddle1);
            drawPaddle(paddle2);

            // Atualiza os objetos
            paddle1.update();
            paddle2.update();
            updateBall();

            // Continua o loop
            if (gameRunning) {
                animationId = requestAnimationFrame(gameLoop);
            }
        }

        // Controles
        function keyDownHandler(e) {
            switch (e.key) {
                case 'w':
                case 'W':
                    paddle1.upPressed = true;
                    break;
                case 's':
                case 'S':
                    paddle1.downPressed = true;
                    break;
                case 'ArrowUp':
                    paddle2.upPressed = true;
                    break;
                case 'ArrowDown':
                    paddle2.downPressed = true;
                    break;
            }
        }

        function keyUpHandler(e) {
            switch (e.key) {
                case 'w':
                case 'W':
                    paddle1.upPressed = false;
                    break;
                case 's':
                case 'S':
                    paddle1.downPressed = false;
                    break;
                case 'ArrowUp':
                    paddle2.upPressed = false;
                    break;
                case 'ArrowDown':
                    paddle2.downPressed = false;
                    break;
            }
        }

        // Funções para controle por botões
        function setupButtonControls() {
            // Jogador 1 - W e S
            document.getElementById('w-key').addEventListener('mousedown', () => paddle1.upPressed = true);
            document.getElementById('w-key').addEventListener('mouseup', () => paddle1.upPressed = false);
            document.getElementById('w-key').addEventListener('touchstart', () => paddle1.upPressed = true);
            document.getElementById('w-key').addEventListener('touchend', () => paddle1.upPressed = false);

            document.getElementById('s-key').addEventListener('mousedown', () => paddle1.downPressed = true);
            document.getElementById('s-key').addEventListener('mouseup', () => paddle1.downPressed = false);
            document.getElementById('s-key').addEventListener('touchstart', () => paddle1.downPressed = true);
            document.getElementById('s-key').addEventListener('touchend', () => paddle1.downPressed = false);

            // Jogador 2 - ↑ e ↓
            document.getElementById('up-key').addEventListener('mousedown', () => paddle2.upPressed = true);
            document.getElementById('up-key').addEventListener('mouseup', () => paddle2.upPressed = false);
            document.getElementById('up-key').addEventListener('touchstart', () => paddle2.upPressed = true);
            document.getElementById('up-key').addEventListener('touchend', () => paddle2.upPressed = false);

            document.getElementById('down-key').addEventListener('mousedown', () => paddle2.downPressed = true);
            document.getElementById('down-key').addEventListener('mouseup', () => paddle2.downPressed = false);
            document.getElementById('down-key').addEventListener('touchstart', () => paddle2.downPressed = true);
            document.getElementById('down-key').addEventListener('touchend', () => paddle2.downPressed = false);
        }

        // Iniciar jogo
        function startGame() {
            // Esconde todas as telas de UI primeiro
            startScreen.classList.add('hidden');
            gameOverScreen.classList.add('hidden');

            // Reinicia o placar
            player1Score = 0;
            player2Score = 0;
            updateScore();

            // Reinicia a bola
            ball.reset();

            // Posiciona as raquetes
            paddle1.y = canvas.height / 2 - paddle1.height / 2;
            paddle2.y = canvas.height / 2 - paddle2.height / 2;

            // Configura os controles por botão
            setupButtonControls();

            // Inicia o jogo
            gameRunning = true;
            gameLoop();
        }

        // Finalizar jogo
        function endGame() {
            gameRunning = false;
            cancelAnimationFrame(animationId);

            // Mostra a tela de game over
            gameOverScreen.classList.remove('hidden');

            // Define a mensagem do vencedor
            if (player1Score > player2Score) {
                winnerMessage.textContent = 'Jogador 1 Venceu!';
                winnerMessage.className = 'winner-player1';
            } else {
                winnerMessage.textContent = 'Jogador 2 Venceu!';
                winnerMessage.className = 'winner-player2';
            }
        }

        // Event listeners
        document.addEventListener('keydown', keyDownHandler);
        document.addEventListener('keyup', keyUpHandler);

        startButton.addEventListener('click', function() {
            startGame();
        });

        restartButton.addEventListener('click', function() {
            startGame();
        });

        // Inicialização do jogo
        window.addEventListener('load', function() {
            // Garante que apenas a tela inicial está visível
            startScreen.classList.remove('hidden');
            gameOverScreen.classList.add('hidden');

            // Inicializa o placar
            updateScore();
        });
    </script>
</body>

</html>