<?php

	namespace App\Controllers;
	use MF\Controller\Action;
	use MF\Model\Container;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

class IndexController extends Action {

	public function index() {

		date_default_timezone_set('America/Sao_Paulo');
		$ano = date('Y');
		
		$this->view->ano = $ano;
		$this->view->msg = $this->mensagem();
		$this->render('index');
	}

	private function mensagem(){
		
		$msg = '';

		if(isset($_GET['auth']) && $_GET['auth'] == 'error'){

			return $msg = 'Email ou senha inválidos';

		} else if(isset($_GET['link']) && $_GET['link'] == 'error'){

			return $msg = 'Página não encontrada, o link já expirou';

		} else if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'error'){

			return $msg = 'Erro ao tentar cadastrar usuário';

		} else if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'true'){

			return $msg = 'Cadastro realizado com sucesso';

		} else if(isset($_GET['email']) && $_GET['email'] == 'error'){

			return $msg = 'Esse email já está sendo usado em nosso sistema';

		} else if(isset($_GET['email_v']) && $_GET['email_v'] == 'false'){

			return $msg = 'Email inválido';

		} else if(isset($_GET['email_enviado']) && $_GET['email_enviado'] == 'true'){

			return $msg = 'Foi enviado um link para seu email, por favor verificar caixa de entrada e spam';

		} else if(isset($_GET['email_enviado']) && $_GET['email_enviado'] == 'error'){

			return $msg = 'Falha ao enviar mensagem';

		} else if(isset($_GET['email_check']) && $_GET['email_check'] == 'false'){

			return $msg = 'Esse email não está cadastrado em nosso sistema';
		}

		else if(isset($_GET['alter_s']) && $_GET['alter_s'] == 'error'){

			return $msg = 'Error ao tentar alterar senha';
		}

		else if(isset($_GET['alter_s']) && $_GET['alter_s'] == 'success'){

			return $msg = 'Senha alterada com sucesso, faça login para acessar o sistema';
		}
	}

	public function alterar_senha(){

		$usuario = Container::getModel('Usuario');
		$usuario->__set('token', $_GET['token']);

		$dados_usuario = $usuario->get_id();

		if(!isset($dados_usuario->id)){
			header('Location: /?link=error');
		} else{

			header("Refresh: 300; url=/?link=error");
			$usuario->__set('id', $dados_usuario->id);

			session_start();
			$_SESSION['id'] = $dados_usuario->id;

			$usuario->delete_token();
			$this->render('alterar_senha');
		}

		
	}

	public function nova_senha(){

		if($_POST['nova_senha'] != $_POST['confirm_senha']){

			$this->mensagem();
		} else if(strlen($_POST['nova_senha']) < 6 || strlen($_POST['confirm_senha']) < 6){

			$this->mensagem();
		} else{

			session_start();

			$usuario = Container::getModel('Usuario');
			$usuario->__set('senha', md5($_POST['confirm_senha']));
			$usuario->__set('id', $_SESSION['id']);

			if($usuario->update_senha()){
				session_destroy();				
				header('Location: /?alter_s=success');
			} else{

				header('Location: /?alter_s=error');
			}
		}
	}


	public function auth(){

		$usuario = Container::getModel('Usuario');
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		$user = $usuario->autenticar();

		if(isset($user['id']) != '' && $user['nome'] != ''){

			session_start();

			$_SESSION['id'] = $user['id'];
			$_SESSION['nome'] = $user['nome'];
			
			header('Location: /dashboard');
		} else{
			
			header('Location: /?auth=error');
		}
	}

	public function cadastrar_usuario(){

		$usuario = Container::getModel('Usuario');
		$usuario->__set('email', $_POST['email']);

		if(strlen($_POST['nome']) < 4 || strlen($_POST['senha']) < 6 || !$this->validarEmail($_POST['email'])){

			header('Location: /?cadastro=error');

		} else if($usuario->get_email() > 0){
			header('Location: /?email=error');
		}
		  
		else{

			$usuario->__set('nome', $_POST['nome']);
			$usuario->__set('email', $_POST['email']);
			$usuario->__set('senha', md5($_POST['senha']));
			$usuario->__set('data_nascimento', $_POST['data']);

			$usuario->inserir_usuario() ? header('Location: /?cadastro=true') : header('Location: /?cadastro=error');
		}
	}

	public function redefinir_senha(){

		if(!$this->validarEmail($_POST['email'])){

			header('Location: /?email_v=false');

		} else{

		   $usuario = Container::getModel('Usuario');
		   $usuario->__set('email', $_POST['email']);

			if($usuario->get_email()){

				$token = bin2hex(random_bytes(32)); 
				$usuario->__set('token', $token);
				
				$usuario->add_token();
				
				$dados_usuario = $usuario->get_token();
				$email = new PHPMailer();

				try{

					header('Location: /?email_enviado=true');

					$email->SMTPDebug = SMTP::DEBUG_SERVER;                      
					$email->isSMTP();                                            
					$email->Host       = 'smtp.gmail.com';                     
					$email->SMTPAuth   = true;                                   
					$email->Username   = 'sistarefa.01@gmail.com';                     
					$email->Password   = 'j95222676';                               
					$email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
					$email->Port       = 465;
					
					$email->setFrom('sistarefa.01@gmail.com');
					$email->addAddress($_POST['email']);

					//Content
					$email->isHTML();                                  
					$email->Subject = 'Redefinir senha';
					$email->Body    = "Olá <strong>$dados_usuario->nome</strong>, recebemos um pedido para redefinir sua senha. <br>Clique no link abaixo para alterar sua senha.<br><a href='https://ljrportifolio.com/alterar_senha?token=$dados_usuario->token'>Redefinir senha</a><br><strong>Se não foi você que fez essa solicitação, por favor ignore este email.</strong>";
					$email->AltBody = 'Redefinir senha';

					$email->send();
				} catch(\PDOException $e){
					header('Location: /?email_enviado=error');
				}
			} else{

				header('Location: /?email_check=false');
			}
		}
	}

	private function validarEmail($email){

		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
		} else{
			return false;
		}
	}
}
