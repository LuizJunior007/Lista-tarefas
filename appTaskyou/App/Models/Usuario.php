<?php 

    namespace App\Models;
    use MF\Model\Model;

    class Usuario extends Model{

        private $id;
        private $nome;
        private $email;
        private $senha;
        private $foto;
        private $data_nascimento;
        private $token;

        public function __get($atr){
            return $this->$atr;
        }

        public function __set($atr, $val){
            $this->$atr = $val;
        }

        public function autenticar(){
            $query = "select id, nome, foto from usuarios where email = :email and senha = :senha";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function editar_foto(){
            $query = "update usuarios set foto = :foto where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':foto', $this->__get('foto'));
            $stmt->bindValue(':id', $this->__get('id'));
            return $stmt->execute();
        }

        public function editar_nome(){
            $query = "update usuarios set nome = :nome where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':nome', $this->__get('nome'));
            return $stmt->execute();
        }

        public function listar_nome_foto(){
            $query = "select nome, foto from usuarios where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function inserir_usuario(){
            $query = "insert into usuarios (nome, email, senha, data_nascimento, token) values (:nome, :email, :senha, :data_nascimento, :token)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->bindValue(':token', $this->__get('token'));
            $stmt->bindValue(':data_nascimento', $this->__get('data_nascimento'));
            return $stmt->execute();
        }

        public function get_email(){
            $query = "select count(id) as total from usuarios where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ)->total;
        }

        public function add_token(){
            $query = "update usuarios set token = :token where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':token', $this->__get('token'));
            $stmt->bindValue(':email', $this->__get('email'));
            return $stmt->execute();
        }

        public function get_token(){
            $query = "select token, email, nome from usuarios where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        public function get_id(){
            $query = "select id, nome, email from usuarios where token = :token";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':token', $this->__get('token'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        public function delete_token(){
            $query = "update usuarios set token = null where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            return $stmt->execute();
        }

        public function update_senha(){
            $query = "update usuarios set senha = :senha where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->bindValue(':id', $this->__get('id'));
            return $stmt->execute();
        }
    }
?>