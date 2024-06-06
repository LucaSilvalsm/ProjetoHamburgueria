<?php
require_once("header.php");

require_once(__DIR__ . "/../dao/PedidosDAO.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/CarrinhoDAO.php");
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Pedidos.php");
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");


// Inicialização de objetos e recuperação de dados
$usuario = new Usuario();
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$carrinhoDao = new CarrinhoDAO($conn, $BASE_URL);
$pedidosDAO = new PedidosDAO($conn, $BASE_URL);
$usuarioData = $usuarioDao->verifyToken(true);
$usuarioPedidos = $pedidosDAO->getValorTotalPedidos($usuarioData->id);
$totalPedido = $pedidosDAO->getTotalPedidos();
$mediaPedido = $pedidosDAO->getMediaPedidos($usuarioPedidos, $totalPedido);
$todosPedidos = $pedidosDAO->findALL();

// Atualização do status do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidoId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT); // Obtém o ID do pedido do corpo da solicitação POST
    $status = filter_input(INPUT_POST, "status_pedido");

    if ($pedidoId && $status) {
        $pedido = new Pedidos();
        $pedido->id = $pedidoId;
        $pedido->status = $status;

        $atualizadoComSucesso = $pedidosDAO->updatePedidos($pedido);

        if ($atualizadoComSucesso) {
            echo json_encode(["message" => "O status do pedido foi atualizado com sucesso!"]);
            exit; // Termina a execução do script PHP após a atualização do status do pedido
        } else {
            echo json_encode(["message" => "Falha ao atualizar o status do pedido."]);
            exit; // Termina a execução do script PHP após a falha na atualização do status do pedido
        }
    } else {
        echo json_encode(["message" => "Dados incompletos fornecidos."]);
        exit; // Termina a execução do script PHP após dados incompletos fornecidos
    }
}
?>

<div class="content">
    <div class="titulo-secao">
        <h2>Dashboard</h2>
        <br />
        <hr>
        <p><i class="fa-solid fa-house"></i> / Dashboard do Projeto </p>
    </div> <!--FIM Titulo-Secação=-->
    <div class="box-info">
        <div class="box-info-single" id="vendas">
            <div class="info-text">
                <h3>Total Vendas</h3>
                <p> R$ <?= $usuarioPedidos ?></p>
            </div>
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <div class="box-info-single" id="pedidos">
            <div class="info-text">
                <h3>Total Pedidos</h3>
                <p>Total de pedidos <?= $totalPedido ?></p>
            </div>
            <i class="fa-solid fa-store"></i>
        </div>
        <div class="box-info-single" id="lucros">
            <div class="info-text">
                <h3>Total Lucro</h3>
                <p>R$ 1.500,00</p>
            </div>
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <div class="box-info-single" id="media">
            <div class="info-text">
                <h3>Ticket Medio  </h3>
                <p>O Ticket Medio é R$ <?= $mediaPedido ?></p>
            </div>
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
    </div>
    <div id="dashboard-container" class="dashboard-fluid">
        <h2 class="dashboard-title">Pedidos</h2>
        <p class="dashboard-description">Confira aqui</p>
        <div class="col-md-12" id="add-carrinho-container"></div>
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
                <th scope="col" class="actions-column">Ações</th>
            </thead>
            <tbody>
                <?php foreach ($todosPedidos as $pedido) : ?>
                    <tr>
                        <td style="vertical-align: middle;" scope="row"><?= $pedido->id ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->itens_comprados ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->observacao ?></td>
                        <td style="vertical-align: middle;">
                            <select name="status_pedido" class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                <option value="" <?= ($pedido->status == '') ? 'selected' : '' ?>><?=$pedido->status?></option>
                                <option value="Saiu para entrega" <?= ($pedido->status == 'saiu_para_entrega') ? 'selected' : '' ?>>Saiu para Entrega</option>
                                <option value="Entregue" <?= ($pedido->status == 'entregue') ? 'selected' : '' ?>>Entregue</option>
                            </select>
                        </td>
                        <td style="vertical-align: middle;" nowrap>R$ <?= $pedido->valor_total ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->forma_pagamento ?></td>
                        <td  style="vertical-align: middle;"class="action"> <!--Editar-->
                            <a href="#" class="edit-link" data-pedido-id="<?= $pedido->id ?>"><i class="fas fa-edit edit-icon"></i></a>

                            <input type="hidden" name="id" value="<?= $pedido->id ?>">

                            <form  class="delete-form" action="<?= $BASE_URL ?>editar.php<?= $pedido->id ?>" method="POST">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $pedido->id ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-times delete-icon"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> <!--FIM Content-->

</section> <!--MAIN-->

<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function(event) {
            const pedidoId = this.getAttribute('data-pedido-id');
            const novoStatus = this.value;

            fetch("dashboard.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${pedidoId}&status_pedido=${novoStatus}`
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Falha ao atualizar o status do pedido.');
                    }
                })
                .then(data => {
                    // Recarrega a página após a atualização do status do pedido
                    location.reload();
                })
                .catch(error => {
                    console.error(error.message);
                });
        });
    });
</script>


<?php require_once("footer.php"); ?>
