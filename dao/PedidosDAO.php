<?php
require_once(__DIR__ . "/../models/Carrinho.php");
require_once(__DIR__ . "/../models/Message.php");
require_once(__DIR__ . "/../models/Pedidos.php");

require_once(__DIR__ . "/../dao/ProdutosDAO.php");

require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");





class PedidosDAO implements PedidosDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }
    public function buildPedidos($data) {
        $pedidos = new Pedidos();
        $pedidos->id = isset($data["id"]) ? $data["id"] : null; // Definir o valor de id corretamente
        $pedidos->usuario_id = isset($data["usuario_id"]) ? $data["usuario_id"] : null;
        $pedidos->data_pedido = isset($data["data_pedido"]) ? $data["data_pedido"] : null;
        $pedidos->forma_pagamento = isset($data["forma_pagamento"]) ? $data["forma_pagamento"] : null;
        $pedidos->endereco_entrega = isset($data["endereco_entrega"]) ? $data["endereco_entrega"] : null;
        $pedidos->status = isset($data["status"]) ? $data["status"] : null;
        $pedidos->valor_total = isset($data["valor_total"]) ? $data["valor_total"] : null;
        $pedidos->observacao = isset($data["observacao"]) ? $data["observacao"] : null;
        $pedidos->itens_comprados = isset($data["itens_comprados"]) ? $data["itens_comprados"] : null;
        return $pedidos;
    }
    
    
    public function createAndDeleteCarrinho(Pedidos $pedidos, $usuario_id) {
        try {
            $this->conn->beginTransaction();
    
            // Insere o pedido na tabela pedidos
            $stmt = $this->conn->prepare("INSERT INTO pedidos(usuario_id, data_pedido, forma_pagamento, endereco_entrega, status, valor_total, observacao, itens_comprados) VALUES(:usuario_id, :data_pedido, :forma_pagamento, :endereco_entrega, :status, :valor_total, :observacao, :itens_comprados)");
            $stmt->bindParam(":usuario_id", $pedidos->id);
            $stmt->bindParam(":data_pedido", $pedidos->data_pedido);
            $stmt->bindParam(":forma_pagamento", $pedidos->forma_pagamento);
            $stmt->bindParam(":endereco_entrega", $pedidos->endereco_entrega);
            $stmt->bindParam(":status", $pedidos->status);
            $stmt->bindParam(":valor_total", $pedidos->valor_total);
            $stmt->bindParam(":observacao", $pedidos->observacao);
            $stmt->bindParam(":itens_comprados", $pedidos->itens_comprados);
            $stmt->execute();
    
            // Exclui o carrinho do usuário
            $stmt = $this->conn->prepare("DELETE FROM carrinho WHERE usuario_id = :usuario_id");
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();
    
            $this->conn->commit();
    
            $this->message->setMessage("Pedido Realizado com sucesso!", "success", "..//index.php");
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Erro: " . $e->getMessage();
        }
    }
    public function updatePedidos(Pedidos $pedidos) {
        try {
            $stmt = $this->conn->prepare("UPDATE pedidos SET status = :status WHERE id = :pedido_id");
            $stmt->bindParam(":status", $pedidos->status);
            $stmt->bindParam(":pedido_id", $pedidos->id);
            $stmt->execute();
            // Verifica se a atualização foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                return true; // Retorna true se a atualização foi bem-sucedida
            } else {
                return false; // Retorna false se nenhum registro foi atualizado
            }
        } catch (PDOException $e) {
            // Gerenciar exceções, por exemplo, registrando logs ou lançando exceções personalizadas
            throw $e;
        }
    }
    
    public function findAll() { // mostrar todos os pedidos 
        $pedidos = [];
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos ORDER BY id DESC LIMIT 8");
            $stmt->execute();
            while ($pedidoData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pedidos[] = $this->buildPedidos($pedidoData);
            }
        } catch (PDOException $e) {
            // Gerenciar exceções, por exemplo, registrando logs ou lançando exceções personalizadas
            // Aqui, estou apenas lançando novamente a exceção, mas você pode fazer mais
            throw $e;
        }
        return $pedidos;
    }
    
    public function findById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $pedidoData = $stmt->fetch(PDO::FETCH_ASSOC);
                return $this->buildPedidos($pedidoData);
            } else {
                // Se não houver resultados, retorna null ou lança uma exceção, dependendo da lógica de sua aplicação
                return null;
            }
        } catch (PDOException $e) {
            // Gerenciar exceções, por exemplo, registrando logs ou lançando exceções personalizadas
            // Aqui, estou apenas lançando novamente a exceção, mas você pode fazer mais
            throw $e;
        }
    }
    
    public function findByData($data_pedido){ // procurar o pedido pela data do pedido 

    }
    public function getPedidosByUsuarioId($usuario_id) {
        $pedidos = [];
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY id DESC");
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($pedidoData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pedidos[] = $this->buildPedidos($pedidoData);
            }
        } catch (PDOException $e) {
            // Gerenciar exceções
            throw $e;
        }
        return $pedidos;
    }
    
    
    public function delete ($id){ // cancelar o pedido 

    }
    public function deleteUsuario($usuario_id){
        $stmt = $this->conn->prepare("DELETE FROM carrinho 
                                    WHERE usuario_id = :usuario_id");
    
         $stmt->bindParam(":usuario_id", $usuario_id);
    
        $stmt->execute();
    
        $this->message->setMessage("Carrinho Esvaziado com sucesso!", "success", "..//index.php");
    }
    public function getValorTotalPedidos(){
        $total = 0;
        $stmt = $this->conn->prepare("SELECT SUM(valor_total) AS total FROM pedidos");
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        }
        return $total;
    }
    public function getTotalPedidos(){
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_pedidos FROM pedidos");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_pedidos'];
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            return null;
        }
    }
    public function getMediaPedidos($totalPedidos, $totalDias){
        // Verifica se o total de dias não é zero para evitar divisão por zero
        if ($totalDias != 0) {
            $media = $totalPedidos / $totalDias;
            // Formata o resultado para duas casas decimais
            $mediaFormatada = number_format($media, 2);
            return $mediaFormatada;
        } else {
            return 0; // Retorna 0 se o total de dias for zero
        }
    
    
    }
    public function findAllPaginated($offset, $limit) {
        $sql = "SELECT * FROM pedidos ORDER BY id DESC LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    
}