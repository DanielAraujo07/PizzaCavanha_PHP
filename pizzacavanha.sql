CREATE DATABASE pizzacavanha;
USE pizzacavanha;

CREATE TABLE clientes (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (45) NOT NULL,
    senha           VARCHAR (255) NOT NULL,
    email           VARCHAR (64) NOT NULL UNIQUE,
    telefone        VARCHAR (20) NOT NULL
);

CREATE TABLE admins (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (45) NOT NULL,
    senha           VARCHAR (255) NOT NULL,
    email           VARCHAR (64) NOT NULL UNIQUE
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

CREATE TABLE produtos (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_categoria 	INT,
    nome 			VARCHAR(128) NOT NULL,
    descricao 		TEXT NOT NULL,
    preco 			DECIMAL(10, 2) NOT NULL,
    imagem 			VARCHAR(255),
    disponivel 		BOOLEAN DEFAULT TRUE,
   
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

CREATE TABLE ingredientes (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_tipo         INT NOT NULL,
	nome 			VARCHAR(64) NOT NULL,
    quantidade		INT NOT NULL DEFAULT 1,
    preco 			DECIMAL(5, 2) NOT NULL,
    imagem 			VARCHAR(255),
    disponivel 		BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (id_tipo) REFERENCES tipos_categoria (id)
);

CREATE TABLE itens (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (128) NOT NULL,
    quantidade      INT NOT NULL,
    valor           DECIMAL (5, 2) NOT NULL,
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

    FOREIGN KEY (id_cliente) REFERENCES clientes (id),
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
    nome 			VARCHAR(20) NOT NULL,
    preco_base	 	DECIMAL(5, 2) NOT NULL
);

/*
CREATE TABLE carrinho (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_cliente 		INT NOT NULL,
    id_car_itens 	INT NOT NULL,
    valor 			DECIMAL(10, 2) NOT NULL,
    data_adicao 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    FOREIGN KEY (id_car_itens) REFERENCES carrinho_itens(id)
);

CREATE TABLE carrinho_itens (
    id 				INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_carrinho 	INT NOT NULL,
    id_item 		INT NOT NULL,
   
    FOREIGN KEY (id_carrinho) REFERENCES carrinho(id),
    FOREIGN KEY (id_item) REFERENCES item(id)
); 
*/

SELECT * FROM clientes;
SELECT * FROM admins;
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

-- INSERT INTOs

    INSERT INTO clientes (nome, senha, email, telefone) VALUES
		('User001', SHA2('senha', 512), 'user@email.com', '(11) 11111-1111'),
		('User002', SHA2('senha2', 512), 'user2@email.com', '(11) 11111-1111'),
		('User003', SHA2('senha3', 512), 'user3@email.com', '(11) 11111-1111');
    
    INSERT INTO admins (nome, senha, email) VALUES
		('Admin', SHA2('admin', 512), 'admin@email.com');
        
	INSERT INTO tipos_categoria (nome) VALUES
		('Salgado'),
        ('Doce'),
        ('Bebida');
        
    INSERT INTO categorias (tipo_id, nome) VALUES
		(1, 'salgadas'),
		(2, 'doces'),
		(1, 'vegetarianas'),
		(3, 'bebidas'),
		(2, 'sobremesas');
        
    INSERT INTO produtos (nome, descricao, preco, imagem, id_categoria) VALUES
		('Pizza Personalizada', 'Monte a sua própria pizza, do zero. Uma pizza com a sua cara!', '20.00', 'https://images.pexels.com/photos/1093015/pexels-photo-1093015.jpeg', 1),
		('Margherita', 'Molho de Tomate, Mussarela e Manjericão', 35.00, 'https://images.unsplash.com/photo-1595854341625-f33ee10dbf94?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', 1),
		('Pepperoni', 'Molho de Tomate, Mussarela e Pepperoni', 40.00, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1),
		('Calabresa', 'Molho de Tomate, Mussarela e Calabresa', 38.00, 'https://images.unsplash.com/photo-1566843972705-1aad0ee32f88?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1),
		('Frango com Catupiry', 'Molho de Tomate, Frango Desfiado e Catupiry', 45.00, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1381&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1),
		('Portuguesa', 'Molho de Tomate, Presunto, Ovos, Cebola, Azeitonas e Mussarela', 42.00, 'https://www.ogastronomo.com.br/upload/389528334-curiosidades-sobre-a-pizza-portuguesa.jpg', 1),
		('Chocolate com Morango', 'Chocolate ao Leite e Morangos Frescos', 48.00, 'https://images.unsplash.com/photo-1650173600578-778a606f7c2c?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 2),
		('Banana com Canela', 'Banana, Canela e Leite Condensado', 40.00, 'https://s2-receitas.glbimg.com/gwhIG-mUpHKhYSg8Zl21Zj_OXjA=/0x0:1280x800/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_1f540e0b94d8437dbbc39d567a1dee68/internal_photos/bs/2022/M/q/GHOy3ZT9GjPxMJH7FYmw/pizza-doce-banana-receita.jpg', 2),
		('Vegetariana', 'Molho de Tomate, Mussarela e Legumes Frescos', 38.00, 'https://images.unsplash.com/photo-1552539618-7eec9b4d1796?q=80&w=1402&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 3),
		('Rúcula com Tomate', 'Mussarela de Búfala, Rúcula e Tomate Seco', 45.00, 'https://images.unsplash.com/photo-1641840360785-c720744aa905?q=80&w=1374&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 3);
   
    INSERT INTO tamanhos (nome, preco_base) VALUES
		('pequena', 20.00),
		('media', 30.00),
		('grande', 40.00);
   
    INSERT INTO ingredientes (nome, preco, id_tipo, imagem) VALUES
		('Queijo', 2.00, 1, './assets/ingredientes/queijo.png'),
		('Pepperoni', 3.00, 1, './assets/ingredientes/pepperoni.png'),
		('Cogumelos', 2.50, 1, './assets/ingredientes/cogumelo.png'),
		('Cebola', 1.50, 1, './assets/ingredientes/cebola.png'),
		('Pimentão', 2.00, 1, './assets/ingredientes/pimentao.png'),
		('Azeitonas', 2.50, 1, './assets/ingredientes/azeitona.png'),
		('Bacon', 3.50, 1, './assets/ingredientes/bacon.png'),
		('Tomate', 2.00, 1, './assets/ingredientes/tomate.png'),
		('Manjericão', 1.50, 1, './assets/ingredientes/manjericao.png'),
		('Alho', 1.00, 1, './assets/ingredientes/alho.png');
	
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
        