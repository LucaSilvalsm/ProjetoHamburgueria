<?php

require_once(__DIR__ . "/../models/Usuario.php");

require_once(__DIR__ . "/../models/Message.php");


require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");




class UsuarioDAO implements UsuarioDAOinferface
{

  private $conn;
  private $url;
  private $message;

  public function __construct(PDO $conn, $url)
  {
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }




  public function buildUsuario($data)
  {
    $usuario = new Usuario();

    // Verifica se $data não é nulo e se contém todas as chaves necessárias
    if (
      !empty($data) &&
      isset(
        $data["id"],
        $data["nome"],
        $data["sobrenome"],
        $data["endereco"],
        $data["numeroCasa"],
        $data["complemento"],
        $data["bairro"],
        $data["telefone"],
        $data["email"],
        $data["senha"],
        $data["token"]
      )
    ) {
      $usuario->id = $data["id"];
      $usuario->nome = $data["nome"];
      $usuario->sobrenome = $data["sobrenome"];
      $usuario->endereco = $data["endereco"];
      $usuario->numeroCasa = $data["numeroCasa"];
      $usuario->complemento = $data["complemento"];
      $usuario->bairro = $data["bairro"];
      $usuario->telefone = $data["telefone"];
      $usuario->email = $data["email"];
      $usuario->senha = $data["senha"];
      $usuario->token = $data["token"];
    } else {
      // Tratar o caso em que os dados estão ausentes ou incompletos
      // Por exemplo, lançar uma exceção ou retornar um valor padrão
    }

    return $usuario;
  }


