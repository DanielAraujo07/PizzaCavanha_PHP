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

    // Receber o tipo de ingrediente via GET (opcional)
    $tipo_id = isset($_GET['tipo']) ? intval($_GET['tipo']) : null;

    $sql = "SELECT * FROM ingredientes WHERE disponivel = TRUE";
    
    // Adicionar filtro por tipo se especificado
    if ($tipo_id !== null) {
        $sql .= " AND tipo_id = ?";
    }
    
    $sql .= " ORDER BY nome";

    if ($tipo_id !== null) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $tipo_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, $sql);
    }
    
    if (!$result) {
        throw new Exception("Erro ao buscar ingredientes: " . mysqli_error($conn));
    }

    $ingredientes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ingredientes[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'],
            'preco' => (float)$row['preco'],
            'imagem' => $row['imagem'],
            'tipo_id' => (int)$row['tipo_id']
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