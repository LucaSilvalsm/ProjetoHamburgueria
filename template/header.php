<?php

require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");

require_once(__DIR__ . "/../models/Carrinho.php");

require_once(__DIR__ . "/../dao/UsuarioDAO.php");


$message = new Message($BASE_URL);

$flassMessage = $message->getMessage();

if(!empty($flassMessage["msg"])) {
  // Limpar a mensagem
  $message->clearMessage();
}

$usuarioDao = new UsuarioDAO($conn,$BASE_URL);
$usuarioData = $usuarioDao->verifyToken(false);



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hamburgueria</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css" integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw==" crossorigin="anonymous" />

 <link rel="stylesheet" href="<?=$BASE_URL?>css/newhamburguer.css">  

  <link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- or -->
  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS do projeto -->

  
  
  <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css">
  <link rel="stylesheet" href="<?=$BASE_URL?>css/login.css">
 


  
</head>
<body>
<header>
    <nav id="main-navbar" class="navbar navbar-expand-lg">
      <a href="<?= $BASE_URL ?>" class="navbar-brand">
        <img src="<?= $BASE_URL ?>img/logo.png" alt="Hamburgueria Olimpica" id="logo">
        <span id="hamburgueria-title">Olimpica</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav">
          <?php if($usuarioData): ?>
           
            <li class="nav-item">
              <a href="<?=$BASE_URL ?>newProduct.php" class="nav-link">
              <i class='bx bxs-offer'></i> Promoção
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=$BASE_URL?>meuspedidos.php" class="nav-link">
              <i class='bx bx-notepad'></i>Meus Pedidos</a>
            </li>           
            <li class="nav-item">
              <a href="<?=$BASE_URL?>cesta.php" class="nav-link"> 
              <i class='bx bx-basket'></i> Carrinho</a>
            </li>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>meuspedidos.php" class="nav-link bold">
                <?= $usuarioData->nome ?> <?= $usuarioData->sobrenome ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>logout.php" class="nav-link">Sair</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
              <i class='bx bxs-offer'></i> Promoção
              </a>
            </li>
            <li class="nav-item">                
              <a href="<?= $BASE_URL ?>auth.php" class="nav-link">
              <i class="fas fa-user"></i> Entrar / Cadastrar
                </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>
  <?php if(!empty($flassMessage["msg"])): ?>
    <div class="msg-container">
      <p class="msg <?= $flassMessage["type"] ?>"><?= $flassMessage["msg"] ?></p>
    </div>
  <?php endif; ?>