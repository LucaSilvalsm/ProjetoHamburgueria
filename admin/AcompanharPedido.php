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

// Configurações de paginação
$porPagina = 10; // Define o número de pedidos por página
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $porPagina;

// Busca os pedidos para a página atual
$todosPedidos = $pedidosDAO->findAllPaginated($offset, $porPagina);

// Verifica se há mais pedidos para outra página
$maisPedidos = count($todosPedidos) >= $porPagina;

// Calcula o número total de páginas
$totalPaginas = ceil($pedidosDAO->getTotalPedidos() / $porPagina);

// Define o link para a página anterior
$paginaAnterior = $paginaAtual - 1;

// Define o link para a próxima página
$proximaPagina = $paginaAtual + 1;

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
    <div id="dashboard-container" class="dashboard-fluid">
        <h2 class="dashboard-title">Pedidos</h2>
        <p class="dashboard-description">Confira aqui</p>
        <div class="col-md-12" id="add-carrinho-container"></div>
    </div>
    <!-- Tabela de pedidos -->
    <div class="col-md-12" id="carrinho-dashboard">
        <table class="table">
            <!-- Cabeçalho da tabela -->
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Observação</th>
                    <th scope="col">Status</th>
                    <th scope="col">Total</th>
                    <th scope="col" nowrap>Forma de Pagamento</th>
                    <th scope="col" class="actions-column">Ações</th>
                </tr>
            </thead>
            <!-- Corpo da tabela -->
            <tbody>
                <?php foreach ($todosPedidos as $pedido) : ?>
                    <tr>
                        <td style="vertical-align: middle;" scope="row"><?= $pedido->id ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->itens_comprados ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->observacao ?></td>
                        <td style="vertical-align: middle;">
                            <select name="status_pedido" class="status-select" data-pedido-id="<?= $pedido->id ?>">
                                <option value="" <?= ($pedido->status == '') ? 'selected' : '' ?>><?= $pedido->status ?></option>
                                <option value="Saiu para entrega" <?= ($pedido->status == 'saiu_para_entrega') ? 'selected' : '' ?>>Saiu para Entrega</option>
                                <option value="Entregue" <?= ($pedido->status == 'entregue') ? 'selected' : '' ?>>Entregue</option>
                            </select>
                        </td>
                        <td style="vertical-align: middle;" nowrap>R$ <?= $pedido->valor_total ?></td>
                        <td style="vertical-align: middle;"><?= $pedido->forma_pagamento ?></td>
                        <td class="action">
                            <!--Editar-->
                            <a href="#" class="edit-link" data-pedido-id="<?= $pedido->id ?>"><i class="fas fa-edit edit-icon"></i></a>

                            <input type="hidden" name="id" value="<?= $pedido->id ?>">

                            <form class="delete-form" action="<?= $BASE_URL ?>editar.php<?= $pedido->id ?>" method="POST">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $pedido->id ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-times delete-icon"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Botões de navegação -->
        <div class="pagination">
            <?php if ($paginaAtual > 1) : ?>
                <a href="?pagina=<?= $paginaAnterior ?>" class="btn btn-primary">Voltar</a>
            <?php endif; ?>
            <?php if ($maisPedidos) : ?>
                <a href="?pagina=<?= $proximaPagina ?>" class="btn btn-primary">Próxima</a>
            <?php endif; ?>
        </div>
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
