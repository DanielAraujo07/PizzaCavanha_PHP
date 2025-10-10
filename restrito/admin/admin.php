<?php
//include "verifica_admin.php";
include "../conexao.php";

// Processar formulários de administração aqui
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['adicionar_produto'])) {
        // Processar adição de produto
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
        $preco = floatval($_POST['preco']);
        $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
        $categoria = intval($_POST['categoria']);
        
        $sql = "INSERT INTO produtos (nome, descricao, preco, imagem, id_categoria) 
                VALUES ('$nome', '$descricao', $preco, '$imagem', $categoria)";
        mysqli_query($conn, $sql);
    }
    
    // Adicionar processamento para outros formulários (ingredientes, etc.)
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Administração - Pizza do Cavanha</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Painel de Administração</h1>
        
        <div class="admin-sections">
            <!-- Gerenciar Produtos -->
            <section class="admin-section">
                <h2>Gerenciar Produtos</h2>
                <form method="POST">
                    <input type="text" name="nome" placeholder="Nome do produto" required>
                    <textarea name="descricao" placeholder="Descrição" required></textarea>
                    <input type="number" step="0.01" name="preco" placeholder="Preço" required>
                    <input type="text" name="imagem" placeholder="URL da imagem">
                    <select name="categoria" required>
                        <option value="">Selecione a categoria</option>
                        <?php
                        $sql = "SELECT * FROM categorias";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="adicionar_produto">Adicionar Produto</button>
                </form>
                
                <h3>Produtos Existentes</h3>
                <table>
                    <tr>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                    <?php
                    $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p 
                            LEFT JOIN categorias c ON p.id_categoria = c.id";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['nome']}</td>
                                <td>R$ {$row['preco']}</td>
                                <td>{$row['categoria_nome']}</td>
                                <td>
                                    <a href='editar_produto.php?id={$row['id']}'>Editar</a>
                                    <a href='excluir_produto.php?id={$row['id']}'>Excluir</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </table>
            </section>
            
            <!-- Gerenciar Ingredientes -->
            <section class="admin-section">
                <h2>Gerenciar Ingredientes</h2>
                <form method="POST">
                    <input type="text" name="nome_ingrediente" placeholder="Nome do ingrediente" required>
                    <input type="number" step="0.01" name="preco_ingrediente" placeholder="Preço" required>
                    <input type="text" name="imagem_ingrediente" placeholder="URL da imagem">
                    <button type="submit" name="adicionar_ingrediente">Adicionar Ingrediente</button>
                </form>
                
                <h3>Ingredientes Existentes</h3>
                <table>
                    <tr>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM ingredientes";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['nome']}</td>
                                <td>R$ {$row['preco']}</td>
                                <td>
                                    <a href='editar_ingrediente.php?id={$row['id']}'>Editar</a>
                                    <a href='excluir_ingrediente.php?id={$row['id']}'>Excluir</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </table>
            </section>
        </div>
    </div>
</body>
</html>