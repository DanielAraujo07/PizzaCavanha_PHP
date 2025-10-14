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
		// echo "Conectado!";
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
 ?>