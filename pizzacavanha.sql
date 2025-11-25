CREATE DATABASE pizzacavanha;
USE pizzacavanha;

CREATE TABLE user_classes (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (45) NOT NULL,
    nivel           INT NOT NULL
);
CREATE TABLE users (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (45) NOT NULL,
    senha           VARCHAR (255) NOT NULL,
    email           VARCHAR (64) NOT NULL UNIQUE,
    telefone        VARCHAR (20) NOT NULL,
    class_id        INT NOT NULL DEFAULT 1,

    FOREIGN KEY (class_id) REFERENCES user_classes(id)
);
CREATE TABLE tipos_categoria (
	id				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome			VARCHAR(45)
);
CREATE TABLE categorias (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	tipo_id			INT NOT NULL,
    nome 			VARCHAR(45) NOT NULL,
    
    FOREIGN KEY (tipo_id) REFERENCES tipos_categoria(id)
);
CREATE TABLE tipos_produtos (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(45) NOT NULL
);
CREATE TABLE produtos (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_categoria 	INT,
    tipo_id			INT,
    nome 			VARCHAR(128) NOT NULL,
    descricao 		TEXT NOT NULL,
    preco 			DECIMAL(10, 2) NOT NULL,
    imagem 			VARCHAR(255),
    disponivel 		BOOLEAN DEFAULT TRUE,
   
    FOREIGN KEY (id_categoria) REFERENCES categorias(id),
    FOREIGN KEY (tipo_id) REFERENCES tipos_produtos(id)
);
CREATE TABLE ingredientes (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo_id         INT NOT NULL,
	nome 			VARCHAR(64) NOT NULL,
    quantidade		INT NOT NULL DEFAULT 1,
    preco 			DECIMAL(5, 2) NOT NULL,
    imagem 			VARCHAR(255),
    disponivel 		BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (tipo_id) REFERENCES tipos_categoria (id)
);
CREATE TABLE itens (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (128) NOT NULL,
    quantidade      INT NOT NULL,
    valor           DECIMAL (6, 2) NOT NULL,
    descricao       VARCHAR (255) NOT NULL,
    observacao      VARCHAR (255)
);
CREATE TABLE ingredientes_itens (
	id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_item 		INT NOT NULL,
    id_ingrediente 	INT NOT NULL,
    
    FOREIGN KEY (id_item) REFERENCES itens(id),
    FOREIGN KEY (id_ingrediente) REFERENCES ingredientes(id)
);
CREATE TABLE estados (
	id				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome			VARCHAR (45) NOT NULL
);
CREATE TABLE formapag (
	id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome 			VARCHAR (45) NOT NULL
);
CREATE TABLE tipo_entrega (
	id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo			VARCHAR (45) NOT NULL
);
CREATE TABLE entrega (
	id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_tipo			INT NOT NULL,
    endereco		VARCHAR (128) NOT NULL,
    
    FOREIGN KEY (id_tipo) REFERENCES tipo_entrega (id)
);
CREATE TABLE pedido (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_cliente      INT NOT NULL,
    id_estado       INT DEFAULT 1 NOT NULL, -- ('Em processamento', 'Preparando', 'Enviado', 'Entregue', 'Cancelado') DEFAULT 'Em processamento'
    id_entrega	   	INT NOT NULL, -- Endereço + ('Delivery', 'Retirada')
    id_formapag		INT NOT NULL, -- ('Pix',  'Débito', 'Crédito', 'Dinheiro')
	valor           DECIMAL (10, 2) NOT NULL,
    horario         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_cliente) REFERENCES users (id),
    FOREIGN KEY (id_estado) REFERENCES estados (id),
    FOREIGN KEY (id_entrega) REFERENCES entrega (id),
    FOREIGN KEY (id_formapag) REFERENCES formapag (id)
);
CREATE TABLE pedido_itens (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pedido       INT NOT NULL,
    id_item         INT NOT NULL,
   
    FOREIGN KEY (id_pedido) REFERENCES pedido(id),
    FOREIGN KEY (id_item) REFERENCES itens(id)
);
CREATE TABLE tamanhos (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo_id			INT NOT NULL,
    nome 			VARCHAR(45) NOT NULL,
    preco_base	 	DECIMAL(5, 2) NOT NULL,

    FOREIGN KEY (tipo_id) REFERENCES tipos_produtos(id)
);

