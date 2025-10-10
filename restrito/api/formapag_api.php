<?php
include "../conexao.php";

header('Content-Type: application/json');

$sql = "SELECT * FROM formapag";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $formas_pagamento = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $formas_pagamento[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $formas_pagamento
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Nenhuma forma de pagamento encontrada'
    ]);
}
?>