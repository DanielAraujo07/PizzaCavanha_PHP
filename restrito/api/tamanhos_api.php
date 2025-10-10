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

    $sql = "SELECT * FROM tamanhos";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception("Erro ao buscar tamanhos: " . mysqli_error($conn));
    }

    $tamanhos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tamanhos[] = [
            'nome' => $row['nome'],
            'preco_base' => (float)$row['preco_base']
        ];
    }

    $response['success'] = true;
    $response['data'] = $tamanhos;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    error_log("Erro em tamanhos_api: " . $e->getMessage());
}

echo json_encode($response);
?>