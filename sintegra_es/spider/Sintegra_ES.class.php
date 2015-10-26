<?php 
	class SintegraEs{
			
		function consultar($cnpj)
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "http://www.sintegra.es.gov.br/resultado.php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			
			$data = array('num_cnpj' => $cnpj, 'num_ie' => '','botao' => 'Consultar');
			
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			$html = curl_exec($ch);
			
			curl_close($ch);
			
			return $this->extrairDados($html);
		}
		
		private function extrairDados($html)
		{
		
			$re = "/<div id=\"conteudo\"[^>]*>(.*?)<\\/div>/si"; 
	     
			preg_match_all($re, $html, $matches);

			$html = $matches[0][0];

			//IDENTIFICAÇÃO - PESSOA JURÍDICA 
			$re = "/<table width=\"100%\" border=\"0\" cellspacing=\"[12]\" cellpadding=\"[12]\">[^>]*>(.*?)<\\/table>/si"; 

			preg_match_all($re, $html, $identificacao);
			
			//VERIFICA SE CNPJ EXISTE NO SINTEGRA ES
			if (empty($identificacao[0][0])) {
				return '{"sintegra_es": "CNPJ não existe em nossa base de dados"}';
			}
	
			$re = "/<td class=\"valor\"[^>]*>(.*?)<\\/td>/si"; 

			preg_match_all($re, $identificacao[0][0], $valores);

			$valores_indetificacao = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

			//ENDEREÇO   
			$re = "/<td (class=\"valor\"|width=\"30%\")[^>]*>(.*?)<\\/td>/si"; 

			preg_match_all($re, $identificacao[0][1], $valores);

			$valores_endereco = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

			//INFORMAÇÕES COMPLEMENTARES   
			$re = "/<td class=\"valor\"[^>]*>(.*?)<\\/td>/si";

			preg_match_all($re, $identificacao[0][2], $valores);

			$valores_infs_complementares = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));
				 
			preg_match_all($re, $identificacao[0][3], $valores);

			$valores_infs_complementares2 = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

			preg_match_all($re, $identificacao[0][5], $valores);

			$valores_infs_complementares3 = str_replace('&nbsp;','', array_map("strip_tags", $valores[0]));

			//MONTA JSON
			$json = '{"sintegra_es":
						{
							"identidade":
								{
									"cnpj":"'.$valores_indetificacao[0].'",
									"inscricao_estadual":"'.$valores_indetificacao[1].'",
									"razao_social":"'.$valores_indetificacao[2].'"
								},
							"endereco":
								{
									"logradouro:":"'.$valores_endereco[0].'",
									"numero":"'.$valores_endereco[1].'",
									"complemento":"'.$valores_endereco[2].'",
									"bairro":"'.$valores_endereco[3].'",
									"municipio":"'.$valores_endereco[4].'",
									"uf":"'.$valores_endereco[5].'",
									"cep":"'.$valores_endereco[6].'",
									"telefone":"'.$valores_endereco[7].'"
								},
							"informacoes_complementares":
								{
									"atividade_economica":"'.$valores_infs_complementares[0].'",
									"data_inicio_atividade":"'.$valores_infs_complementares[1].'",
									"situacao_cadastral_vigente":"'.$valores_infs_complementares[2].'",
									"data_desta_situacao_cadastral":"'.$valores_infs_complementares[3].'",
									"regime_apuracao":"'.$valores_infs_complementares2[0].'",
									"emitente_nfe_desde":"'.$valores_infs_complementares3[0].'"
						
								}	
						}
					}';	
			return trim($json);
		}
	}
?>
