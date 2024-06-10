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

// Inicialize as strings de comprados e observações
$comprados = "";
$observacao = "";

// Verifique se os dados do carrinho estão na sessão e se o total do pedido formatado está definido
if (isset($_SESSION['objetosCarrinho']) && isset($_SESSION['totalPedidoFormatado'])) {
    // Recupere os objetos carrinho da sessão
    $objetosCarrinho = $_SESSION['objetosCarrinho'];
    $totalPedidoFormatado = $_SESSION['totalPedidoFormatado'];

    // Percorra os objetos carrinho para montar as strings de comprados e observações
    foreach ($objetosCarrinho as $item) {
        // Concatene os detalhes de comprados e observações
        $comprados .= $item->nomeProduto . " - Quantidade: " . $item->quantidade . "\n";
        $observacao .= $item->nomeProduto . " - Observação: " . $item->observacao . "\n";
    }
} else {
    // Se os dados do carrinho não estiverem na sessão, lide com isso de acordo
    echo "Os dados do carrinho não foram encontrados na sessão.";
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifique se o campo "type" está definido no array $_POST
    if (isset($_POST["type"])) {
        // Atribua o valor do campo "type" à variável $type
        $type = $_POST["type"];

        // Se o tipo for "criarPedido", processe o pedido
        if ($type === "criarPedido") {
            $forma_pagamento = filter_input(INPUT_POST, "formaPagamento");
            $valor_total = $totalPedidoFormatado;
            $endereco_entrega = $fullEndereco;
            $status = "Preparando o Pedido";
            $observacoes = "";

            // Verifique se os dados do pagamento são válidos
            if ($forma_pagamento && $valor_total !== false && $valor_total > 0) {
                // Crie um novo objeto de pedido
                $pedidos = new Pedidos();
                $pedidos->id = $usuario_id;
                $pedidos->forma_pagamento = $forma_pagamento;
                $pedidos->status = $status;
                $pedidos->observacao = $observacao;
                $pedidos->endereco_entrega = $fullEndereco;
                $pedidos->itens_comprados = $comprados;
                $pedidos->valor_total = $valor_total;

                // Obtenha o ID do pedido recém-inserido
                 // Obter o próximo ID de pedido
                 $stmt = $conn->query("SELECT MAX(id) AS max_id FROM pedidos");
                 $row = $stmt->fetch(PDO::FETCH_ASSOC);
                 $next_id = $row['max_id'] + 1;
                // Enviar detalhes do pedido via WhatsApp
                $mensagem_pedido = "Detalhes do Pedido: Codigo do Pedido # $next_id \n\n";
                $mensagem_pedido .= "Forma de Pagamento: $forma_pagamento\n\n";
                $mensagem_pedido .= "Endereço de Entrega: $endereco_entrega\n\n";
                $mensagem_pedido .= "Itens Comprados:\n\n$comprados\n\n";
                $mensagem_pedido .= "Observação:\n\n$observacao\n\n";
                $mensagem_pedido .= "Valor Total: R$ $valor_total";

                $params = array(
                    'token' => 'ule8fs00azwdn5tg',
                    'to' => '+5521979844840', // Substitua pelo número do usuário ou o número de destino desejado
                    'body' => $mensagem_pedido
                );

                // Inicie a sessão cURL e envie a mensagem via WhatsApp
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.ultramsg.com/instance87701/messages/chat",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => http_build_query($params),
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "Erro ao enviar mensagem via WhatsApp: " . $err;
                } else {
                    echo "Pedido criado com sucesso e detalhes enviados via WhatsApp!";
                }

                // Crie o pedido no banco de dados e exclua os itens do carrinho
                $pedidosDAO->createAndDeleteCarrinho($pedidos, $usuario_id);
            } else {
                echo "Dados Inválidos";
            }
        }
    }
}
