<?php

require_once("config/globals.php");
require_once("config/db.php");
require_once("template/header.php");
require_once("dao/ProdutosDAO.php");
require_once("dao/PedidosDAO.php");
require_once("dao/CarrinhoDAO.php");
require_once("models/Carrinho.php");
require_once("models/Pedidos.php");

$usuario = new Usuario();
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$carrinhoDao = new CarrinhoDAO($conn, $BASE_URL);
$pedidosDAO = new PedidosDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);
$usuarioPedidos = $pedidosDAO->getPedidosByUsuarioId($usuarioData->id);

?>

<div id="main-container" class="container-fluid">
    <h2 class="page-title">Pedidos</h2>
    <p class="section-description">Confira aqui</p>
    <div class="col-md-12" id="add-carrinho-container">

    </div>
    <div class="col-md-12" id="carrinho-dashboard">
        <table class="table">
            <thead>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                
                <th scope="col">Observação</th>
                <th scope="col">Status</th>
                <th scope="col">Total</th>
                <th scope="col" nowrap>Forma de Pagamento</th>
                
            </thead>
            <tbody>
                <?php foreach ($usuarioPedidos as $pedido) : ?>
                    <tr>
                        <td style="vertical-align: middle;" scope="row"><?= $pedido->id ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->itens_comprados ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->observacao ?></td>
                        <td style="vertical-align: middle;" nowrap><?= $pedido->status ?></td>
                        <td style="vertical-align: middle;" nowrap>R$ <?= $pedido->valor_total ?></td>
                        <td style="vertical-align: middle;" class="actions-column">
                        <?= $pedido->forma_pagamento ?>
                           
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once("template/footer.php"); ?>
