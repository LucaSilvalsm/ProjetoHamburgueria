<?php

// Incluindo os arquivos necessários
require_once(__DIR__ . "/../config/db.php");

require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../models/Usuario.php");

require_once(__DIR__ . "/../models/Produtos.php");

require_once(__DIR__ . "/../models/Carrinho.php");
require_once(__DIR__ . "/../models/Message.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");
require_once(__DIR__ . "/../dao/ProdutosDAO.php");

require_once(__DIR__ . "/../dao/CarrinhoDAO.php");
require_once(__DIR__ . "/../dao/PedidosDAO.php");


// Instanciando a classe de mensagens
$message = new Message($BASE_URL);

// Instanciando os DAOs necessários
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$produtoDao = new ProdutosDAO($conn, $BASE_URL);
$id = filter_input(INPUT_GET, "id");

// Verificando se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificando se o tipo é "criar"
    $type = filter_input(INPUT_POST, "type");
    if ($type === "criar") {
        // Recebendo os dados do formulário
        $nomeProduto = trim($_POST["nome"]);
        $tipoProduto = trim($_POST["tipoProdutos"]);
        $tamanho = isset($_POST["tamanho"]) ? $_POST["tamanho"] : [];
        $ingredientes = isset($_POST["ingrediente"]) ? $_POST["ingrediente"] : [];
        $preco = trim($_POST["preco"]);
        $descricao = trim($_POST["descricao"]);

        // Verificando se todos os campos obrigatórios foram preenchidos
        if (!empty($nomeProduto) && !empty($tipoProduto) && !empty($preco) && !empty($descricao)) {
            // Criando um objeto Produtos com os dados recebidos
            $produtos = new Produtos();
            $produtos->nomeProduto = $nomeProduto;
            $produtos->tipoProdutos = $tipoProduto;
            $produtos->tamanho = $tamanho;
            $produtos->ingrediente = $ingredientes;
            $produtos->preco = $preco;
            $produtos->descricao = $descricao;

            // Salvando a imagem, se foi enviada
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                // Verificando se é uma imagem válida
                $allowedTypes = ["image/jpeg", "image/png"];
                $fileType = $_FILES["image"]["type"];
                if (in_array($fileType, $allowedTypes)) {
                    $imagePath = "../img/produtos/";
                    $imageName = uniqid() . "-" . $_FILES["image"]["name"];
                    $imageFullPath = $imagePath . $imageName;
                    // Movendo a imagem para o diretório correto
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageFullPath)) {
                        $produtos->image = $imageName;
                    } else {
                        $message->setMessage("Falha ao fazer upload da imagem", "error", "back");
                    }
                } else {
                    $message->setMessage("Formato de imagem inválido. Apenas JPEG e PNG são permitidos.", "error", "back");
                }
            }

            // Salvando o produto no banco de dados
            $produtoDao->create($produtos);

            // Mensagem de sucesso
            $message->setMessage("Produto adicionado com sucesso!", "success", "../admin/newproduct.php");


        } else {
            // Mensagem de erro se algum campo obrigatório estiver vazio
            $message->setMessage("Todos os campos são obrigatórios. Por favor, preencha-os e tente novamente.", "error", "back");
        }
    } elseif ($type === "deleteProduto"){
        $produtoDao->destroy($id);
    }
}


