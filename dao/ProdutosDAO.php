
<?php

require_once(__DIR__ . "/../models/Produtos.php");
require_once(__DIR__ . "/../models/Message.php");

class ProdutosDAO implements ProdutosDAOInterface
{

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }




    public function buildProdutos($data)
    { // para criar o objeto hamburguer $usuario->id = $data["id"];

        $produtos = new Produtos();
        $produtos->id = $data["id"];
        $produtos->nomeProduto = $data["nomeProduto"];
        $produtos->tipoProdutos  = $data["tipoProdutos"];
        $produtos->tamanho = $data["tamanho"];
        $produtos->ingrediente = $data["ingrediente"];
        $produtos->preco = $data["preco"];
        $produtos->descricao = $data["descricao"];
        $produtos->image = $data["image"];
        return $produtos;
    }

    public function findAll()
    {
        $produtos = [];
        try {
            $stmt = $this->conn->prepare("SELECT * FROM produtos");
            $stmt->execute();

            // Use fetch() dentro de um loop para evitar consumo excessivo de memória
            while ($produtosData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produtos[] = $this->buildProdutos($produtosData);
            }
        } catch (PDOException $e) {
            // Gerenciar exceções, por exemplo, registrando logs ou lançando exceções personalizadas
            // Aqui, estou apenas lançando novamente a exceção, mas você pode fazer mais
            throw $e;
        }
        return $produtos;
    }


    public function create(Produtos $produtos)
    { // para criar o hambúrguer
        // Convertendo o array de ingredientes em uma string separada por vírgulas
        $ingredientes = implode(" - ", $produtos->ingrediente);
        $tamanho = implode(", ", $produtos->tamanho);

        $stmt = $this->conn->prepare("INSERT INTO produtos (nomeProduto, tipoProdutos, tamanho, ingrediente, preco, descricao, image) 
            VALUES (:nomeProduto, :tipoProdutos, :tamanho, :ingrediente, :preco, :descricao, :image)");

        $stmt->bindParam(":nomeProduto", $produtos->nomeProduto);
        $stmt->bindParam(":tipoProdutos", $produtos->tipoProdutos);
        $stmt->bindParam(":tamanho", $tamanho); // Bind o tamanho como string

        $stmt->bindParam(":ingrediente", $ingredientes); // Passando a string de ingredientes
        $stmt->bindParam(":preco", $produtos->preco);
        $stmt->bindParam(":descricao", $produtos->descricao);
        $stmt->bindParam(":image", $produtos->image);

        $stmt->execute();

        // mensagem de sucesso 
        $this->message->setMessage("Produto adicionado com sucesso!", "success", "../admin/newproduct.php");
    }


    public function update(Produtos $produtos)
    { // para localizar o hamburguer 

    }
    public function findByid($id)
    {
        $produtos = []; // Inicialize como um array vazio

        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Não há necessidade de usar fetchAll(), pois estamos apenas recuperando uma única linha
            $produtosData = $stmt->fetch(PDO::FETCH_ASSOC);
            $produtos = $this->buildProdutos($produtosData); // Adicione a instância do produtos diretamente ao array
        }

        return $produtos;
    }


    public function getpProdutosByCategorias($tipoProdutos)
    {
        $produtos = [];
        $stmt = $this->conn->prepare("SELECT * FROM produtos 
                                          WHERE tipoProdutos = :tipoProdutos 
                                          ORDER BY id DESC");

        $stmt->bindParam(":tipoProdutos", $tipoProdutos);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $produtoArray = $stmt->fetchAll();
            foreach ($produtoArray as $row) {
                $produtos[] = $this->buildProdutos($row);
            }
        }

        return $produtos;
    }


    public function getAllProdutosGroupedByCategoria()
    {
        $produtosByCategoria = [];

        // Obtém todas as categorias de produtos
        $categorias = ["Artesanal", "Tradicional", "Bebida", "Porção", "Sobremesa"];

        // Organiza os produtos por categoria
        foreach ($categorias as $categoria) {
            $produtosByCategoria[$categoria] = $this->getpProdutosByCategorias($categoria);
        }

        return $produtosByCategoria;
    }

    public function destroy($id)
    {
        // Iniciar uma transação
       
            // Preparando a consulta SQL para excluir o item do carrinho com base no ID
            $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id = :id");
            $stmt->bindParam(":id", $id);
            
            // Executando a consulta preparada
            $stmt->execute();
                 // Definir a mensagem de sucesso se o item do carrinho for removido com sucesso
             $this->message->setMessage("Produto removido com sucesso!", "success", "../admin/produtos.php");
    }
}
