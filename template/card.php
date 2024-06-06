<?php
require_once("template/header.php");
require_once("dao/ProdutosDAO.php");

require_once("models/Produtos.php");

$produtoDao = new ProdutosDAO($conn, $BASE_URL);

?>

<div class="card movie-card">
    <div class="card-img-top" style="background-image: url('<?= $BASE_URL ?>img/produtos/<?= $produtos->image ?>')"></div>
    <div class="card-body d-flex flex-column justify-content-between">
        <h5 class="card-title"><?= $produtos->nomeProduto ?></h5>
        <p class="card-text">Descrição: <?= $produtos->descricao ?></p>
        <p class="card-text">Ingredientes: <?= $produtos->ingrediente ?></p>

        <p class="card-text">Preço R$ <?= $produtos->preco ?></p>
        <!-- Adicionando o formulário para adicionar ao carrinho -->
        <form class="addToCartForm" action="cesta_process.php" method="POST">
            <!-- Definindo o tipo do item como "hamburguer" -->
            <input type="hidden" name="item_type" value="produtos">
            <!-- Passando o ID do hambúrguer como parâmetro -->
            <input type="hidden" name="produtos_id" value="<?= $produtos->id ?>">
           
          <a href="<?= $BASE_URL ?>produtos.php?id=<?= $produtos->id ?>" class="card-btn" ><i class='bx bx-cart' ></i>Adicionar ao Carrinho</a>
        </form>
    </div>
</div>