CREATE TABLE logs_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tabela_afetada VARCHAR(50) NOT NULL,
    id_registro INT NOT NULL,
    acao ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    dados_anteriores TEXT,
    dados_novos TEXT,
    id_usuario INT NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_usuario VARCHAR(45),
    user_agent VARCHAR(255),
    
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);

SELECT * FROM ingredientes;

-- INSERT INTOs
    INSERT INTO user_classes (nome, nivel) VALUES
        ('Cliente', 1),
        ('Atendente', 2),
        ('Entregador', 3),
        ('Cozinheiro', 4),
        ('Financeiro', 5),
        ('Admin', 6);
    INSERT INTO users (nome, senha, email, telefone, class_id) VALUES
        ('Daniel Araújo', SHA2('senha', 512), 'daniel@email.com', '(00) 00000-0000', 6);
	INSERT INTO tipos_categoria (nome) VALUES
		('Comida Salgada'),
        ('Comida Doce'),
        ('Bebidas Padrão'),
        ('Bebidas Especiais');
    INSERT INTO categorias (tipo_id, nome) VALUES
		(1, 'Pizzas Salgadas'),
		(2, 'Pizzas Doces'),
		(1, 'Pizzas Vegetarianas'),
		(3, 'Bebidas'),
        (4, 'Bebidas Especiais'),
		(2, 'Sobremesas');
    INSERT INTO tipos_produtos (nome) VALUES
		('Pizzas'),
		('Bebidas'),
		('Sobremesas'),
        ('Vinhos');
    INSERT INTO produtos (nome, descricao, preco, imagem, id_categoria, tipo_id) VALUES
        -- Pizzas Personalizadas
		('Pizza Personalizada Salgada', 'Monte a sua própria pizza, do zero. Uma pizza com a sua cara!', '20.00', 'https://images.pexels.com/photos/1093015/pexels-photo-1093015.jpeg', 1, 1),
        ('Pizza Personalizada Doce', 'Monte a sua pizza doce, do zero. Sempre sobra espaço pra um docinho né?', '30.00', 'https://cdn.pixabay.com/photo/2020/01/04/18/10/pizza-4741311_960_720.jpg', 2, 2),
        -- Pizzas Padrão
		('Margherita', 'Molho de Tomate, Mussarela, Tomate e Manjericão', 35.00, 'https://grandecheese.com/wp-content/uploads/2025/02/Margherita-Pizza-deck-oven.jpg.webp', 1, 1),
		('Pepperoni', 'Molho de Tomate, Mussarela e Pepperoni', 40.00, 'https://www.seara.com.br/wp-content/uploads/2025/09/pizza-de-pepperoni-caseira-portal-minha-receita.jpg', 1, 1),
		('Calabresa', 'Molho de Tomate, Mussarela, Calabresa, Cebola e Azeitonas', 38.00, 'https://www.sabornamesa.com.br/media/k2/items/cache/513d7a0ab11e38f7bd117d760146fed3_XL.jpg', 1, 1),
		('Frango com Catupiry', 'Molho de Tomate, Mussarela, Frango Desfiado e Catupiry', 45.00, 'https://guiadacozinha.com.br/wp-content/uploads/2007/01/pizza-de-frango-e-milho.jpg', 1, 1),
		('Portuguesa', 'Molho de Tomate, Presunto, Ovos, Pepperoni, Ervilhas, Cebola, Azeitonas e Mussarela', 42.00, 'https://www.ogastronomo.com.br/upload/389528334-curiosidades-sobre-a-pizza-portuguesa.jpg', 1, 1),
        -- Pizzas Doces
		('Chocolate com Morango', 'Chocolate ao Leite e Morangos Frescos', 48.00, 'https://s2.glbimg.com/qyb1vGS-RoeaveKby5OXxsAkns4=/620x455/e.glbimg.com/og/ed/f/original/2021/04/15/receita-pizza-doce-chocolate-morango.jpg', 2, 1),
		('Banana com Canela', 'Banana, Canela e Leite Condensado', 40.00, 'https://lupertine.com.br/wp-content/uploads/2022/07/BANANA-1.jpg', 2, 1),
        -- Pizzas Vegetarianas
		('Vegetariana', 'Molho de Tomate, Mussarela, Cogumelos e Legumes Frescos', 38.00, 'https://www.maestrella.com/wp-content/uploads/2021/09/AUTUMN-VEGGIE-PIZZA-min.jpg', 3, 1),
		('Rúcula com Tomate', 'Mussarela de Búfala, Rúcula e Tomate Seco', 45.00, 'https://images.unsplash.com/photo-1641840360785-c720744aa905?q=80&w=1374&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 3, 1),
        -- Águas
        ('Água Mineral', 'Água mineral natural sem gás', 1.50, 'https://www.eatright.org/-/media/images/eatright-articles/eatright-article-1200x675/what-makes-a-healthful-drink-of-water_1200x675.jpg?as=0&w=967&rev=2c24c6a7b16e471081d6de8b21657fb7&hash=80C212345F4602E3682D1CA6BF467544', 4, 2),
        ('Água com Gás', 'Água mineral com gás', 2.50, 'https://www.institucional.europa.com.br/blog/wp-content/uploads/2020/07/IMG_1822.jpg', 4, 2),
        ('Água Tônica', 'Água Tônica Schweppes', 4.00, 'https://www.coca-cola.com/content/dam/onexp/za/en/schweppes-last-version/Schweppes_Desktop_1440x810.jpg', 4, 2),
        -- Refrigerantes
        ('Coca Cola', 'Coca Cola / Coca Cola Zero', 4.00, 'https://blogdapublicidade.com/wp-content/uploads/2024/04/historia-logotipo-coca-cola.jpg', 4, 2),
        ('Guaraná Antártica', 'Guaraná Antártica / Guaraná Antártica Zero', 4.00, 'https://www.ycar.com.br/site20/wp-content/uploads/2023/12/embalagem-de-guarana-antarctica-zero-tem-novo-visual.png', 4, 2),
        ('Pepsi', 'Pepsi / Pepsi Zero', 4.00, 'https://admin.cnnbrasil.com.br/wp-content/uploads/sites/12/2023/03/230327115255-embargoed-01-pepsi-new-logo-2023.webp?w=1200&h=630&crop=1', 4, 2),
        ('Sprite', 'Sprite / Sprite Zero', 4.00, 'https://classic.exame.com/wp-content/uploads/2018/09/banners_sprite-lemon-freshdesktop2-1-3.png', 4, 2),
        ('Fanta', 'Fanta. Escolha os sabores através da observação', 4.00, 'https://i.pinimg.com/736x/8c/f0/ca/8cf0ca7179203e96948444f4bdb2f527.jpg', 4, 2),
        ('H2OH!', 'H2OH! Escolha os sabores na observação', 4.00, 'https://i.pinimg.com/736x/4a/fe/ab/4afeabbf9e4896bcc7496cd3ef1b321e.jpg', 4, 2),
        ('Monster', 'Monster. Escolha os sabores através da observação. Mais uma coisa: beba com moderação!', 5.00, 'https://www.foodnavigator-usa.com/resizer/7MEGrGf-0N4DU0hXA9OagQQUBig=/arc-photo-williamreed/eu-central-1-prod/public/3MXWQNYEIZK33HBLVCVW3BZX2Y.jpg', 4, 2),
        ('Red Bull', 'Red Bull. Sabores na da observação. Mais uma coisa: beba com moderação. Ouvi dizer que isso te dá asas!', 10.00, 'https://t3.ftcdn.net/jpg/04/31/62/14/360_F_431621440_FFG7fwFMxdVlADPCaOPKOkD94nQkL1nQ.jpg', 4, 2),
        -- Sucos Caseiros
        ('Suco de Maracujá', 'Suco natural de maracujá', 9.00, 'https://marmitexdesucesso.com.br/wp-content/uploads/2025/05/Como-fazer-suco-de-maracuja-sem-liquidificador.jpg', 4, 2),
        ('Suco de Morango', 'Suco natural de morangos selecionados', 10.50, 'https://cozinhandocomsaude.com.br/wp-content/uploads/2025/03/Suco-refrescante-de-morango.jpg', 4, 2),
        ('Suco de Laranja', 'Suco natural de laranja fresca', 7.50, 'https://images.unsplash.com/photo-1613478223719-2ab802602423?q=80&w=500', 4, 2),
        ('Suco de Abacaxi com Hortelã', 'Suco da polpa do abacaxi com hortelã', 9.50, 'https://storage.googleapis.com/imagens_videos_gou_cooking_prod/production/cooking/vV9afa3LlGb1PkUTMGR0gXMfKJQQG3z86qAIMTTY.jpeg', 4, 2),
        ('Suco de Uva', 'Suco natural de uva orgânico', 8.50, 'https://thekitchenmccabe.com/wp-content/uploads/2018/10/Grape-Juice-8-1-of-1.jpg', 4, 2),
        ('Limonada', 'Limonada italiana tradicional com hortelã', 8.00, 'https://www.comidaereceitas.com.br/wp-content/uploads/2007/09/limonada-de-menta.jpg', 4, 2),
        ('Pink Limonade', 'Limonada rosa com toque de framboesa', 10.00, 'https://guiadacozinha.com.br/wp-content/uploads/2019/11/pink-lemonade-receita.jpg', 4, 2),
		('Chá Gelado', 'Chá gelado com limão e hortelã', 6.50, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=500', 4, 2),
        -- Bebidas Especiais
        ('Casa Valduga Origem Tinto', 'Vinho seco brasileiro. Chique. Combina demais com o nosso restaurante', 110.90, 'https://18666.cdn.simplo7.net/static/18666/sku/tintos-seco-vinho-casa-valduga-origem-cabernet-sauvignon-tinto-750ml--p-1557779248419.jpg', 5, 4),
        ('Primitivo Italia', 'Vinho tinto italiano. Um clássico.', 140.90, 'https://www.portoaporto.com.br/blog/wp-content/uploads/2024/08/primitivo-vinho-1400px.jpg', 5, 4),
        ('Lídio Carraro Faces', 'Vinho tinto brasileiro.', 74.90, 'https://www.lidiocarraro.com/storage/images/product-lines/decoration/3/md-faces-do-brasil-2.jpg', 5, 4),
        ('Naturelle Frisante Branco', 'Vinho branco brasileiro. Suave. Perfeito para dia', 86.90, 'https://http2.mlstatic.com/D_NQ_NP_802065-MLB47835603197_102021-O.webp', 5, 4),
        -- Sobremesas
        ('Panna Cotta', 'Sobremesa italiana de creme de baunilha com calda de frutas vermelhas', 16.00, 'https://images.pexels.com/photos/15359109/pexels-photo-15359109.jpeg', 5, 3),
        ('Banoffee', 'Torta de banana, doce de leite e chantilly, criação inglesa com toque italiano', 18.50, 'https://www.foodandwine.com/thmb/yboQhtuPBJ9aw0TmrZwEOUHguqc=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/banofee-pie-FT-RECIPE0325-d3b7ade5ed654806904775dcfe0f99cb.jpg', 5, 3),
        ('Petit Gateau', 'Bolinho de chocolate fondant com sorvete de baunilha', 22.00, 'https://bakeandcakegourmet.com.br/uploads/site/receitas/petit-gateau-1-1pctvk69.jpg', 5, 3),
        ('Tiramisu', 'Clássico italiano com café, mascarpone e cacau', 19.00, 'https://staticcookist.akamaized.net/wp-content/uploads/sites/22/2024/09/THUMB-VIDEO-2_rev1-56.jpeg', 5, 3),
        ('Brownie com Nutella', 'Brownie de chocolate belga com Nutella e sorvete de Pistache', 20.50, 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?q=80&w=500', 5, 3),
        ('Cannoli Siciliani', 'Tubinhos crocantes recheados com ricota doce e frutas cristalizadas', 17.50, 'https://www.homecookingadventure.com/wp-content/uploads/2022/01/cannoli_siciliani_main.jpg', 5, 3),
        ('Semifreddo al Torrone', 'Clássico italiano com amêndoas e mel, textura suave e cremosa', 21.00, 'https://www.cucchiaio.it/content/cucchiaio/it/ricette/2018/01/semifreddo-al-torroncino/jcr:content/imagePreview.img10.jpg/1542814801073.jpg', 5, 3);
    INSERT INTO tamanhos (tipo_id, nome, preco_base) VALUES
		(1, 'Pequena', 20.00),
		(1, 'Média', 30.00),
		(1, 'Grande', 40.00),
        (2, '350ml', 0.00),
        (2, '500ml', 5.00),
        (2, 'Garrafa / Jarra', 15.00),
        (3, 'Média', 0.00),
        (3, 'Grande', 6.50),
        (4, 'Garrafa', 0.00);
    INSERT INTO ingredientes (nome, preco, tipo_id, imagem) VALUES
		-- Salgados
		('Queijo', 2.00, 1, './assets/ingredientes/queijo.png'),
		('Pepperoni', 3.00, 1, './assets/ingredientes/pepperoni.png'),
		('Cogumelos', 2.50, 1, './assets/ingredientes/cogumelo.png'),
		('Cebola', 1.50, 1, './assets/ingredientes/cebola.png'),
		('Pimentão', 2.00, 1, './assets/ingredientes/pimentao.png'),
		('Azeitonas', 2.50, 1, './assets/ingredientes/azeitona.png'),
		('Bacon', 3.50, 1, './assets/ingredientes/bacon.png'),
		('Tomate', 2.00, 1, './assets/ingredientes/tomate.png'),
		('Manjericão', 1.50, 1, './assets/ingredientes/manjericao.png'),
        ('Ovos', 2.00, 1, './assets/ingredientes/ovos.png'),
		('Frango', 4.00, 1, './assets/ingredientes/frango.png'),
		('Alho', 1.00, 1, './assets/ingredientes/alho.png'),
        -- Doces
        ('Morangos', 4.50, 2, './assets/ingredientes/morango.png'),
		('Banana Caramelizada', 3.80, 2, './assets/ingredientes/banana.png'),
		('Framboesa', 5.20, 2, './assets/ingredientes/framboesa.png'),
		('Mirtilos', 4.80, 2, './assets/ingredientes/mirtilo.png'),
		('Gotas de Chocolate', 3.80, 2, './assets/ingredientes/gotas-chocolate.png'),
		('Nutella', 5.50, 2, './assets/ingredientes/nutella.png'),
		('Nozes', 5.00, 2, './assets/ingredientes/nozes.png'),
		('Pistache', 5.50, 2, './assets/ingredientes/pistache.png'),
		('Avelãs', 5.00, 2, './assets/ingredientes/avelas.png'),
		('Canela em Pó', 2.50, 2, './assets/ingredientes/canela.png'),
		('Baunilha', 3.00, 2, './assets/ingredientes/baunilha.png'),
		('Coco Ralado', 3.20, 2, './assets/ingredientes/coco.png'),
		('Hortelã', 2.80, 2, './assets/ingredientes/hortela.png'),
		('Lascas de Limão', 3.00, 2, './assets/ingredientes/limao.png'),
        -- Bebidas
        ('Canudo de Papel', 0.00, 3, './assets/ingredientes/placeholder.svg'),
        ('Gelo e Limão', 0.00, 3,'./assets/ingredientes/placeholder.svg'),
        -- Bebidas Especiais
        ('Taça Cristal', 0.00, 4, './assets/ingredientes/placeholder.svg');
    INSERT INTO estados (nome) VALUES
		('Em Processamento'),
        ('Preparando'),
        ('Enviado'),
        ('Entregue'),
        ('Cancelado');
	INSERT INTO formapag (nome) VALUES
		('Pix'),
        ('Débito'),
        ('Crédito'),
        ('Dinheiro');
	INSERT INTO tipo_entrega (tipo) VALUES 
		('Delivery'),
        ('Retirada');
        
-- SELECT FROMs
/*
    SELECT * FROM user_classes;
	SELECT * FROM users;
	SELECT * FROM categorias;
	SELECT * FROM produtos;
	SELECT * FROM itens;
	SELECT * FROM ingredientes;
	SELECT * FROM ingredientes_itens;
	SELECT * FROM pedido;
	SELECT * FROM pedido_itens;
	SELECT * FROM tamanhos;
	SELECT * FROM tipos_categoria;
	SELECT * FROM estados;
	SELECT * FROM formapag;
	SELECT * FROM tipo_entrega;
	SELECT * FROM entrega;
*/

-- DELETE FROMs
/*
    DELETE FROM classes;
	DELETE FROM users;
	DELETE FROM tipos_categoria;
	DELETE FROM categorias;
	DELETE FROM produtos;
	DELETE FROM ingredientes;
	DELETE FROM itens;
	DELETE FROM ingredientes_itens;
	DELETE FROM estados;
	DELETE FROM formapag;
	DELETE FROM tipo_entrega;
	DELETE FROM entrega;
	DELETE FROM pedido;
	DELETE FROM pedido_itens;
	DELETE FROM tamanhos;   
*/