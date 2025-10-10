<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include_once "../conexao.php";

$response = ['success' => false, 'data' => []];

try {
    if (!$conn) {
        throw new Exception("Não foi possível conectar ao banco de dados");
    }

    // Buscar todas as categorias
    $categorias = [];
    $sql_categorias = "SELECT * FROM categorias";
    $result_categorias = mysqli_query($conn, $sql_categorias);
    
    if (!$result_categorias) {
        throw new Exception("Erro ao buscar categorias: " . mysqli_error($conn));
    }
    
    while ($row = mysqli_fetch_assoc($result_categorias)) {
        $categorias[$row['nome']] = [];
    }

    // Buscar todos os produtos
    $sql_produtos = "SELECT p.*, c.nome as categoria_nome 
                     FROM produtos p 
                     LEFT JOIN categorias c ON p.id_categoria = c.id 
                     WHERE p.disponivel = TRUE";
    $result_produtos = mysqli_query($conn, $sql_produtos);
    
    if (!$result_produtos) {
        throw new Exception("Erro ao buscar produtos: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result_produtos)) {
        $categoria = $row['categoria_nome'];
        $produto = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'],
            'descricao' => $row['descricao'],
            'preco' => (float)$row['preco'],
            'imagem' => $row['imagem']
        ];

        $categorias[$categoria][] = $produto;
        $categorias['todas'][] = $produto;
    }

    $response['success'] = true;
    $response['data'] = $categorias;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    error_log("Erro em cardapio_api: " . $e->getMessage());
}

echo json_encode($response);
?>