  public function create(Usuario $usuario, $authUsuario = false)
  { // para criar o usuario no sistema para fazer o login

    $stmt = $this->conn->prepare("INSERT INTO usuario
            (nome , sobrenome,tipoUsuario, endereco, numeroCasa, complemento, bairro, telefone, email, senha, token)
             VALUES(:nome , :sobrenome, :tipoUsuario,:endereco, :numeroCasa, :complemento, :bairro, :telefone, :email, :senha, :token) ");

    $stmt->bindParam(":nome", $usuario->nome);
    $stmt->bindParam(":sobrenome", $usuario->sobrenome);
    $stmt->bindParam(":endereco", $usuario->endereco);
    $stmt->bindParam(":tipoUsuario", $usuario->tipoUsuario);
    $stmt->bindParam(":numeroCasa", $usuario->numeroCasa);
    $stmt->bindParam(":complemento", $usuario->complemento);
    $stmt->bindParam(":bairro", $usuario->bairro);
    $stmt->bindParam(":telefone", $usuario->telefone);
    $stmt->bindParam(":email", $usuario->email);
    $stmt->bindParam(":senha", $usuario->senha);
    $stmt->bindParam(":token", $usuario->token);

    $stmt->execute();

    if ($authUsuario) {
      $this->setTokenToSession($usuario->token);
    }
  }

  public function update(Usuario  $usuario, $redirect = true)
  {   // att os dados no sistema  
    $stmt = $this->conn->prepare("UPDATE usuario SET
            nome = :nome , 
            sobrenome = :sobrenome,
            endereco =  :endereco, 
            numeroCasa = :numeroCasa, 
            complemento = :complemento, 
            bairro =  :bairro, 
            telefone = :telefone, 
            email = :email, 
            senha = :senha, 
            token =  :token
            WHERE id = :id
          ");

    $stmt->bindParam(":nome", $usuario->nome);
    $stmt->bindParam(":sobrenome", $usuario->sobrenome);
    $stmt->bindParam(":endereco", $usuario->endereco);
    $stmt->bindParam(":numeroCasa", $usuario->numeroCasa);
    $stmt->bindParam(":complemento", $usuario->complemento);
    $stmt->bindParam(":bairro", $usuario->bairro);
    $stmt->bindParam(":telefone", $usuario->telefone);
    $stmt->bindParam(":email", $usuario->email);
    $stmt->bindParam(":senha", $usuario->senha);
    $stmt->bindParam(":token", $usuario->token);
    $stmt->bindParam(":id", $usuario->id);

    $stmt->execute();

    if ($redirect) {

      // Redireciona para o perfil do usuario
      $this->message->setMessage("Dados atualizados com sucesso!", "success", "..//index.php");
    }
  }

  public function verifyToken($protected = false)
  { // para fazer o controle da rota 

    if (!empty($_SESSION["token"])) {

      // Pega o token da session
      $token = $_SESSION["token"];

      $usuario = $this->findByToken($token);

      if ($usuario) {
        return $usuario;
      } else if ($protected) {

        // Redireciona usuário não autenticado
        $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
      }
    } else if ($protected) {

      // Redireciona usuário não autenticado
      $this->message->setMessage("Faça a login para adicionar no carrinho ", "error", "index.php");
    }
  }




  public function setTokenToSession($token, $redirect = true)
  { //para redirecionar o usuario para uma pagina 
    $_SESSION["token"] = $token;

    if ($redirect) {

      // Redireciona para o perfil do usuario
      $this->message->setMessage("Seja bem-vindo!", "success", "index.php");
    }
  }

  public function authenticateUser($email, $senha)
  { // para fazer a autentificação completa

    $usuario = $this->findByEmail($email);

    if ($usuario) {

      // Checar se as senhas batem
      if (password_verify($senha, $usuario->senha)) {

        // Gerar um token e inserir na session
        $token = $usuario->generateToken();

        $this->setTokenToSession($token, false);

        // Atualizar token no usuário
        $usuario->token = $token;

        $this->update($usuario, false);

        return true;
      } else {
        return false;
      }
    } else {

      return false;
    }
  }
  public function findByEmail($email)
  {
    //localizando pelo email
    if ($email != "") {

      $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = :email");

      $stmt->bindParam(":email", $email);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $usuario = $this->buildUsuario($data);

        return $usuario;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function getTipoUsuario($email)
  {
    try {
      $stmt = $this->conn->prepare("SELECT tipoUsuario FROM usuario WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['tipoUsuario'];
      } else {
        return null; // Se o usuário não for encontrado, retorne null
      }
    } catch (PDOException $e) {
      echo "Erro: " . $e->getMessage();
      return null;
    }
  }








  public function authenticateTelefone($telefone, $senha)
  { // para fazer a autentificação completa

    $usuario = $this->findByTelefone($telefone);

    if ($usuario) {

      if (password_verify($senha, $usuario->senha)) {

        $token = $usuario->generateToken();

        $this->setTokenToSession($token, false);

        // atualiza o token do usuario

        $usuario->token = $token;

        $this->update($usuario, false);

        return true;
      } else {
        return false;
      }
    } else {

      return false;
    }
  }
  public function findByTelefone($telefone)
  { // localiar o usuario pelo telefone

    if ($telefone != "") {

      $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE telefone = :telefone");

      $stmt->bindParam(":telefone", $telefone);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $usuario = $this->buildUsuario($data);

        return $usuario;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function findById($id)
  {
    $usuario = null; // Inicialize como null

    try {
      $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuario = $this->buildUsuario($usuarioData);
      }
    } catch (PDOException $e) {
      // Tratamento de erro aqui, por exemplo, registrar o erro ou lançar uma exceção
      // Você pode lançar uma exceção aqui se preferir
      echo "Erro ao encontrar usuário: " . $e->getMessage();
    } finally {
      // Sempre limpe os recursos
      $stmt = null;
    }

    return $usuario;
  }




  public function findByToken($token)
  { // para encontrar o usuario pelo token

    if ($token != "") {

      $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE token = :token");


      $stmt->bindParam(":token", $token);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $usuario = $this->buildUsuario($data);

        return $usuario;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function destroyToken()
  {
    // Remove o token da session
    $_SESSION["token"] = "";

    // Redirecionar e apresentar a mensagem de sucesso
    $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
  }

  public function changeSenha(Usuario $usuario)
  { // troca de senha






  }
  public function ADMdestroyToken()
  {
    // Remove o token da session
    $_SESSION["token"] = "";

    // Redirecionar e apresentar a mensagem de sucesso
    $this->message->setMessage("Você fez o logout com sucesso!", "success", "..//index.php");
  }
}
