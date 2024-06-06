<?php
require_once("header.php");
require_once(__DIR__ . "/../dao/PedidosDAO.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/ProdutosDAO.php");

require_once(__DIR__ . "/../models/Usuario.php");


require_once(__DIR__ . "/../models/Pedidos.php");
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");

$ProdutosDao = new ProdutosDAO($conn, $BASE_URL);
$produtosByCategoria = $ProdutosDao->getAllProdutosGroupedByCategoria();


// Verifique se há uma mensagem para exibir

?>

<div id="main-container" class="container-fluid">
    <h2 class="page-title">Produtos</h2>
    <strado class="section-description">Confira aqui os produtos cadastrados</p>
        <div class="col-md-12" id="add-carrinho-container">

        </div>
        <div class="col-md-12" id="carrinho-dashboard" style="overflow-y: auto; max-height: 800px;">
            <table class="table">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Imagem</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Tipo do Produto</th>

                    <th scope="col">Preço</th>

                    <th scope="col" class="actions-column">Ações</th>
                </thead>
                <tbody>
                    <?php foreach ($produtosByCategoria as $categoria => $produtos) : ?>
                        <?php foreach ($produtos as $produto) : ?>
                            <tr>
                                <td style="vertical-align: middle;" scope="row"><?= $produto->id ?></td>
                                <td>
                                    <div class="card-img-top-cesta" style="background-image: url('<?= $BASE_URL ?>../img/produtos/<?= $produto->image ?>')"></div>
                                </td>
                                <td style="vertical-align: middle;"><?= $produto->nomeProduto ?></td>
                                <td style="vertical-align: middle;"><?= $categoria ?></td>

                                <td style="vertical-align: middle;">R$ <?= $produto->preco ?></td>

                                <td style="vertical-align: middle;" class="actions-column">
                                    <form class="delete-form" action="<?= $BASE_URL ?>../process/hamburguer_process.php?type=delete&id=<?= $produto->id ?>" method="POST">
                                        <input type="hidden" name="type" value="deleteProduto">
                                        <input type="hidden" name="id" value="<?= $produto->id ?>">
                                        <button type="submit" class="delete-btn"><i class="fas fa-times delete-icon"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="7">

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</div>

<?php require_once("footer.php"); ?>