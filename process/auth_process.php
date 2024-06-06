<?php
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Message.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");



$message = new Message($BASE_URL); // nao troquei o nome da classe

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

// regastando o tipo do form

$type = filter_input(INPUT_POST,"type");

// Verificando o tipo de form
if ($type === "registrar") {
  $nome = filter_input(INPUT_POST, "nome");
  $sobrenome = filter_input(INPUT_POST, "sobrenome");
  $endereco = filter_input(INPUT_POST, "endereco");
  $numeroCasa = filter_input(INPUT_POST, "numeroCasa");
  $complemento = filter_input(INPUT_POST, "complemento");
  $bairro = filter_input(INPUT_POST, "bairro");
  $telefone = filter_input(INPUT_POST, "telefone");
  $email = filter_input(INPUT_POST, "email");
  $senha = filter_input(INPUT_POST, "senha");
  $confirmacaoSenha = filter_input(INPUT_POST, "confirmacaoSenha");

    // verificar se os dado minimo foi preenchido

    if ($nome && $sobrenome && $telefone && $email && $senha ) {


        if($senha === $confirmacaoSenha){
            //verificar se o email ou telefone é cadastrado no sistema
            if($usuarioDao->findByTelefone($telefone) !== false || $usuarioDao->findByEmail($email) !== false){
              $message->setMessage("Telefone ou Email já cadastrado, tente outro Telefone ou Email.", "error", "back");
          }
            else {
                $usuario = new Usuario();
                // Criação de token e senha
                $usuarioToken = $usuario->generateToken();
                $finalSenha = $usuario->generatePassword($senha);
                $tipoUsuario = "usuario";
                $usuario->nome = $nome;
                $usuario->sobrenome = $sobrenome;
                $usuario->endereco = $endereco;
                $usuario->numeroCasa = $numeroCasa;
                $usuario->complemento = $complemento;
                $usuario->bairro = $bairro;
                $usuario->telefone = $telefone;
                $usuario->email = $email;
                $usuario->senha = $finalSenha;
                $usuario->token = $usuarioToken; // Atribuir o token gerado à propriedade token
        
                $auth = true;
        
                $usuarioDao->create($usuario, $auth);
            }
        } else {
            $message->setMessage("As senhas não são iguais.", "error", "back");
        }
    } else {
        // Enviar uma msg de erro, de dados faltantes
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }



    
  }else if ($type === "login") {
    $email = filter_input(INPUT_POST, "email");
    $senha = filter_input(INPUT_POST, "senha");

    // Tenta autenticar usuário
    $tipoUsuario = $usuarioDao->getTipoUsuario($email); // Obtém o tipo de usuário

    if ($usuarioDao->authenticateUser($email, $senha)) {
        if ($tipoUsuario === "administrador") {
            $message->setMessage("Seja bem-vindo! ADMIN", "success", "../admin/dashboard.php");
        } else {
            $message->setMessage("Seja bem-vindo! USUARIO", "success", "../index.php");
        }
    } else {
        $message->setMessage("Usuário e/ou senha incorretos.", "error", "..//auth.php");
    }
}

 




  
  





        





    






















