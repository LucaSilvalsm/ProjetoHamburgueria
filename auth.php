<?php
    require_once("config/globals.php");
    require_once("config/db.php");
    require_once("template/header.php");
    

?>
 <div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form action="<?= $BASE_URL ?>process/auth_process.php" method="POST" class="login100-form validate-form">
                <input type="hidden" name="type" value="login">

                <span class="login100-form-title p-b-43">
                    Faça o Login para Continuar
                </span>

                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="email">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Email</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="senha">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Senha</span>
                </div>

                <div class="flex-sb-m w-full p-t-3 p-b-32">
                    <div>
                        <a href="#" class="txt1">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">Entrar</button>
                </div>

                <div class="text-center p-t-46 p-b-20">
                    <span class="card-btn">
                        <a href="<?= $BASE_URL ?>cadastro.php">Não tenho conta</a>
                    </span>
                </div>

            </form>

            <div class="login100-more" style="background-image: url('img/fundo.jpg')"></div>
        </div>
    </div>
</div>
<?php
  require_once("template/footer.php");
?>