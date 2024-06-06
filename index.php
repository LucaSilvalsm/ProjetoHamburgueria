<?php
require_once(__DIR__ . "/config/globals.php");
require_once(__DIR__ . "/config/db.php");
require_once(__DIR__ . "/template/header.php");
require_once(__DIR__ . "/dao/UsuarioDAO.php");
require_once(__DIR__ . "/dao/ProdutosDAO.php");





$ProdutosDao = new ProdutosDao($conn, $BASE_URL);




$hamburguerTradicional = $ProdutosDao->getpProdutosByCategorias("Tradicional");
$hamburguerArtesanal = $ProdutosDao->getpProdutosByCategorias("Artesanal");
$porcao = $ProdutosDao->getpProdutosByCategorias("Porção");
$bebida = $ProdutosDao->getpProdutosByCategorias("bebida");



// Inicialize a variável $hamburgueres como um array vazio
$hamburgueres = [];
?>
<!-- Exemplo de como você pode exibir os itens disponíveis -->
<!-- Exemplo de como você pode exibir os itens disponíveis -->

<div id="main-container" class="container-fluid">
    <h2 class="page-title">Hamburgueres Artesanal </h2>
    <p class="section-description">Veja os hambúrgueres Artesanais</p>
    <div class="movies-container">
        <?php foreach ($hamburguerArtesanal as $produtos) : ?>
            <?php require("template/card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($hamburguerArtesanal) === 0) : ?>
            <p class="empty-list">Ainda não há hambúrgueres artesanais cadastrados!</p>
        <?php endif; ?>
    </div>

    <h2 class="page-title">Hamburgueres Tradicional</h2>
    <p class="section-description">Veja os hambúrgueres Tradicional</p>
    <div class="movies-container">
        <?php foreach ($hamburguerTradicional as $produtos) : ?>
            <?php require("template/card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($hamburguerTradicional) === 0) : ?>
            <p class="empty-list">Ainda não há hambúrgueres Tradicional cadastrados!</p>
        <?php endif; ?>
    </div>


    <h2 class="page-title">Porção </h2>
    <p class="section-description">Veja as Porções Abaixo</p>
    <div class="movies-container">
        <?php foreach ($porcao as $produtos) : ?>
            <?php require("template/porcao.php"); ?>
        <?php endforeach; ?>
        <?php if (count($porcao) === 0) : ?>
            <p class="empty-list">Ainda não há Porção cadastrados!</p>
        <?php endif; ?>
    </div>


    <h2 class="page-title">Bebidas </h2>
    <p class="section-description">Veja as Bebidas </p>
    <div class="movies-container">
        <?php foreach ($bebida as $produtos) : ?>
            <?php require("template/bebida.php"); ?>
        <?php endforeach; ?>
        <?php if (count($bebida) === 0) : ?>
            <p class="empty-list">Ainda não há bebidas cadastrados!</p>
        <?php endif; ?>
    </div>



    <!-- Inclua o código dentro de um script JavaScript -->





</div>
<?php
require_once(__DIR__ . "/template/footer.php");
?>