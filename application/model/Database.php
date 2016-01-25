<?php
	/** 
	 * \brief Classe que implementa interacao com o banco de dados.
	 * \author Renato R. R. de Oliveira
	 */
	if (!isset($DATABASE_INCLUDED)) {
		$DATABASE_INCLUDED = true;
		
		@include("../conf/config_db.php");
		@include("./conf/config_db.php");
		
		// Classe que comunica com banco de dados.
		class Database extends DBCONFIG {
			protected $conexao;
			protected $lastResults;
			
			// Construtor
			function __construct() {
				if (!$this->conectar())
					die("<h1>Erro no Banco de Dados</h1>".
						"Erro ao conectar-se ao banco de dados.<br />".
						"Tente acessar novamente mais tarde.<br />".
						"Caso o problema persista, entre em contato".
						" com um professor.");
				$this->selecionarBD();
				mysql_set_charset('latin1',$this->conexao);
			}
			
			// Conecta ao banco de dados
			public function conectar() {
				$this->conexao = mysql_connect($this->host,$this->usuario,$this->senha);
				if (!$this->conexao) {
					return false;
				}
				else {
					return true;
				}
			}
			
			// Fecha conexao com o banco
			public function desconectar()
			{
				mysql_close($this->conexao);
			}
			
			// Seleciona o DB passado no construtor
			public function selecionarBD()
			{
				mysql_select_db($this->nome,$this->conexao);
			}
			
			// Realiza consultas ao banco e retorna resultado
			public function consulta($sqlCode)
			{
				$this->lastResults = mysql_query($sqlCode,$this->conexao);
				while ($aux = @mysql_fetch_array($this->lastResults))
				{
					$rows[] = $aux;
				}
				return @$rows;
			}
			
			// Executa um comando sem retorno
			public function executar($sqlCode)
			{
				if (!mysql_query($sqlCode,$this->conexao))
				{
					echo "<b>".mysql_error()."</b><br />";
					return false;
				}
				else
				{
					return true;
				}
			}
			
			// Autenticar usuario
			public function autenticar($email, $passwd)
			{
				$search = "SELECT * FROM `usuarios` WHERE `usuarios`.`email` = '".$email.
					"' AND `usuarios`.`senha` = '".md5($passwd)."';";				
				$res = mysql_query($search);
				if (!$res) {
					die('Consulta inválidaaaaaaa! '.$search);
				}
				if ($aux = mysql_fetch_array($res))
				{
					if ($aux['validation_status'] == 1)
						return -1;
					else if ($aux['validation_status'] == 2){
						return -2;
					}
					else{
						$this->authenticatedCPF = $aux['cpf'];
						while (strlen($this->authenticatedCPF) < 11)
							$this->authenticatedCPF = "0".$this->authenticatedCPF;
						return $aux['nivel_acesso'];
					}
				}
				else
				{
					$this->authenticatedCPF = 0;
					return 0;
				}
			}
		}
	}
?>