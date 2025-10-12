<?php
session_start();
include "../conexao.php";

header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        exit;
    }
    
    // Iniciar transação
    mysqli_begin_transaction($conn);
    
    try {
        $id_cliente = $data['id_cliente'];
        $id_estado = $data['id_estado'];
        $id_formapag = $data['id_formapag'];
        $valor = $data['valor'];
        $tipo_entrega = $data['tipo_entrega'];
        $dados_entrega = $data['dados_entrega'];
        $itens = $data['itens'];
        
        // DEBUG: Verificar itens recebidos
        error_log("Itens recebidos: " . print_r($itens, true));
        
        // 1. Criar registro de entrega
        if ($tipo_entrega == 1) { // Delivery
            $endereco = $dados_entrega['rua'] . ', ' . $dados_entrega['numero'] . ' - ' . 
                       $dados_entrega['bairro'] . ', ' . $dados_entrega['cidade'] . 
                       ($dados_entrega['complemento'] ? ' - ' . $dados_entrega['complemento'] : '');
        } else { // Retirada
            $endereco = $dados_entrega['tipoRetirada'] == 'mesa' ? 'Entrega na mesa' : 'Retirada no balcão';
        }
        
        $sql_entrega = "INSERT INTO entrega (id_tipo, endereco) VALUES (?, ?)";
        $stmt_entrega = mysqli_prepare($conn, $sql_entrega);
        mysqli_stmt_bind_param($stmt_entrega, "is", $tipo_entrega, $endereco);
        
        if (!mysqli_stmt_execute($stmt_entrega)) {
            throw new Exception("Erro ao criar entrega: " . mysqli_error($conn));
        }
        
        $id_entrega = mysqli_insert_id($conn);
        
        // 2. Criar pedido
        $sql_pedido = "INSERT INTO pedido (id_cliente, id_estado, id_entrega, id_formapag, valor) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt_pedido = mysqli_prepare($conn, $sql_pedido);
        mysqli_stmt_bind_param($stmt_pedido, "iiisd", $id_cliente, $id_estado, $id_entrega, $id_formapag, $valor);
        
        if (!mysqli_stmt_execute($stmt_pedido)) {
            throw new Exception("Erro ao criar pedido: " . mysqli_error($conn));
        }
        
        $id_pedido = mysqli_insert_id($conn);
        
        // 3. Processar itens do pedido - CORREÇÃO AQUI
        foreach ($itens as $item) {
            // DEBUG: Verificar cada item
            error_log("Processando item: " . print_r($item, true));
            
            // Validar dados do item
            if (empty($item['nome']) || !isset($item['quantidade']) || !isset($item['preco'])) {
                error_log("Item inválido: " . print_r($item, true));
                continue; // Pular item inválido
            }
            
            // Inserir item
            $sql_item = "INSERT INTO itens (nome, quantidade, valor, descricao, observacao) 
                        VALUES (?, ?, ?, ?, ?)";
            $stmt_item = mysqli_prepare($conn, $sql_item);
            
            $nome = $item['nome'];
            $quantidade = intval($item['quantidade']);
            $preco = floatval($item['preco']);
            $descricao = isset($item['descricao']) ? $item['descricao'] : '';
            $observacao = isset($item['observacao']) ? $item['observacao'] : '';
            
            mysqli_stmt_bind_param($stmt_item, "sidss", $nome, $quantidade, $preco, $descricao, $observacao);
            
            if (!mysqli_stmt_execute($stmt_item)) {
                throw new Exception("Erro ao criar item: " . mysqli_error($conn));
            }
            
            $id_item = mysqli_insert_id($conn);
            
            // Vincular item ao pedido
            $sql_pedido_item = "INSERT INTO pedido_itens (id_pedido, id_item) VALUES (?, ?)";
            $stmt_pedido_item = mysqli_prepare($conn, $sql_pedido_item);
            mysqli_stmt_bind_param($stmt_pedido_item, "ii", $id_pedido, $id_item);
            
            if (!mysqli_stmt_execute($stmt_pedido_item)) {
                throw new Exception("Erro ao vincular item ao pedido: " . mysqli_error($conn));
            }
            
            // Processar adicionais se existirem
            if (isset($item['adicionais']) && is_array($item['adicionais']) && !empty($item['adicionais'])) {
                foreach ($item['adicionais'] as $adicional) {
                    if (isset($adicional['id'])) {
                        $sql_ingrediente_item = "INSERT INTO ingredientes_itens (id_item, id_ingrediente) VALUES (?, ?)";
                        $stmt_ingrediente_item = mysqli_prepare($conn, $sql_ingrediente_item);
                        mysqli_stmt_bind_param($stmt_ingrediente_item, "ii", $id_item, $adicional['id']);
                        
                        if (!mysqli_stmt_execute($stmt_ingrediente_item)) {
                            throw new Exception("Erro ao vincular ingrediente: " . mysqli_error($conn));
                        }
                    }
                }
            }
        }
        
        // Commit da transação
        mysqli_commit($conn);
        
        echo json_encode([
            'success' => true, 
            'pedido_id' => $id_pedido,
            'message' => 'Pedido criado com sucesso!'
        ]);
        
    } catch (Exception $e) {
        // Rollback em caso de erro
        mysqli_rollback($conn);
        error_log("Erro ao finalizar pedido: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>