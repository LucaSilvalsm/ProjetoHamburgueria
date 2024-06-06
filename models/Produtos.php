<?php

    //model do Hamburguer

    class Produtos {

        public $id;
        public  $nomeProduto ;
        public $tipoProdutos ;
        public $tamanho;
        public $ingrediente;
        public $preco ; // preço ou valor
        public $descricao; // descrição
        public $image;

        public function imageGenerateName() {
            return bin2hex(random_bytes(60)) . ".jpg";
          }
    }

    interface ProdutosDAOInterface{

    public function buildProdutos($data); // para criar o objeto produtos
    public function findAll(); // para encontrar todos os produtos 
    public function create(Produtos $produtos); // para criar o produtos
    public function update(Produtos $produtos); // para localizar o produtos 
    public function findByid($id); // localizar o produtos pelo ID
    public function getpProdutosByCategorias($tipoProdutos); // separar os produtos pela categoria
    public function destroy($id);







    }






















