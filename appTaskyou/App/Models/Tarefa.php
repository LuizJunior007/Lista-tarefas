<?php

    namespace App\Models;
    use MF\Model\Model;

    class Tarefa extends Model{

        private $id;
        private $titulo;
        private $descricao;
        private $prioridade;
        private $data;
        private $hora;
        private $id_usuario;

        public function __get($atr){
            return $this->$atr;
        } 

        public function __set($atr, $val){
            $this->$atr = $val;
        }

        public function adicionar_tarefa(){
            $query = "insert into tarefas (titulo, descricao, prioridade, data, hora, id_usuario) values (:titulo, :descricao, :prioridade, :data, :hora, :id_usuario)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':titulo', $this->__get('titulo'));
            $stmt->bindValue(':descricao', $this->__get('descricao'));
            $stmt->bindValue(':prioridade', $this->__get('prioridade'));
            $stmt->bindValue(':data', $this->__get('data'));
            $stmt->bindValue(':hora', $this->__get('hora'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            return $stmt->execute();
        }

        public function listar_tarefas(){
            $query = "select * from tarefas where id_usuario = :id_usuario order by id desc";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function editar_tarefas(){
            $query = "update tarefas set titulo = :titulo, descricao = :descricao, prioridade = :prioridade, data = :data, hora = :hora where id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':titulo', $this->__get('titulo'));
            $stmt->bindValue(':descricao', $this->__get('descricao'));
            $stmt->bindValue(':prioridade', $this->__get('prioridade'));
            $stmt->bindValue(':data', $this->__get('data'));
            $stmt->bindValue(':hora', $this->__get('hora'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            return $stmt->execute();
        }

        public function remover_tarefas(){
            $query = "delete from tarefas where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            return $stmt->execute();
        }

        public function listar_tarefa(){
            $query = "select * from tarefas where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function concluir_tarefas(){
            $query = "insert into tarefas_concluidas (titulo, descricao, id_usuario) values (:titulo, :descricao, :id_usuario)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':titulo', $this->__get('titulo'));
            $stmt->bindValue(':descricao', $this->__get('descricao'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            return $stmt->execute();
        }

        public function pesquisar_tarefas(){
            $query = "select * from tarefas where titulo like :titulo and id_usuario = :id_usuario order by id desc";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':titulo', '%'. $this->__get('titulo').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function classificar_d(){
            
            $query = "select * from tarefas where id_usuario = :id_usuario order by data asc";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);   
        }

        public function classificar_p(){
            $query = "select * from tarefas where prioridade = :prioridade and id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':prioridade', $this->__get('prioridade'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function total_t_pendentes(){
            $query = "select count(id) as total from tarefas where id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ)->total;
        }

        public function total_t_concluidas(){
            $query = "select count(id) as total from tarefas_concluidas where id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ)->total;
        }

        public function total_hoje(){
            $query = "select count(id) as total from tarefas_concluidas where id_usuario = :id_usuario and data_fim = CURRENT_DATE()";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ)->total;
        }

        public function listar_t_concluidas(){
            $query = "select * from tarefas_concluidas where id_usuario = :id_usuario order by id desc limit 5";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function total_ontem(){
            $query = "select count(id) as total from tarefas_concluidas where id_usuario = :id_usuario and data_fim = CURDATE() - INTERVAL 1 DAY";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue('id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ)->total;
        }
    }
?>