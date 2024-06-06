<?php
ob_start();
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
$usuarioDao = new UsuarioDAO($conn,$BASE_URL);

$usuarioData = $usuarioDao->verifyToken(false);



?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aprendendo</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>//css//newhamburguer.css">

  <link rel="stylesheet" href="<?= $BASE_URL ?>..//css//styles.css">


  <!-- CSS do projeto -->
  <!-- or -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css" integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw==" crossorigin="anonymous" />



  <link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <!-- or -->
  <link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

  <link rel="stylesheet" href="<?= $BASE_URL ?>dashboard.css">

  

  <!-- CSS do projeto -->

  

</head>
<body>
    <header>
        <div style="align-items: center;" class="info-header">
            <div class="logo">
                <h3>Aprendendo</h3>
            </div>
            <div class="icon-header">
                <i class='bx bxs-offer'></i>
            </div>
        </div>
        <div style="align-items: center;" class="info-header">
            <a href="#"> <i class='fa-solid fa-bell'></i></a>
            <a href="#"> <i class='bx bxs-notepad'></i>
            </a> <img src="<?= $BASE_URL ?>logo.png" alt="logo" id="logo">
        </div>
    </header> <!-- Fim do header -->
    <section class="main">
        <div class="sidebar">
            <h3> HOME</h3>
            <a class="sidebar-active" href="<?=$BASE_URL?>dashboard.php"> <i class='bx bxs-report'></i> Relatorio de Venda</a>
            <a href="#"><i class='bx bxs-report'></i> Relatorio de Pedidos</a>
            <a href="#"><i class='bx bxs-report'></i> Relatorio de Veda</a>
            <a href="#"><i class='bx bxs-report'></i> Relatorio </a>
            <a href="#"><i class='bx bxs-report'></i> Relatorio </a>
            <a href="#"><i class='bx bxs-report'></i> Relatorio </a>
            <br/>
            <hr>

            <h3> PRODUTOS</h3>
            <a href="<?=$BASE_URL ?>newproduct.php"><i class='bx bxs-report'></i> Adicionar Produto</a>
            <a href="#"><i class='bx bxs-report'></i> Atualizar Produto</a>
            <a href="<?=$BASE_URL?>produtos.php"><i class='bx bxs-report'></i> Remover Produto</a>
            <a href="#"><i class='bx bxs-badge-dollar'></i> Adicionar Promoção</a>           
            <br/>
            <hr>

            <h3> Pedidos</h3>
            <a href="<?=$BASE_URL ?>AcompanharPedido.php"><i class='bx bxs-report'></i> Acompanhar Pedidos</a>
            <a href="#"><i class='bx bxs-report'></i> Atualizar os Pedidos</a>
            <a href="#"><i class='bx bxs-report'></i> Ver todos os Pedidos</a>
                 
            <br/>
            <hr>

            <h3> HOME</h3>
            <a href="#"><i class='bx bxs-report'></i >Relatorio de Venda</a>
            <a href="#"><i class='bx bxs-report'></i> Relatorio de Pedidos</a>
            <a href="<?= $BASE_URL ?>..//index.php"><i class='bx bxs-report'></i> Home Site da Loja</a>

           
            <a href="<?= $BASE_URL ?>logout.php"><i class='bx bxs-report'></i> Sair</a>
            <br/>
            <hr>
        </div> <!--FIM Sidebar-->
        