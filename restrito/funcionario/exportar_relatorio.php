<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão
if ($_SESSION['class_nivel'] < 2) {
    header('Location: ../index.php');
    exit();
}

// Obter parâmetros
$data_inicio = $_GET['data_inicio'] ?? date('Y-m-01');
$data_fim = $_GET['data_fim'] ?? date('Y-m-d');
$filtro_estado = $_GET['estado'] ?? '';

// Configurar headers para download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_pedidos_' . date('Y-m-d') . '.csv');

// Criar output
$output = fopen('php://output', 'w');

// Header do CSV
fputcsv($output, [
    'ID Pedido',
    'Cliente',
    'Valor (R$)',
    'Status',
    'Data/Hora',
    'Forma de Pagamento'
], ';');

// Buscar dados
$sql = "SELECT p.*, u.nome as cliente_nome, e.nome as estado_nome, f.nome as forma_pagamento
        FROM pedido p 
        LEFT JOIN users u ON p.id_cliente = u.id 
        LEFT JOIN estados e ON p.id_estado = e.id
        LEFT JOIN formapag f ON p.id_formapag = f.id
        WHERE DATE(p.horario) BETWEEN ? AND ?";

if (!empty($filtro_estado)) {
    $sql .= " AND p.id_estado = ?";
}

$sql .= " ORDER BY p.horario DESC";

$stmt = mysqli_prepare($conn, $sql);
if (!empty($filtro_estado)) {
    mysqli_stmt_bind_param($stmt, "ssi", $data_inicio, $data_fim, $filtro_estado);
} else {
    mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Escrever dados
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['cliente_nome'],
        number_format($row['valor'], 2, ',', '.'),
        $row['estado_nome'],
        date('d/m/Y H:i', strtotime($row['horario'])),
        $row['forma_pagamento']
    ], ';');
}

fclose($output);
exit;