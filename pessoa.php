<?php

    Class Pessoa{   
        private $pdo;

        //Conexão 
        public function __construct($dbname, $host, $user, $senha)
        {
            try{
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host, $user, $senha);
            }catch(PDOException $e){
                echo "Erro com banco de dados: ".$e->getMessage();
                exit();
            }catch(Exception $e){
                echo "Erro generico: ".$e->getMessage();
                exit();
            }   
        }

        //Função para buscar e apresentar os dados na sessão direita
        public function buscarDados(){
            $res = array();
            $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
            $res= $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        //Função para cadastrar pessoa e inserir na sessao direita
        public function cadastrar($nome, $telefone, $email){

            //Antes de cadastrar, verificar se possui cadastro pelo email
            $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
            $cmd->bindValue(":e", $email);
            $cmd->execute();
            if($cmd ->rowCount() > 0){  // email existe no banco
                return false;
            }else{                  // cadastrar
                $cmd = $this->pdo->prepare("INSERT INTO pessoa(nome, telefone, email) VALUES (:n, :t, :e)");
                $cmd->bindValue(":n", $nome);
                $cmd->bindValue(":t", $telefone);
                $cmd->bindValue(":e", $email);
                $cmd->execute();
                return true;
            }
        }

        //Funcao para excluir cadastro
        public function excluir($id){
            $cmd= $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }
        
        //Funcao para buscar dados por cadastro
        public function buscarCadastro($id){
            $res = array();
            $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
            $cmd->bindValue(":id", $id);
            $cmd->execute();
            $res= $cmd->fetch(PDO::FETCH_ASSOC);
            return $res;
        }

        //Funcao para atualizar dados do cadastro no banco
        public function atualizarDados($id,$nome, $telefone, $email){
            $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
            $cmd->bindValue(":n", $nome);
            $cmd->bindValue(":t", $telefone);
            $cmd->bindValue(":e", $email);
            $cmd->bindValue(":id", $id);
            $cmd->execute();
        }
    }
?>