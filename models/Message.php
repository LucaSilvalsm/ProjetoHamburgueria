<?php

  class Message {

    private $url;

    public function __construct($url) {
      $this->url = $url;
    }

    public function setMessage($msg, $type, $redirect = "..//index.php") {
      // Vou inserir uma mensagem na tela 
      $_SESSION["msg"] = $msg;
      $_SESSION["type"] = $type;
  
      if($redirect != "back") {
          header("Location: $this->url$redirect");
      } else {
          header("Location: " . $_SERVER["HTTP_REFERER"]);
      }
  
      // Certifique-se de que nada é enviado após este ponto
      exit();
  }
  
    public function getMessage() {
        // vou pegar uma mesagem do sistema
      if(!empty($_SESSION["msg"])) { // se a msg não esta vazia
        return [
          "msg" => $_SESSION["msg"],
          "type" => $_SESSION["type"]
        ];
      } else {
        return false;
      }

    }

    public function clearMessage() {
      // vai limpar a mensagem do sistema
      $_SESSION["msg"] = "";
      $_SESSION["type"] = "";
    }

  }