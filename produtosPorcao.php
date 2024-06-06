<?php

require_once("template/header.php");

// Verifica se o usuário está autenticado
require_once("models/Usuario.php");
require_once("dao/UsuarioDAO.php");
require_once("dao/ProdutosDAO.php");

$usuario = new Usuario();
$usuarioDao = new UsuarioDao($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);
$id = filter_input(INPUT_GET, "id");
$image = filter_input(INPUT_GET, "image");

$produtos;
$adicionais;

$produtoDao = new ProdutosDAO($conn, $BASE_URL);

if (empty($id)) {
  $message->setMessage("O produto não foi encontrado!", "error", "index.php");
} else {
  $produtos = $produtoDao->findByid($id);
  // Verifica se o produto existe
  if (!$produtos) {
    $message->setMessage("O produto não foi encontrado!", "error", "index.php");
  }

  // Busca os adicionais
}

?>
<div id="main-container" class="container-fluidProdutos">
  <div class="card produto-card-produtos">
    <div class="card-img-top-produtos" style="background-image: url('<?= $BASE_URL ?>img/produtos/<?= $produtos->image ?>')"></div>
    <div class="card-body d-flex flex-column justify-content-between">
      <h5 class="card-title"><?= $produtos->nomeProduto ?></h5>
      <p class="card-text">Descrição: <?= $produtos->descricao ?></p>
      <p class="card-text">Ingredientes: <?= $produtos->ingrediente ?></p>
      <p class="card-text">Preço R$ <?= $produtos->preco ?></p>
      <!-- Adicionando o formulário para adicionar ao carrinho -->
      <form class="addToCartForm" action="process/cesta_process.php" method="POST" id="carrinhoForm">
        <!-- Definindo o tipo do item como "carrinhoProdutos" -->
        <input type="hidden" name="type" value="carrinhoProdutos">
        <!-- Passando o ID do produto como parâmetro -->
        <input type="hidden" name="produtos_id" value="<?= $produtos->id ?>">
        <!-- Campos ocultos para enviar quantidade e preço total -->
        <input type="hidden" id="quantity_hidden" name="quantidade" value="0">
        <input type="hidden" id="totalPrice_hidden" name="totalPrice" value="0">
        
        <h5 class="card-title"> Produto e Adicionais </h5>

        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>Preço Unitário</th>
              <th>Quantidade</th>
              <th>Preço Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= $produtos->nomeProduto ?></td>
              <td>R$ <?= $produtos->preco ?></td>
              <td>
                <div class="quantity">
                  <button type="button" onclick="decrement('<?= $produtos->nomeProduto ?>')">-</button>
                  <span id="quantity_<?= $produtos->nomeProduto ?>">0</span>
                  <input type="hidden" id="value_<?= $produtos->nomeProduto ?>" value="<?= $produtos->preco ?>">
                  <button type="button" onclick="increment('<?= $produtos->nomeProduto ?>')">+</button>
                </div>
              </td>
              <td id="totalPrice_<?= $produtos->nomeProduto ?>">R$ 0.00</td>
            </tr>
          </tbody>
        </table>
        <label for="observacao">Observação:</label>
        <textarea id="observacao" name="observacao" rows="5" class="form-control"  placeholder="Faça uma Observação do seu pedido"></textarea>
        <input type="hidden" name="addtoCart">
        <button type="submit" id="addToCartButton" class="card-btn">Adicionar ao Carrinho</button>
      </form>
    </div>
  </div>
</div>
<script src="javascript/produtos.js"></script>
<?php

require_once("template/footer.php");

?>
