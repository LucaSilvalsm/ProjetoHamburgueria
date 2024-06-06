<?php
require_once(__DIR__ . "/../models/Carrinho.php");
require_once(__DIR__ . "/../models/Message.php");
require_once(__DIR__ . "/../dao/ProdutosDAO.php");

require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");











class CarrinhoDAO implements CarrinhoDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildCarrinho($data) { 
        $carrinho = new Carrinho();
        $stmt = $this->conn->prepare("SELECT nomeProduto, image FROM produtos WHERE id = :produtos_id");
        $stmt->bindParam(":produtos_id", $data["produtos_id"]);
        $stmt->execute();
        $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $carrinho->id = $data["id"];
        $carrinho->usuario_id = $data["usuario_id"];            
        $carrinho->produtos_id = $data["produtos_id"];
        $carrinho->quantidade = $data["quantidade"];
        $carrinho->observacao = $data["observacao"];
        $carrinho->precoTotal = $data["precoTotal"];
        
        // Adicione as propriedades nomeProduto e image ao objeto Carrinho
        $carrinho->nomeProduto = $produtoData['nomeProduto'];
        $carrinho->imageProduto = $produtoData['image'];
        
        return $carrinho;
    }
    
    
    public function findAll() {
        $carrinhos = []; 
        $stmt = $this->conn->prepare("SELECT * FROM carrinho");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $carrinhoDataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($carrinhoDataArray as $carrinhoData) {
                $carrinhos[] = $this->buildCarrinho($carrinhoData);
            }
        }
        return $carrinhos;
    }
    
    public function create(Carrinho $carrinho) {
        // Primeiro, vamos buscar o nome e a imagem do produto com base no produtos_id
        $stmt = $this->conn->prepare("SELECT nomeProduto, image FROM produtos WHERE id = :produtos_id");
        $stmt->bindParam(":produtos_id", $carrinho->produtos_id);
        $stmt->execute();
        $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);
        $nomeProduto = $produtoData['nomeProduto'];
        $imageProduto = $produtoData['image'];
    
        // Agora podemos inserir o carrinho no banco de dados, incluindo o nome e a imagem do produto
        $stmt = $this->conn->prepare("INSERT INTO carrinho (
            usuario_id, produtos_id, nomeProduto, imageProduto,  observacao, quantidade, precoTotal) 
            VALUES (
                :usuario_id, :produtos_id, :nomeProduto, :imageProduto,  :observacao, :quantidade, :precoTotal
            )");
        $stmt->bindParam(":usuario_id", $carrinho->usuario_id);
        $stmt->bindParam(":produtos_id", $carrinho->produtos_id);
        $stmt->bindParam(":nomeProduto", $nomeProduto);
        $stmt->bindParam(":imageProduto", $imageProduto);
        $stmt->bindParam(":observacao", $carrinho->observacao);
        $stmt->bindParam(":quantidade", $carrinho->quantidade);
        $stmt->bindParam(":precoTotal", $carrinho->precoTotal);
        $stmt->execute();
        $this->message->setMessage("Adicionado no carrinho com sucesso!", "success", "..//index.php");
    }
    
    
    public function update(Carrinho $carrinho) {
        // para localizar o carrinho
    }

    public function findById($id) { // Corrigido o nome do método para findById
        // Inicializando um array para armazenar os dados do carrinho
        $carrinho = [];
        
        // Preparando a consulta SQL para selecionar o carrinho com base no ID
        $stmt = $this->conn->prepare("SELECT * FROM carrinho WHERE id = :id");
        
        // Vinculando o parâmetro :id com o valor fornecido
        $stmt->bindParam(":id", $id);
        
        // Executando a consulta preparada
        $stmt->execute();
        
        // Verificando se a consulta retornou algum resultado
        if ($stmt->rowCount() > 0) { 
            // Obtendo os dados do carrinho
            $carrinhoData = $stmt->fetch();
            
            // Construindo o objeto de carrinho a partir dos dados retornados
            $carrinho = $this->buildCarrinho($carrinhoData);
            
            // Retornando o carrinho construído
            return $carrinho;
        } else {
            // Se não houver resultados, retornar false
            return false;
        }
    }
    
    public function deleteUsuario($usuario_id){
        $stmt = $this->conn->prepare("DELETE FROM carrinho 
                                    WHERE usuario_id = :usuario_id");
    
         $stmt->bindParam(":usuario_id", $usuario_id);
    
        $stmt->execute();
    
        $this->message->setMessage("Carrinho Esvaziado com sucesso!", "success", "..//index.php");
    }
    public function delete($id) {
        // Iniciar uma transação
       
            // Preparando a consulta SQL para excluir o item do carrinho com base no ID
            $stmt = $this->conn->prepare("DELETE FROM carrinho WHERE id = :id");
            $stmt->bindParam(":id", $id);
            
            // Executando a consulta preparada
            $stmt->execute();
                 // Definir a mensagem de sucesso se o item do carrinho for removido com sucesso
             $this->message->setMessage("Produto removido com sucesso!", "success", "..//cesta.php");
    
    
            // Retornando true para indicar que a exclusão foi bem-sucedida
          
        

        }
    
    
        public function getCarrinhoByUserId($id) {
            $carrinho = [];
            $stmt = $this->conn->prepare("SELECT c.*, p.nomeProduto, p.image 
                                          FROM carrinho c
                                          INNER JOIN produtos p ON c.produtos_id = p.id
                                          WHERE c.usuario_id = :usuario_id");
            $stmt->bindParam(":usuario_id", $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $carrinhoArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($carrinhoArray as $data) {
                    // Aqui, $item contém os dados do carrinho e do produto associado
                    $carrinho[] = $this->buildCarrinho($data);
                }
            }
            return $carrinho;
        }
        

        }
?>
