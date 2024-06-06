<?php
require_once("header.php");
require_once(__DIR__ . "/../dao/PedidosDAO.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");

require_once(__DIR__ . "/../models/Usuario.php");
require_once(__DIR__ . "/../models/Message.php");

require_once(__DIR__ . "/../models/Pedidos.php");
require_once(__DIR__ . "/../config/globals.php");
require_once(__DIR__ . "/../config/db.php");






$usuario = new Usuario();
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);


?>


<div class="form-container">


    <div class="form">
        <h2 class="page-title">Adicionando Hamburguer</h2>
        <form action="<?= $BASE_URL ?>../process/hamburguer_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="criar">
            <label for="nome">Nome do Hamburguer:</label>
            <input type="text" id="nome" name="nome" placeholder="Digite o nome do Hamburguer" required>

            <label for="tipoProdutos">Tipo do Produto:</label>
            <select id="tipoProdutos" name="tipoProdutos" required>
                <option value="">Selecione o tipo de Produto</option>
                <option value="Artesanal">Hambúrguer Artesanal</option>
                <option value="Tradicional">Hambúrguer Tradicional</option>
                <option value="Bebida">Bebida</option>
                <option value="Porcao">Porção</option>
                <option value="Sobremesa">Sobremesa</option>
            </select>

            <label>Tamanho:</label>
            <div class="tamanho">

                <input type="checkbox" id="tamanho-290ml" name="tamanho[]" value="290 ml" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-350ml">290 ml</label>
                <input type="checkbox" id="tamanho-350ml" name="tamanho[]" value="350 ml" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-350ml">350 ml</label>

                <input type="checkbox" id="tamanho-500ml" name="tamanho[]" value="500 ml" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-500ml">500 ml</label>

                <input type="checkbox" id="tamanho-600ml" name="tamanho[]" value="600 ml" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-600ml">600 ml</label>

                <input type="checkbox" id="tamanho-1.5litros" name="tamanho[]" value="1.5 litros" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-1.5litros">1.5 litros</label>

                <input type="checkbox" id="tamanho-2litros" name="tamanho[]" value="2 litros" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-2litros">2 litros</label>

                <input type="checkbox" id="tamanho-porcao-media" name="tamanho[]" value="Porcao Media" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-porcao-media">Porção Media</label>

                <input type="checkbox" id="tamanho-porcao-grande" name="tamanho[]" value="Porcao Grande" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-porcao-grande">Porção Grande</label>

                <input type="checkbox" id="tamanho-hamburguer-pequeno" name="tamanho[]" value="Hamburguer Pequeno" onclick="permitirApenasUmCheckbox(this)">
                <label for="tamanho-hamburguer-pequeno">Hamburguer Pequeno</label>
            </div>

            <label>Ingredientes:</label>
            <div class="ingrediente">
                <input type="checkbox" id="bacon" name="ingrediente[]" value="Bacon">
                <label for="bacon">Bacon</label>

                <input type="checkbox" id="batata-frita" name="ingrediente[]" value="Batata frita">
                <label for="batata-frita">Batata Frita</label>

                <input type="checkbox" id="Anel de cebola" name="ingrediente[]" value="Anel de cebola">
                <label for="Anel de cebola">Anel de Cebola</label>

                <input type="checkbox" id="calabresa" name="ingrediente[]" value="Calabresa">
                <label for="calabresa">Calabresa</label>

                <input type="checkbox" id="salada" name="ingrediente[]" value="Salada">
                <label for="salada">Salada</label>

                <input type="checkbox" id="molho" name="ingrediente[]" value="Molho">
                <label for="molho">Molho</label>

                <input type="checkbox" id="cebola" name="ingrediente[]" value="Cebola">
                <label for="cebola">Cebola</label>

                <input type="checkbox" id="duas_carnes" name="ingrediente[]" value="2 Carnes">
                <label for="duas_carnes">2 Carnes</label>

                <input type="checkbox" id="uma_carne" name="ingrediente[]" value="1 Carne">
                <label for="uma_carne">1 Carne</label>

                <input type="checkbox" id="ovo" name="ingrediente[]" value=" Ovo">
                <label for="ovo"> Ovo</label>

                <input type="checkbox" id="cheddar" name="ingrediente[]" value=" Cheddar">
                <label for="cheddar"> Cheddar</label>

                <input type="checkbox" id="barbecue" name="ingrediente[]" value=" Barbecue">
                <label for="barbecue"> Barbecue</label>
            </div>

            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="5" class="form-control" required placeholder="Faça uma descrição do Hamburguer"></textarea>

            <label for="image">Imagem:</label>
            <input type="file" class="form-control-file" name="image">

            <button class="btn align-self-center card-btn" type="submit">Adicionar Hamburguer</button>
        </form>
    </div>
    <script src="javascript/produtos.js"></script>


    =