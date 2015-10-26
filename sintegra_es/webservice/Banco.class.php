<?php
class Banco
{
	private $host = "localhost";
	private $usuario = "root";
	private $senha = "";
	private $banco_dados = "db_sintegra";

	private $query;
	private $link;
	private $resultado;

	function Banco(){ }

	function conectar()
	{
		$this->link = mysql_connect($this->host,$this->usuario,$this->senha);
		
		if(!$this->link)
		{
			echo "Falha na conexão com o Banco de Dados!<br />";
			echo "Erro: " . mysql_error();
			die();
		}
		elseif(!mysql_select_db($this->banco_dados, $this->link))
		{
			echo "O Bando de Dados solicitado não pode ser aberto!<br />";
			echo "Erro: " . mysql_error();
			die();
		}
	}

	function executeQuery($query)
	{
		$this->conectar();
		$this->query = $query;
		if($this->resultado = mysql_query($this->query))
		{
			$this->desconectar(); 
			return $this->resultado;
		}
		else
		{
			echo "Ocorreu um erro na execução da SQL";
			echo "Erro :" . mysql_error();
			echo "SQL: " . $query;
			die();
			desconectar();
		}
	}

	function desconectar()
	{
		return mysql_close($this->link);
	}
}
?>
