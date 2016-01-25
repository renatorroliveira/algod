<?php 
	if (!isset($USERBS_INCLUDED)) {
		$USERBS_INCLUDED = true;
		
		@include "./model/DAO.php";
		@include "../model/DAO.php";
		@include "./model/User.php";
		@include "../model/User.php";
                include "../randompass.php";
                include "../PHPMailer/class.phpmailer.php";
		
		class UserBS {
			protected $params;
			
			function __construct($params) {
				$this->params = $params;
			}
			
			public function login() {
				@$dao = new DAO(User);
				$query = new QueryBuilder('user');
				$query->addEqual('usr_email', $this->params['usr_email']);
				$query->addEqual('usr_password', sha1($this->params['usr_password']));
				$users = $dao->findByQuery($query);
				if (count($users) != 1)
					return false;
				else {
					$user = $users[0];
					UserSession::getInstance()->login($user);
					return true;
				}
			}
			
			public function save() {
				$model = new User();
				$model->setFields($this->params);
				$model->set('usr_password', sha1($model->get('usr_password')));
				$modelId = $model->get("usr_id");
				if (!isset($modelId)) {
					$status = $this->saveNew($model);
					return $status;
				} else {
					if (UserSession::getInstance()->isLogged() !== true) {
						die("<h1>Forbidden resource for you.</h1>");
					}
					$status = $this->update($model);
					return $status;
				}
			}
			protected function saveNew($user) {
				@$dao = new DAO(User);
				$accessLevel = $user->get("usr_accessLevel");
				if (!isset($accessLevel))
					$user->set("usr_accessLevel", 1);
				else if ($accessLevel > 1) {
					if (UserSession::getInstance()->isLogged() !== true)
						die("<h1>Die f****** cracker!</h1>");
					else if (UserSession::getInstance()->getAccessLevel() < $accessLevel)
						die("<h1>Die f****** cracker!</h1>");
				}
				$user->set("usr_deleted", 0);
				$user->set('usr_confirmationCode', sha1($user->get('usr_id').$user->get('usr_email').$user->get('usr_accessLevel')));
				$status = $dao->save($user);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar cadastrar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			protected function update($user) {
				@$dao = new DAO(User);
				$qbuilder = new QueryBuilder('user');
				$qbuilder->addEqual('usr_id', $user->get('usr_id'));
				$existent = $dao->findByQuery($qbuilder);
				if (count($existent) != 1)
					die("<h1>Usuário inválido.</h1>");
				$existent = $existent[0];
				if (!isset($existent))
					die("<h1>Usuários não existe.</h1>");
				if ($existent->get('usr_id') != UserSession::getInstance()->getUser()->get("usr_id")) {
					if ($existent->get('usr_accessLevel') >= UserSession::getInstance()->getAccessLevel())
						die("<h1>Forbidden resource for you.</h1>");
				} else {
					if ($user->get('usr_accessLevel') > UserSession::getInstance()->getAccessLevel())
						$user->set('usr_accessLevel', UserSession::getInstance()->getAccessLevel());
				}
				$user->set('usr_confirmationCode', sha1($user->get('usr_id').$user->get('usr_email').$user->get('usr_accessLevel')));
				$status = $dao->update($user);
				if ($status !== true) {
					die("Um erro ocorreu ao tentar atualizar o usuário, favor contatar um professor:<br />".$status);
				}
				return true;
			}
			public function recovery($email) {
				@$dao = new DAO(User);
				$query = new QueryBuilder('user');
				$query->addEqual('usr_email', $email);
				$users = $dao->findByQuery($query);
				if (count($users) != 1)
					die("Usuário não encontrado.");
				else {
                                    
                                    $user = $users[0];
                                    $nome = $user->get('usr_name');
                                    $passw = generatePassword(9,8);
                                    $password = sha1($passw);
                                    
                                    
                                    $user->set('usr_password', $password);
                                    $dao->update($user);
                                    
                                    $mail = new PHPMailer();
 
                                    // Charset para evitar erros de caracteres
                                    $mail->Charset = 'UTF-8';

                                    // Dados de quem est? enviando o email
                                    $mail->From = 'emaildoalgod@email.com';
                                    $mail->FromName = 'Algod';

                                    // Setando o conteudo
                                    $mail->IsHTML(true);
                                    $mail->Subject = 'Redefinicao de senha';
                                    $mail->Body     = 'Voce solicitou a redefinicao da sua senha. Sua senha temporaria e ' .$passw . '<br>Por favor faca login em sua conta e modifique-a';
                                    $mail->AltBody = 'Voce solicitou a redefinicao da sua senha. Sua senha temporaria e '.$passw.' Por favor faca login em sua conta e modifique-a';

                                    // Validando a autentica??o
                                    $mail->IsSMTP();
                                    $mail->SMTPAuth = true;
                                    $mail->Host     = "ssl://smtp.googlemail.com";
                                    $mail->Port     = 465;
                                    $mail->Username = 'emailalgod@gmail.com';
                                    $mail->Password = 'coca-cola123';

                                    // Setando o endere?o de recebimento
                                    $mail->AddAddress($email, $nome);
                                    
                                    $result = $mail->Send();
                                    
                                    if($result){
                                        return TRUE;
                                    }else{
                                        $error = $mail->ErrorInfo;
                                        die($error);
                                        //return FALSE;
                                    }
                                }
			}
		}
	}
?>
