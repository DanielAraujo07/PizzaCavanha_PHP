CREATE DATABASE pizzacavanha;
USE pizzacavanha;

CREATE TABLE clientes (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR (45) NOT NULL,
    senha           VARCHAR (255) NOT NULL,
    email           VARCHAR (45) NOT NULL UNIQUE,
    telefone        VARCHAR (20) NOT NULL
);

CREATE TABLE pedido (
    id              INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_cliente      INT,
    local_entrega   VARCHAR (45), 
    estado          ENUM('Em processamento', 'Enviado', 'Entregue', 'Cancelado') DEFAULT 'Em processamento',
    valor           DECIMAL (10, 2) NOT NULL,
    itens           TEXT NOT NULL,
    pagamento       ENUM('Cartão de Crédito', 'Pix', 'Dinheiro', 'Débito') NOT NULL,
    data_pedido     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_cliente) REFERENCES clientes (id)
);

INSERT INTO clientes (nome, senha, email, telefone) VALUES 
('Daniel Araújo', SHA2('dn0gmr080', 512), 'daniel271207.ac@gmail.com', '(31) 99978-2383');

CREATE TABLE avaliacao (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR (20) NOT NULL,
nota ENUM('1', '2', '3', '4', '5') NOT NULL,
comentario VARCHAR (255)
);

SELECT * FROM clientes;