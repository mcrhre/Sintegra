<?php
	if($_POST){
		$url  = 'http://127.0.0.1'.str_replace("cliente_rest.php", "servidor_rest.php", $_SERVER['SCRIPT_NAME']);
		$url .= '?cnpj='.$_POST['cnpj'].'&token='.md5($_POST['login'].':'.$_POST['senha']); 
		
		$resultado = file_get_contents($url);
		
		echo '<pre>';
		echo $resultado;
	}	
?>
