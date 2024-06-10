<?php
require_once("config/globals.php");
require_once("config/db.php");
require_once("template/header.php");
require_once("dao/ProdutosDAO.php");
require_once("dao/CarrinhoDAO.php");
require_once("models/Carrinho.php");

$usuario = new Usuario();
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$carrinhoDao = new CarrinhoDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);

$usuarioCarrinho = $carrinhoDao->getCarrinhoByUserId($usuarioData->id);

// Inicialize um array para armazenar os objetos carrinho
$objetosCarrinho = [];

// Calcula o total do pedido
$totalPedido = 0;

foreach ($usuarioCarrinho as $carrinho) {
    // Extrair os dados de cada objeto do carrinho
    $id = $carrinho->id;
    $imageProduto = $carrinho->imageProduto;
    $nomeProduto = $carrinho->nomeProduto;
    $quantidade = $carrinho->quantidade;
    $observacao = $carrinho->observacao;
    $precoTotal = $carrinho->precoTotal;

    // Criar um novo objeto com os dados extraídos
    $novoObjetoCarrinho = (object) [
        'id' => $id,
        'imageProduto' => $imageProduto,
        'nomeProduto' => $nomeProduto,
        'quantidade' => $quantidade,
        'observacao' => $observacao,
        'precoTotal' => $precoTotal
    ];

    // Adicionar o novo objeto ao array
    $objetosCarrinho[] = $novoObjetoCarrinho;

    // Somar ao total do pedido
    $totalPedido += $precoTotal;
}

// Formatar o total do pedido
$totalPedidoFormatado = number_format($totalPedido, 2, ',', '.');

// Armazenar o array de objetos carrinho na sessão
$_SESSION['objetosCarrinho'] = $objetosCarrinho;

// Armazenar o total do pedido formatado na sessão
$_SESSION['totalPedidoFormatado'] = $totalPedidoFormatado;

?>



<div id="main-container" class="container-fluid">
    <h2 class="page-title">Carrinho</h2>
    <p class="section-description">Confira aqui o Carrinho</p>
    <div class="col-md-12" id="add-carrinho-container">

    </div>
    <div class="col-md-12" id="carrinho-dashboard">
        <table class="table">
            <thead>
                <th scope="col">#</th>
                <th scope="col">Imagem</th>
                <th scope="col">Nome</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Observação</th>
                <th scope="col">Total</th>
                <th scope="col" class="actions-column">Ações</th>
            </thead>
            <tbody>
                <?php foreach ($usuarioCarrinho as $carrinho) : ?>
                    <tr>
                        <td style="vertical-align: middle;" scope="row"><?= $carrinho->id ?></td>
                        <td>
                            <div class="card-img-top-cesta" style="background-image: url('<?= $BASE_URL ?>img/produtos/<?= $carrinho->imageProduto ?>')"></div>
                        </td>
                        <td style="vertical-align: middle;"><?= $carrinho->nomeProduto ?></td>
                        <td style="vertical-align: middle;"><?= $carrinho->quantidade ?></td>
                        <td style="vertical-align: middle;"><?= $carrinho->observacao ?></td>
                        <td style="vertical-align: middle;">R$ <?= $carrinho->precoTotal ?></td>
                        <td style="vertical-align: middle;" class="actions-column">
                            <form class="delete-form" action="<?= $BASE_URL ?>process/cesta_process.php?type=delete&id=<?= $carrinho->id ?>" method="POST">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?= $carrinho->id ?>">
                                <button type="submit" class="delete-btn"><i class="fas fa-times delete-icon"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                 <!-- Tabela aninhada para endereço, complemento e telefone -->
                 <tr>
                        <td colspan="7">
                            <table class="table">
                                <tr>
                                    <th scope="col">Endereço: </th>
                                    <th scope="col">Complemento : </th>
                                    <th scope="col">Numero da Casa: </th>
                                    <th scope="col">Telefone de Contato</th>
                                </tr>
                                <tr>
                                    <td><?= $usuarioData->endereco ?></td>
                                    <td><?= $usuarioData->complemento ?></td>
                                    <td><?= $usuarioData->numeroCasa ?></td>
                                    <td><?= $usuarioData->telefone ?></td>
                                </tr>
                            </table>
                

                <!-- Nova linha para o botão "Criar Pedido" -->
                <tr>
    <td colspan="7">
        <form action="<?= $BASE_URL ?>process/pedidos_process.php" method="POST">
            <input type="hidden" name="type" value="criarPedido">

            <!-- Label "Forma de Pagamento" com pequeno espaço à esquerda -->
            <div class="col-md-3">
                <label for="formaPagamento" class="form-label">Forma de Pagamento:</label>
            </div>

            <!-- Menu suspenso (select) ocupando a mesma largura -->
            <div class="col-md-12">
                <select id="formaPagamento" name="formaPagamento" class="form-select">
                    <option value="CARTAO">Cartão</option>
                    <option value="PIX">PIX</option>
                    <option value="DINHEIRO">Dinheiro</option>
                </select>
            </div>

            <!-- Campo para inserir o valor em dinheiro (oculto inicialmente) -->
            <div id="campoDinheiro" class="col-md-12" style="display: none;">
                <label for="valorDinheiro">Valor em dinheiro:</label>
                <input type="number" id="valorDinheiro" name="valorDinheiro" class="form-control" step="0.01">
                <h3 id="trocoMsg" class="card-btn">O troco será calculado automaticamente</h3>
            </div>

            <!-- Botão para deletar o pedido -->
            <div class="col-md-12">
                <button id="pedido-btn" type="submit" class="delete-btn card-btn">
                    <i class='bx bx-cart'></i> Criar Pedido por R$ <?= $totalPedidoFormatado ?>
                </button>
            </div>
        </form>
    </td>
</tr>
</tbody>
</table>
</div>
</div>
<script src="javascript/cesta.js"></script>
<script>
    // Função para mostrar ou ocultar o campo de valor em dinheiro dependendo da forma de pagamento selecionada
    document.getElementById('formaPagamento').addEventListener('change', function() {
        var formaPagamento = this.value;
        if (formaPagamento === 'DINHEIRO') { // Corrigido para verificar a opção "DINHEIRO" em maiúsculas
            document.getElementById('campoDinheiro').style.display = 'block';
        } else {
            document.getElementById('campoDinheiro').style.display = 'none';
        }
    });

    // Evento de entrada para calcular o troco
    document.getElementById('valorDinheiro').addEventListener('input', function() {
        // Obter o valor em dinheiro fornecido pelo usuário
        var valorDinheiro = parseFloat(this.value);

        // Obter o valor total do pedido
        var totalPedido = <?= $totalPedido; ?>;

        // Calcular o troco
        var troco = valorDinheiro - totalPedido;

        // Atualizar a mensagem de troco
        document.getElementById('trocoMsg').innerText = "O troco é R$ " + troco.toFixed(2);
    });
</script>
<?php
require_once("template/footer.php");
?>