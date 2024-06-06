<?php
require_once(__DIR__ . "/../dao/PedidosDAO.php");
require_once(__DIR__ . "/../models/Pedidos.php");
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");

$pedidosDAO = new PedidosDAO($conn, $BASE_URL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = filter_input(INPUT_POST, "status_pedido");
    $pedidoId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT); // Obtém o ID do pedido da URL
    if ($pedidoId) {
        $pedido = new Pedidos(); // Cria um novo objeto Pedido
        $pedido->id = $pedidoId; // Define o ID do pedido
        $pedido->status = $status; // Define o novo status do pedido
        $atualizadoComSucesso = $pedidosDAO->updatePedidos($pedido); // Atualiza o status do pedido
        if ($atualizadoComSucesso) {
            echo json_encode(["message" => "O status do pedido foi atualizado com sucesso!"]);
        } else {
            echo json_encode(["message" => "Falha ao atualizar o status do pedido."]);
        }
    } else {
        echo json_encode(["message" => "ID do pedido não fornecido."]);
    }
}

?>
