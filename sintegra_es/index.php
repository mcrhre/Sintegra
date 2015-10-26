<h3>Sintegra Espirito Santo</h3>
<form action='webservice/cliente_rest.php' method='POST'>
	CNPJ: <input type="text" name="cnpj" value="31.804.115-0002-43"><br><br>
	LOGIN: <input type="text" name="login" value="admin"><br><br>
	SENHA: <input type="password" name="senha" value="123456"><br><br>
	<input type="submit" value="PESQUISAR">
</form>
<strong>Exemplo de requisição no ws via url:</strong><br>
<?php echo 'http://127.0.0.1/sintegra_es/webservice/servidor_rest.php?cnpj=31.804.115-0002-43&token=2d08086927f4d87a31154aaf0ba2e067'?>