<?php


class Pedidos{
    public $id;
    public $usuario_id;
    public $data_pedido;
    public $forma_pagamento;
    public $endereco_entrega;
    public $status;
    public $valor_total;
    public $observacao;
    public $itens_comprados ;

    // Construtor da classe
   


}

interface PedidosDAOInterface {
    public function buildPedidos($data) ; // para criar o objeto Pedidos 
    public function createAndDeleteCarrinho(Pedidos $pedidos, $usuario_id) ; // crindo o pedido e deletando o carrinho 
    public function updatePedidos(Pedidos $Pedidos) ; // atualizar o pedido "status"
    public function findALL(); // mostrar todos os pedidos 
    public function findById($id); // procurar o pedido pelo ID 
    public function findByData($data_pedido);// procurar o pedido pela data do pedido 
    public function delete ($id); // cancelar o pedido 
    public function deleteUsuario($usuario_id);
    public function getValorTotalPedidos();
    


}