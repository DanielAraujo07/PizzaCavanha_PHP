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

    // Receber o tipo de produto via GET (default: 1 = Pizzas)
    $tipo_id = isset($_GET['tipo_id']) ? intval($_GET['tipo_id']) : 1;

    $sql = "SELECT t.*, tp.nome as tipo_nome 
            FROM tamanhos t 
            LEFT JOIN tipos_produtos tp ON t.tipo_id = tp.id 
            WHERE t.tipo_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $tipo_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        throw new Exception("Erro ao buscar tamanhos: " . mysqli_error($conn));
    }

    $tamanhos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tamanhos[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'],
            'preco_base' => (float)$row['preco_base'],
            'tipo_id' => (int)$row['tipo_id'],
            'tipo_nome' => $row['tipo_nome']
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