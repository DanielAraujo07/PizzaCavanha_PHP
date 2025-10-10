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

    $sql = "SELECT * FROM ingredientes WHERE disponivel = TRUE";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception("Erro ao buscar ingredientes: " . mysqli_error($conn));
    }

    $ingredientes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ingredientes[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'],
            'preco' => (float)$row['preco'],
            'imagem' => $row['imagem']
        ];
    }

    $response['success'] = true;
    $response['data'] = $ingredientes;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    error_log("Erro em ingredientes_api: " . $e->getMessage());
}

echo json_encode($response);
?>