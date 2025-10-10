<?php 
	$server = "localhost";
	$user = "root";
	$pass = "MyC4stl3T0wn_BEST!";
	$bd = "pizzacavanha";



	if ( $conn = mysqli_connect($server, $user, $pass, $bd) ) {
		// echo "Conectado!";
	} else 
		echo "Erro!";

if (!function_exists('mensagem')) {
    function mensagem($texto, $tipo) {
        echo "<div class='alert alert-$tipo' role='alert'>
                $texto
              </div>";
    }
}

if (!function_exists('mostra_data')) {
    function mostra_data($data) {
        $d = explode('-', $data);
        $escreve = $d[2] ."/" .$d[1] ."/" .$d[0];
        return $escreve;
    }
}

 ?>