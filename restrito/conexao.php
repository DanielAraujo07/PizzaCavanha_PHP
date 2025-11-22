<?php 
    // Configurações de exibição de erros
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

	$server = "localhost";
	$user = "root";
	$pass = "MyC4stl3T0wn_BEST!";
	$bd = "pizzacavanha";

	if ( $conn = mysqli_connect($server, $user, $pass, $bd) ) {
		// Definir charset para UTF-8
		mysqli_set_charset($conn, "utf8mb4");
	} else 
		die("Erro de conexão: " . mysqli_connect_error());

// Verificar se as funções já existem
if (!function_exists('mensagem')) {
    function mensagem($texto, $tipo) {
        echo "<div class='alert alert-$tipo' role='alert'>$texto</div>";
    }
}

// Função básica de verificação de permissão
if (!function_exists('tem_permissao')) {
    function tem_permissao($nivel_minimo) {
        if (!isset($_SESSION['class_nivel'])) {
            return false;
        }
        return $_SESSION['class_nivel'] >= $nivel_minimo;
    }
}

// Função de formatar data
if (!function_exists('mostra_data')) {
    function mostra_data($data) {
        $d = explode('-', $data);
        $escreve = $d[2] ."/" .$d[1] ."/" .$d[0];
        return $escreve;
    }
}

// Função para registrar logs de auditoria - VERIFICAR SE JÁ EXISTE
if (!function_exists('registrarLog')) {
    function registrarLog($conn, $tabela, $id_registro, $acao, $dados_anteriores = null, $dados_novos = null) {
        $id_usuario = $_SESSION['id'] ?? 0;
        $ip_usuario = $_SERVER['REMOTE_ADDR'] ?? 'DESCONHECIDO';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'DESCONHECIDO';
        
        $sql = "INSERT INTO logs_auditoria (tabela_afetada, id_registro, acao, dados_anteriores, dados_novos, id_usuario, ip_usuario, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sissiiss", $tabela, $id_registro, $acao, $dados_anteriores, $dados_novos, $id_usuario, $ip_usuario, $user_agent);
        
        return mysqli_stmt_execute($stmt);
    }
}

// Função para obter dados formatados para log
if (!function_exists('formatarDadosParaLog')) {
    function formatarDadosParaLog($dados) {
        return json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
?>