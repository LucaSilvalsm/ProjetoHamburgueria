<?php

    class Carrinho {
        public $id;
        public $usuario_id;
        public $produtos_id; 
        public $quantidade ;
        public $observacao; 
        public $precoTotal; 
        public $nomeProduto ; 

        public $imageProduto; 
    }


    interface CarrinhoDAOInterface{


        public function buildCarrinho($data); // para criar o objeto 
        public function findAll(); // para encontrar todos os objetos
        public function create(Carrinho $carrinho); // para criar o carrinho
        public function update(Carrinho $carrinho); // para localizar o carrinho
        public function findByid($id); // localizar o carrinho pelo ID
   
        public function delete($id);

        public function getCarrinhoByUserId($id);
    









    }