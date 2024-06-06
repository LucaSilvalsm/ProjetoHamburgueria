<?php
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Pedidos.php");
require_once(__DIR__ . "/../models/Carrinho.php");
require_once(__DIR__ . "/../models/Message.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/CarrinhoDAO.php");
require_once(__DIR__ . "/../dao/PedidosDAO.php");

$usuario = new Usuario();
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$carrinhoDao = new CarrinhoDAO($conn, $BASE_URL);
$pedidosDAO = new PedidosDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);

$usuarioCarrinho = $carrinhoDao->getCarrinhoByUserId($usuarioData->id);

$fullEndereco = $usuario->getfullEndereco($usuarioData);
$usuario_id = $usuarioData->id;

// Função para formatar os itens comprados

// Inicie a sessão para acessar as variáveis de sessão

// Verifique se os dados do carrinho estão na sessão e se o total do pedido formatado está definido
$observacao = ""; // Inicialize uma string vazia para armazenar as observações
$comprados = " "; // Inicialize uma string vazia para armazenar os item comprados
// Verifique se os dados do carrinho estão na sessão e se o total do pedido formatado está definido
if (isset($_SESSION['objetosCarrinho']) && isset($_SESSION['totalPedidoFormatado'])) {
    // Recupere os objetos carrinho da sessão
    $objetosCarrinho = $_SESSION['objetosCarrinho'];
    $totalPedidoFormatado = $_SESSION['totalPedidoFormatado'];
    $objetosCarrinho = $_SESSION['objetosCarrinho'];

    // Percorra o array $objetosCarrinho e concatene as observações
    foreach ($objetosCarrinho as $item) {
        // Concatene a observação do item atual com as observações anteriores, separadas por uma quebra de linha
        $observacao .= $item->nomeProduto . " " . "- " . "Observação - " . $item->observacao . "<br>";
    }
    foreach ($objetosCarrinho as $item) {
        // Concatene a observação do item atual com as observações anteriores, separadas por uma quebra de linha
        $comprados .= $item->nomeProduto . " " . "- " . "Quantidade - " . $item->quantidade . "<br>";
    }

    // Faça o que você precisa com os objetos carrinho e o total do pedido formatado
    // Exiba os objetos carrinho
    // Exiba o total do pedido formatado

} else {
    // Se os dados do carrinho não estiverem na sessão, lide com isso de acordo
    echo "Os dados do carrinho não foram encontrados na sessão.";
}
$type = filter_input(INPUT_POST, "type");

if ($type === "criarPedido") {
    $forma_pagamento = filter_input(INPUT_POST, "formaPagamento");
    $valor_total = $totalPedidoFormatado;
    $endereco_entrega = $fullEndereco;
    $status = "Preparando o Pedido";
    $observacoes = "";

    if ($forma_pagamento && $valor_total !== false && $valor_total > 0) {
        $pedidos = new Pedidos();
        $pedidos->id = $usuario_id;
        $pedidos->forma_pagamento = $forma_pagamento;
        $pedidos->status = $status;
        $pedidos->observacao = $observacao;
        $pedidos->endereco_entrega = $fullEndereco;
        $pedidos->itens_comprados = $comprados;
        $pedidos->valor_total = $valor_total;
        print_r($observacao);

        $pedidosDAO->createAndDeleteCarrinho($pedidos, $usuario_id);
    } else {
        echo "Dados Inválidos";
    }
}

