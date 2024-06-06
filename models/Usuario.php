<?php

// model para mandar pro DAO
    class Usuario {

        public $id;
        public $nome;
        public $sobrenome;
        public $endereco;
        public $numeroCasa;
        public $complemento;    
        public $bairro;
        public $telefone;
        public $email;
        public $senha;
        public $token;
        public $tipoUsuario;

        public function getfullEndereco($usuario){
            return $usuario->endereco." " . "-" . " ".$usuario->numeroCasa." " . "-" ." ".$usuario->complemento ." -" . " " .$usuario->bairro;

        }
        public function getfullName($usuario){
            return $usuario->nome . " " . $usuario->sobrenome;

        }
        public function generateToken(){
            return bin2hex(random_bytes(50));

        }
        public function generatePassword($senha){
            return password_hash($senha, PASSWORD_DEFAULT);


        }
        public function imageGenerateName() {
            return bin2hex(random_bytes(60)) . ".jpg";
          }
      


    }

    interface UsuarioDAOinferface{

        public function buildUsuario($data); // para construir o id o objeto

        public function create(Usuario $usuario, $authUsuario = false); // para criar o usuario no sistema para fazer o login

        public function update(Usuario $user, $redirect = true); // att os dados no sistema       

        public function verifyToken($protected = false); // para fazer o controle da rota 

        public function setTokenToSession($token,$redirect = true); // para redirecionar o usuario para uma pagina 

        public function authenticateUser($email,$senha); // para fazer a autentificação completa

        public function authenticateTelefone($telefone,$senha); // para fazer a autentificação completa
        public function findByTelefone($telefone); // localiar o usuario pelo telefone

        public function findByEmail($email); // localiar o usuario pelo email

        public function findById($id); // encontrar o usuario pelo ID
        public function getTipoUsuario($email);

        public function findByToken($token); // para encontrar o usuario pelo token

        public function changeSenha(Usuario $usuario); // troca de senha

        public function destroyToken();// desloga o usuario






    }





















?>