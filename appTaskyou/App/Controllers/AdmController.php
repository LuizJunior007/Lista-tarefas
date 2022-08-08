<?php

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class AdmController extends Action {

        public function dashboard(){

            $this->proteger();

            $usuario = Container::getModel('Usuario');
            $tarefa = Container::getModel('Tarefa');
            
            $usuario->__set('id', $_SESSION['id']);
            $tarefa->__set('id_usuario', $_SESSION['id']);
            $this->view->total_c =  $tarefa->total_t_concluidas();
            $this->view->total_p = $tarefa->total_t_pendentes();
            $this->view->tarefas_c = $tarefa->listar_t_concluidas();
            $this->view->hoje = $tarefa->total_hoje();
            $this->view->perfil = $usuario->listar_nome_foto();

            $this->render('dashboard');
        }

        public function tarefas(){

            $this->proteger();

            $usuario = Container::getModel('Usuario');
            $tarefa = Container::getModel('Tarefa');

            $usuario->__set('id', $_SESSION['id']);
            $tarefa->__set('id_usuario', $_SESSION['id']);

            $this->view->tarefas = $tarefa->listar_tarefas();
            $this->view->perfil = $usuario->listar_nome_foto();

            $this->render('tarefas');
        }
        
        public function nova_tarefa(){

            $this->proteger();

            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('titulo', $_POST['titulo']);
            $tarefa->__set('descricao', $_POST['descricao']);
            $tarefa->__set('prioridade', $_POST['prioridade']);
            $tarefa->__set('data', $_POST['data']);
            $tarefa->__set('hora', $_POST['hora']);
            $tarefa->__set('id_usuario', $_SESSION['id']);

            $tarefa->adicionar_tarefa() ? header('Location: /tarefas?add=success') : header('Location: /tarefas?add=error');
        }

        public function select_tarefa(){

            $this->proteger();

            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('id', $_GET['id']);
            echo json_encode($tarefa->listar_tarefa());
        }

        public function editar_tarefa(){

            $this->proteger();

            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('titulo', $_POST['titulo']);
            $tarefa->__set('descricao', $_POST['descricao']);
            $tarefa->__set('prioridade', $_POST['prioridade']);
            $tarefa->__set('data', $_POST['data']);
            $tarefa->__set('hora', $_POST['hora']);
            $tarefa->__set('id_usuario', $_SESSION['id']);

            $tarefa->editar_tarefas() ? header('Location: /tarefas?edit=success') : header('Location: /tarefas?edit=error');
        }

        public function alterar_foto(){

            $this->proteger();

            $extensao = substr($_FILES['foto']['name'], -4);
            $id_arquivo = uniqid().$extensao;
            $dir = '../public_html/img/avatar/';
            move_uploaded_file($_FILES['foto']['tmp_name'], $dir.$id_arquivo);
            $caminho_img = '/img/avatar/'.$id_arquivo;

            $usuario = Container::getModel('Usuario');
            $usuario->__set('foto', $caminho_img);
            $usuario->__set('id', $_SESSION['id']);

            $usuario->editar_foto() ? header('Location: /dashboard?perfil=true') : header('Location: /dashboard?perfil=error'); 
        }

        public function alterar_nome(){

            $this->proteger();

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);
            $usuario->__set('nome', $_POST['nome']);

            $usuario->editar_nome() ? header('Location: /dashboard?editNome=true') : header('Location: /dashboard?editNome=error');
        }

        public function grafico(){

            $this->proteger();

            $t = Container::getModel('Tarefa');
            $t->__set('id_usuario', $_SESSION['id']);
            
            $dados = array(
                "ontem" => $t->total_ontem(),
                "hoje" => $t->total_hoje()
            );

            echo json_encode($dados);
        }

        public function pesquisar(){

            $this->proteger();

            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('id_usuario', $_SESSION['id']);
            $tarefa->__set('titulo', $_GET['p']);
            echo json_encode($tarefa->pesquisar_tarefas());
        }

        public function classificar(){

            $this->proteger();

            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('id_usuario', $_SESSION['id']);
            
            if($_GET['c'] == 'asc'){

                echo json_encode($tarefa->classificar_d());
            } else{

                $tarefa->__set('prioridade', $_GET['c']);
                echo json_encode($tarefa->classificar_p());
            }
        }

        public function concluir_tarefa(){

            $this->proteger();
            
            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('id', $_POST['id']);
            $tarefa->__set('titulo', $_POST['titulo']);
            $tarefa->__set('descricao', $_POST['descricao']);
            $tarefa->__set('id_usuario', $_SESSION['id']);

            if($tarefa->concluir_tarefas()){

                $tarefa->remover_tarefas();
                header('Location: /tarefas?complete=success');
            } else{

                header('Location: /tarefas?complete=error');
            }
        }

        public function remover_tarefa(){

            $this->proteger();
            $tarefa = Container::getModel('Tarefa');
            $tarefa->__set('id', $_POST['id']);
            $tarefa->remover_tarefas() ? header('Location: /tarefas?remove=success') : header('Location: /tarefas?remove=error');
        }

        private function proteger(){

            session_start();

            if(!isset($_SESSION['id']) || !isset($_SESSION['nome'])){

                header('Location: /?auth=error');
            }
        }

        public function sair(){

            session_start();
            session_destroy();
            header('Location: /');
        }

        
    }

?>