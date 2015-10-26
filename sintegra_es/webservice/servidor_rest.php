<?php
	include_once('../spider/Sintegra_ES.class.php');
	include_once('Banco.class.php');
		
	if($_GET){
		if($_GET['cnpj']){
			if(strlen($_GET['cnpj']) == 14 || strlen($_GET['cnpj']) == 18){
				if(autenticacao($_GET['token'])){
					consultar($_GET['cnpj']);
				}else{
					die('acesso negado');
				}
			}else{
				die('erro no cnpj');
			}
		}else{
			die('erro no cnpj');
		}
	}else{
		die('acesso negado');
	}
	
	function autenticacao($token){
		$db = new Banco();
		$sql = "SELECT token FROM tbl_usuario ";
		$sql.= "WHERE token = '".$token."'";
		$resposta = $db->executeQuery($sql);
		$usuario = @mysql_result($resposta, 0, "token");
		if(!empty($usuario)){
			return true;
		}else{
			return false;
		}		
	}
	
	function consultar($cnpj){
		$sintegra = new SintegraES();
		$resposta = $sintegra->consultar($cnpj);
		gravarConsulta($resposta);
		imprimir();
	}
	
	function gravarConsulta($resposta){
		$db = new Banco();
		$sql = "INSERT INTO tbl_consulta(data, json) ";
		$sql.= "VALUES ('".date('d/m/Y')."','".$resposta."')";
		$db->executeQuery($sql);
	}
	
	function consultarBanco(){
		$db = new Banco();
		$sql = "SELECT json FROM tbl_consulta ";
		$sql.= "ORDER BY codigo DESC LIMIT 1";
		$resposta = $db->executeQuery($sql);
		return mysql_result($resposta, 0, "json");
	}
	
	function imprimir(){
		header("Content-Type: application/json; charset=ISO-8859-1");
		$json = consultarBanco();
		print_r(json_decode($json));
	}
?>

