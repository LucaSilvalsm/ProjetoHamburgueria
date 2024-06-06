<?php
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Message.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/CarrinhoDAO.php");
require_once(__DIR__ . "/../models/Carrinho.php");













// Verifica se usuário está autenticado




// Instanciando objetos
$usuarioDao = new UsuarioDao($conn, $BASE_URL);
$produtoDao = new ProdutosDAO($conn, $BASE_URL);
$carrinhoDao = new CarrinhoDAO($conn, $BASE_URL);

// Obtendo ID do usuário da sessão
$id = filter_input(INPUT_GET, "id");
$type = filter_input(INPUT_GET, "type");

// Obtendo dados do usuário
$usuarioData = $usuarioDao->verifyToken(true);
$usuario_id = $usuarioData->id;

// Verificando se é uma requisição de adicionar ao carrinho
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["type"]) && $_POST["type"] === "carrinhoProdutos") {
    // Obtendo dados do formulário
    $produto_id = filter_input(INPUT_POST, "produtos_id", FILTER_VALIDATE_INT);
    $quantidade = filter_input(INPUT_POST, "quantidade", FILTER_VALIDATE_INT);
    $observacao = filter_input(INPUT_POST, "observacao");
    $totalPrice = filter_input(INPUT_POST, "totalPrice", FILTER_VALIDATE_FLOAT); // Recuperando o valor de totalPrice

    // Verifica se os dados do formulário são válidos
    if ($produto_id && $quantidade !== false && $totalPrice !== false && $totalPrice > 0) {
        // Criando objeto Carrinho
        $carrinho = new Carrinho();
        $carrinho->usuario_id = $usuario_id;
        $carrinho->produtos_id = $produto_id;
        $carrinho->quantidade = $quantidade;
        $carrinho->observacao = $observacao;
        $carrinho->precoTotal = $totalPrice;

        // Chama a função create do CarrinhoDAO
        $carrinhoDao->create($carrinho);
        // Agora você pode fazer o que quiser com o objeto $carrinho, como salvar no banco de dados
    } else {
        // Tratar erros de validação dos dados do formulário
        echo "Erro: Dados inválidos no formulário.";
    }
} elseif ($type === "carrinhoAdicional") {
    // Remover o item do carrinho
   echo "O codigo caiu aqui no Adicional";
}elseif ($type === "delete") {
    // Remover o item do carrinho
    $carrinho = $carrinhoDao->delete($id);
}
?>
