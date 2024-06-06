<?php
    
    require_once("config/globals.php");
    require_once("config/db.php");
    require_once("template/header.php");
    require_once("process/auth_process.php");











?>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form  action="<?= $BASE_URL ?>process/auth_process.php" method="POST" class="login100-form validate-form">
                <input type="hidden" name="type" value="registrar">
                
					<span class="login100-form-title p-b-43" >
                    <h3 class="title-form">Criando Cadastro</h3>
					</span>				
					<div class="wrap-input100 validate-input" > <!--Pegando o Nome -->
						<input class="input100" type="text" name="nome" required >
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="nome">Nome: </label>
					</div>		
                    <div class="wrap-input100 validate-input" >  <!--Pegando o SobreNome -->
						<input class="input100" type="text" name="sobrenome" required >
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="sobrenome">SobreNome: </label>
					</div>	
                    <div class="wrap-input100 validate-input" >  <!--Pegando o Endereço -->
						<input class="input100" type="text" name="endereco">
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="endereco">Endereço: </label>
					</div>		
                    <div class="wrap-input100 validate-input" >  <!--Pegando o numero da Casa -->
						<input class="input100" type="text" name="numeroCasa">
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="numeroCasa">Numero da Casa: </label>
					</div>		
                    <div class="wrap-input100 validate-input" >  <!--Pegando o Complemento -->
						<input class="input100" type="text" name="complemento"  >
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="complemento">Complemento: </label>
					</div>	
                    <div class="wrap-input100 validate-input" >  <!--Pegando o Bairro -->
						<input class="input100" type="text" name="bairro">
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="bairro">Bairro: </label>
					</div>		
                    <div class="wrap-input100 validate-input" >  <!--Pegando o Telefone -->
						<input class="input100" type="text" name="telefone" required >
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="telefone">Telefone de Contato: </label>
					</div>	
                    <div class="wrap-input100 validate-input" >  <!--Pegando o Email -->
						<input class="input100" type="text" name="email">
						<span class="focus-input100"></span>						
                        <label class="label-input100" for="email">Email: </label>
					</div>						
					<div class="wrap-input100 validate-input" >
						<input class="input100" type="password" name="senha" required >
						<span class="focus-input100"></span>
                        <label class="label-input100" for="senha">Senha: </label>
					</div>
                    <div class="wrap-input100 validate-input" >
						<input class="input100" type="password" name="confirmacaoSenha" required >
						<span class="focus-input100"></span>
                        <label class="label-input100" for="confirmacaoSenha">Confirme a Senha: </label>
					</div>
						
					<div class="container-login100-form-btn">
						
                        <input type="submit" class="login100-form-btn" value="Registrar">
					</div>					
					
					
				</form>

				<div class="login100-more" style="background-image: url('img/fundo.jpg')">
				</div>
			</div>
		</div>
	</div>















<?php

    include_once("template/footer.php")






?>