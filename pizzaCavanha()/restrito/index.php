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
    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome para ícones -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                    <li><a href="#" class="nav-link" data-page="cardapio">Cardápio</a></li>
                    <li><a href="#" class="nav-link" data-page="monte-sua-pizza">Monte sua Pizza</a></li>
                    <li><a href="#" class="nav-link" data-page="carrinho">Carrinho</a></li>
                    <li><a href="pong.html">Esperando a Pizza?</a></li>
                </ul>
                <div class="container-usuario">
                    <input type="checkbox" id="button-user">

                    <div class="btn-usuario">
                        <label for="button-user" class="imagem-usuario">
                            <img src="assets/usuario.svg" alt="Login">
                        </label>
                    </div>

                    <div class="opt-usuario">
                        <div class="nome-usuario">
                            <?php if (isset($_SESSION['nome'])): ?>
                                <p>Olá, <?php "SELECT nome FROM clientes WHERE email = "; ?>!</p>
                            <?php endif; ?>
                        </div>
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
                    <canvas id="pizza-canvas" width="400" height="400"></canvas>
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
                            <!-- Os ingredientes serão adicionados via JavaScript -->
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

            <div class="menu-categories">       <!-- Adicionar novos produtos sem ser pizza -->
                <button class="category-btn active" data-category="todas">Todas</button>
                <button class="category-btn" data-category="tradicionais">Tradicionais</button>
                <button class="category-btn" data-category="especiais">Especiais</button>
                <button class="category-btn" data-category="doces">Doces</button>
                <button class="category-btn" data-category="vegetarianas">Vegetarianas</button>
            </div>

            <div class="menu-items" id="menu-items">
                <!-- Os itens do cardápio serão adicionados via JavaScript -->
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
                    <h3>Total: R$<span id="carrinho-total">0.00</span></h3>
                </div>

                <div class="carrinho-acoes">
                    <a href="#" class="nav-link" data-page="home"><button class="btn btn-secondary"
                            id="continuar-comprando">Continuar Comprando</button></a>
                    <button class="btn btn-primary" id="finalizar-pedido">Finalizar Pedido</button>
                </div>
            </div>
        </div>
        <div class="confirmacao-overlay" id="confirmacao-overlay-carrinho">
            <div class="confirmacao-container">
                <img src="assets/seloCavanha_Recomendado.png" alt="Pedido Confirmado" class="fade-in">
                <h2 class="fade-in">Pedido Confirmado!</h2>
                <p id="confirmacao-texto-carrinho" class="fade-in"></p>
                <button id="confirmacao-btn-carrinho" class="fade-in">Voltar ao Início</button>
            </div>
        </div>
    </section>
    <div class="confirmacao-overlay" id="confirmacao-overlay-observacao">
        <div class="obs-overlay">
            <div class="overlay-content">
                <h2>Alguma Observação?</h2>
                <form>
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
                    <img src="assets/logo-2.png" alt="Logo">
                    <h1 style="color: var(--primary-color);">Pizza do Cavanha</h1>
                </div>
                <p>© 2025 Pizza do Cavanha. Todos os direitos reservados.</p>
            </div>
            <hr style="height: 90px">
            <div class="contactenos-container">
                <h2>Fale Conosco:</h2>
                <div class="social-links-container">
                    <a href="https://github.com/DanielAraujo07" target="_blank" class="social-links">
                        <img alt="GitHub" class="social-links-icon" src="assets/github.png">
                    </a>
                    <a href="https://wa.me/3199782383" target="_blank" class="social-links">
                        <img alt="WhatsApp" class="social-links-icon" src="assets/Whatsapp.png">
                    </a>
                    <a href="mailto:daniel271207.produtividade@gmail.com" target="_blank" class="social-links">
                        <img alt="Gmail" class="social-links-icon" src="assets/Gmail.png">
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navegação entre páginas
        document.addEventListener('DOMContentLoaded', function () {
            // Mostrar página inicial por padrão
            showPage('home');

            // Configurar navegação
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    showPage(page);
                });
            });

            // Inicializar página "Cardápio"
            initCardapio();
            // Inicializar página "Carrinho"
            initCarrinho();
            // Inicializar página "Monte sua Pizza"
            initPizzaBuilder();

        });
        // Botão continuar comprando
        document.getElementById('continuar-comprando').addEventListener('click', function () {
            showPage('cardapio');
        });

        // Botão finalizar pedido
        document.getElementById('finalizar-pedido').addEventListener('click', function () {
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
            document.getElementById('confirmacao-btn-carrinho').addEventListener('click', function () {
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

        // Página "Cardápio"
        function initCardapio() {
            const cardapio = {
                todas: [
                    { id: 1, nome: 'Margherita', descricao: 'Molho de tomate, mussarela fresca e manjericão', preco: 35, imagem: 'https://images.unsplash.com/photo-1595854341625-f33ee10dbf94?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80' },
                    { id: 2, nome: 'Pepperoni', descricao: 'Molho de tomate, mussarela e pepperoni', preco: 40, imagem: 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 3, nome: 'Calabresa', descricao: 'Molho de tomate, mussarela e calabresa', preco: 38, imagem: 'https://images.unsplash.com/photo-1566843972705-1aad0ee32f88?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 4, nome: 'Frango com Catupiry', descricao: 'Molho de tomate, frango desfiado e catupiry', preco: 45, imagem: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1381&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 5, nome: 'Portuguesa', descricao: 'Molho de tomate, presunto, ovos, cebola, azeitonas e mussarela', preco: 42, imagem: 'https://www.ogastronomo.com.br/upload/389528334-curiosidades-sobre-a-pizza-portuguesa.jpg' },
                    { id: 6, nome: 'Chocolate com Morango', descricao: 'Chocolate ao leite e morangos frescos', preco: 48, imagem: 'https://images.unsplash.com/photo-1650173600578-778a606f7c2c?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 7, nome: 'Banana com Canela', descricao: 'Banana, canela e leite condensado', preco: 40, imagem: 'https://s2-receitas.glbimg.com/gwhIG-mUpHKhYSg8Zl21Zj_OXjA=/0x0:1280x800/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_1f540e0b94d8437dbbc39d567a1dee68/internal_photos/bs/2022/M/q/GHOy3ZT9GjPxMJH7FYmw/pizza-doce-banana-receita.jpg' },
                    { id: 8, nome: 'Vegetariana', descricao: 'Molho de tomate, mussarela e legumes frescos', preco: 38, imagem: 'https://images.unsplash.com/photo-1552539618-7eec9b4d1796?q=80&w=1402&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 9, nome: 'Rúcula com Tomate Seco', descricao: 'Mussarela de búfala, rúcula e tomate seco', preco: 45, imagem: 'https://images.unsplash.com/photo-1641840360785-c720744aa905?q=80&w=1374&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                ],
                tradicionais: [
                    { id: 1, nome: 'Margherita', descricao: 'Molho de tomate, mussarela fresca e manjericão', preco: 35, imagem: 'https://images.unsplash.com/photo-1595854341625-f33ee10dbf94?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80' },
                    { id: 2, nome: 'Pepperoni', descricao: 'Molho de tomate, mussarela e pepperoni', preco: 40, imagem: 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 3, nome: 'Calabresa', descricao: 'Molho de tomate, mussarela e calabresa', preco: 38, imagem: 'https://images.unsplash.com/photo-1566843972705-1aad0ee32f88?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                ],
                especiais: [
                    { id: 4, nome: 'Frango com Catupiry', descricao: 'Molho de tomate, frango desfiado e catupiry', preco: 45, imagem: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1381&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 5, nome: 'Portuguesa', descricao: 'Molho de tomate, presunto, ovos, cebola, azeitonas e mussarela', preco: 42, imagem: 'https://www.ogastronomo.com.br/upload/389528334-curiosidades-sobre-a-pizza-portuguesa.jpg' }
                ],
                doces: [
                    { id: 6, nome: 'Chocolate com Morango', descricao: 'Chocolate ao leite e morangos frescos', preco: 48, imagem: 'https://images.unsplash.com/photo-1650173600578-778a606f7c2c?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 7, nome: 'Banana com Canela', descricao: 'Banana, canela e leite condensado', preco: 40, imagem: 'https://s2-receitas.glbimg.com/gwhIG-mUpHKhYSg8Zl21Zj_OXjA=/0x0:1280x800/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_1f540e0b94d8437dbbc39d567a1dee68/internal_photos/bs/2022/M/q/GHOy3ZT9GjPxMJH7FYmw/pizza-doce-banana-receita.jpg' },
                ],
                vegetarianas: [
                    { id: 8, nome: 'Vegetariana', descricao: 'Molho de tomate, mussarela e legumes frescos', preco: 38, imagem: 'https://images.unsplash.com/photo-1552539618-7eec9b4d1796?q=80&w=1402&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                    { id: 9, nome: 'Rúcula com Tomate Seco', descricao: 'Mussarela de búfala, rúcula e tomate seco', preco: 45, imagem: 'https://images.unsplash.com/photo-1641840360785-c720744aa905?q=80&w=1374&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                ]
            };

            const menuItems = document.getElementById('menu-items');
            const categoryBtns = document.querySelectorAll('.category-btn');

            // Mostrar categoria inicial
            mostrarCategoria('todas');

            // Configurar botões de categoria
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    // Remover classe active de todos os botões
                    categoryBtns.forEach(b => b.classList.remove('active'));

                    // Adicionar classe active ao botão clicado
                    this.classList.add('active');

                    // Mostrar a categoria selecionada
                    const category = this.getAttribute('data-category');
                    mostrarCategoria(category);
                });
            });

            function mostrarCategoria(category) {
                menuItems.innerHTML = '';

                cardapio[category].forEach(item => {
                    const menuItem = document.createElement('div');
                    menuItem.className = 'menu-item';
                    menuItem.innerHTML = `
                        <img src="${item.imagem}" alt="${item.nome}">
                        <div class="menu-item-info">
                            <h3>${item.nome}</h3>
                            <p>${item.descricao}</p>
                            <div class="menu-item-price">
                                <span class="price">R$${item.preco.toFixed(2)}</span>
                                <button class="add-to-cart" data-id="${item.id}">Adicionar</button>
                            </div>
                        </div>
                    `;

                    // Adicionar evento ao botão "Adicionar"
                    const addButton = menuItem.querySelector('.add-to-cart');
                    addButton.addEventListener('click', () => {
                        mostrarOverlayObservacao(item);
                    });

                    menuItems.appendChild(menuItem);
                });
            }

            // Função para mostrar o overlay de observação
            function mostrarOverlayObservacao(item) {
                const overlay = document.getElementById('confirmacao-overlay-observacao');
                const textarea = overlay.querySelector('textarea');

                textarea.value = '';
                overlay.classList.add('ativo');

                // Configurar o formulário
                const form = overlay.querySelector('form');
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const observacao = textarea.value.trim();
                    adicionarAoCarrinho(item, observacao);
                    overlay.classList.remove('ativo');
                };
            }
            // Função adicionarAoCarrinho
            function adicionarAoCarrinho(item, observacao = '') {
                // Verificar se o item já está no carrinho com a mesma observação
                const itemExistente = carrinho.find(produto =>
                    produto.id === item.id &&
                    produto.observacao === observacao
                );

                if (itemExistente) {
                    itemExistente.quantidade += 1;
                } else {
                    // Criar uma cópia do item para não modificar o original
                    const itemCarrinho = {
                        ...item,
                        observacao: observacao, // Adiciona a observação como campo separado
                        quantidade: 1
                    };
                    carrinho.push(itemCarrinho);
                }

                atualizarCarrinho();
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
                atualizarContadorCarrinho();
            }
        }

        function initCarrinho() {
            const carrinhoSalvo = localStorage.getItem('carrinho');
            if (carrinhoSalvo) {
                carrinho = JSON.parse(carrinhoSalvo);
                atualizarCarrinho();
            }
        }

        function adicionarAoCarrinho(item) {
            // Verifica se o item já está no carrinho
            const itemExistente = carrinho.find(i => i.id === item.id);

            if (itemExistente) {
                // Se já existe, aumenta a quantidade
                itemExistente.quantidade += 1;
            } else {
                // Se não existe, adiciona ao carrinho
                carrinho.push({
                    id: item.id,
                    nome: item.nome,
                    preco: item.preco,
                    imagem: item.imagem,
                    quantidade: 1
                });
            }

            // Atualiza a exibição do carrinho
            atualizarCarrinho();

            // Salva no localStorage
            localStorage.setItem('carrinho', JSON.stringify(carrinho));

            // Feedback visual
            alert(`${item.nome} adicionado ao carrinho!`);
        }

        function atualizarCarrinho() {
            const carrinhoItens = document.getElementById('carrinho-itens');
            const carrinhoTotal = document.getElementById('carrinho-total');

            if (carrinho.length === 0) {
                carrinhoItens.innerHTML = '<div class="carrinho-vazio">Seu carrinho está vazio</div>';
                carrinhoTotal.textContent = '0.00';
                return;
            }

            // Limpa os itens atuais
            carrinhoItens.innerHTML = '';

            // Adiciona cada item do carrinho
            carrinho.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'carrinho-item';
                itemElement.innerHTML = `
                    <div class="item-info" style="width: 500px;">
                        <img src="${item.imagem}" alt="${item.nome}">
                        <div class="item-detalhes">
                            <h3>${item.nome}</h3>
                            <p class="descricao">${item.descricao}</p>
                            <p class="observacao">${item.observacao}</p>     
                            <p class="preco">R$ ${item.preco.toFixed(2)}</p>
                        </div>
                    </div>
                    <div class="item-acoes">
                        <div class="quantidade-controle">
                            <button class="diminuir" data-index="${index}">-</button>
                            <span>${item.quantidade}</span>
                            <button class="aumentar" data-index="${index}">+</button>
                        </div>
                        <button class="remover-item" data-index="${index}">×</button>
                    </div>
                    <div class="item-preco">
                        R$ ${(item.preco * item.quantidade).toFixed(2)}
                    </div>
                `;

                carrinhoItens.appendChild(itemElement);
            });

            // Calcula o total
            const total = carrinho.reduce((sum, item) => sum + (item.preco * item.quantidade), 0);
            carrinhoTotal.textContent = total.toFixed(2);

            // Adiciona eventos aos botões de quantidade
            document.querySelectorAll('.diminuir').forEach(btn => {
                btn.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    alterarQuantidade(index, -1);
                });
            });

            document.querySelectorAll('.aumentar').forEach(btn => {
                btn.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    alterarQuantidade(index, 1);
                });
            });

            // Adiciona eventos aos botões de remover
            document.querySelectorAll('.remover-item').forEach(btn => {
                btn.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    removerItem(index);
                });
            });

            // Atualiza o contador no menu
            atualizarContadorCarrinho();
        }

        function alterarQuantidade(index, mudanca) {
            const novoValor = carrinho[index].quantidade + mudanca;

            if (novoValor < 1) {
                // Remove o item se a quantidade for menor que 1
                carrinho.splice(index, 1);
                atualizarContadorCarrinho();
            } else {
                // Atualiza a quantidade
                carrinho[index].quantidade = novoValor;
            }

            // Atualiza o carrinho
            atualizarCarrinho();
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
        }

        function removerItem(index) {
            carrinho.splice(index, 1);
            atualizarCarrinho();
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarContadorCarrinho();
        }

        function atualizarContadorCarrinho() {
            const totalItens = carrinho.reduce((sum, item) => sum + item.quantidade, 0);
            const carrinhoLink = document.querySelector('[data-page="carrinho"]');
            carrinhoLink.innerHTML = totalItens > 0 ? `Carrinho (${totalItens})` : 'Carrinho';

            if (totalItens > 0) {
                carrinhoLink.innerHTML = `Carrinho (${totalItens})`;
            } else {
                carrinhoLink.innerHTML = 'Carrinho';
            }
        }

        // Página "Monte sua Pizza"
        function initPizzaBuilder() {
            const ingredientes = [
                { id: 1, nome: 'Queijo', imagem: './assets/queijo.png', preco: 2 },
                { id: 2, nome: 'Pepperoni', imagem: './assets/pepperoni.png', preco: 3 },
                { id: 3, nome: 'Cogumelos', imagem: './assets/cogumelo.png', preco: 2.5 },
                { id: 4, nome: 'Cebola', imagem: './assets/cebola.png', preco: 1.5 },
                { id: 5, nome: 'Pimentão', imagem: './assets/pimentao.png', preco: 2 },
                { id: 6, nome: 'Azeitonas', imagem: './assets/azeitona.png', preco: 2.5 },
                { id: 7, nome: 'Bacon', imagem: './assets/bacon.png', preco: 3.5 },
                { id: 8, nome: 'Tomate', imagem: './assets/tomate.png', preco: 2 },
                { id: 9, nome: 'Manjericão', imagem: './assets/manjericao.png', preco: 1.5 },
                { id: 10, nome: 'Alho', imagem: './assets/alho.png', preco: 1 }
            ];

            const ingredientesGrid = document.getElementById('ingredientes-grid');
            ingredientes.forEach(ing => {
                const btn = document.createElement('button');
                btn.className = 'ingrediente-btn';
                btn.innerHTML = `
                    <img src="${ing.imagem}" alt="${ing.nome}">
                    <span>${ing.nome}</span>
                `;
                btn.addEventListener('click', () => adicionarIngrediente(ing));
                ingredientesGrid.appendChild(btn);
            });

            // Canvas da pizza
            const canvas = document.getElementById('pizza-canvas');
            const ctx = canvas.getContext('2d');

            // Imagem da pizza base
            const pizzaBaseImg = new Image();
            pizzaBaseImg.src = './assets/pizzaBase.png';

            // Array para armazenar os ingredientes desenhados
            let ingredientesDesenhados = [];

            pizzaBaseImg.onload = function () {
                redesenharPizza();
            };

            function redesenharPizza() {
                // Limpa o canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Desenha a pizza base (centralizada)
                const baseLargura = pizzaBaseImg.width * 0.8;
                const baseAltura = pizzaBaseImg.height * 0.8;
                const xBase = (canvas.width - baseLargura) / 2;
                const yBase = (canvas.height - baseAltura) / 2;

                ctx.drawImage(pizzaBaseImg, xBase, yBase, baseLargura, baseAltura);

                // Redesenha os ingredientes
                ingredientesDesenhados.forEach(ing => {
                    const img = new Image();
                    img.src = ing.imagem;
                    img.onload = function () {
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
                    const tamanhoIngrediente = 410;
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
                const tamanho = document.querySelector('input[name="tamanho"]:checked').value;
                const precoBase = { pequena: 20, media: 30, grande: 40 }[tamanho];
                const precoIngredientes = ingredientesSelecionados.reduce((total, ing) => total + ing.preco, 0);
                const total = precoBase + precoIngredientes;

                totalPedido.textContent = total.toFixed(2);
            }

            function limparPizza() {
                ingredientesSelecionados.length = 0;
                ingredientesDesenhados.length = 0;
                document.querySelector('input[name="tamanho"][value="media"]').checked = true;
                atualizarListaIngredientes();
                redesenharPizza();
                calcularTotal();
            }

            btnFinalizar.addEventListener('click', function () {
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
                form.onsubmit = function (e) {
                    e.preventDefault();
                    const observacao = textarea.value.trim();

                    // Adicionar a pizza ao carrinho com a observação
                    adicionarPizzaAoCarrinho(observacao);

                    // Esconder o overlay
                    overlay.classList.remove('ativo');
                };

                // Fechar ao clicar fora (opcional)
                overlay.addEventListener('click', function (e) {
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
                }[tamanho];

                // Criar descrição dos ingredientes
                const ingredientesDesc = ingredientesSelecionados.map(ing => ing.nome).join(', ');

                // Criar item para o carrinho
                const pizzaPersonalizada = {
                    id: Date.now(), // Usa timestamp como ID único
                    nome: tamanhoTexto,
                    descricao: `${ingredientesDesc}`,
                    preco: parseFloat(totalPedido.textContent),
                    imagem: './assets/logo-square.svg',
                    observacao: observacao,
                    quantidade: 1
                };

                // Adicionar ao carrinho
                carrinho.push(pizzaPersonalizada);
                atualizarCarrinho();
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
                atualizarContadorCarrinho();

                // Limpar a pizza para o próximo pedido
                limparPizza();

                // Feedback para o usuário
                //alert('Sua pizza personalizada foi adicionada ao carrinho!');
                showPage('carrinho');
            }

            window.removerIngrediente = removerIngrediente;
        }

    </script>
</body>

</html>