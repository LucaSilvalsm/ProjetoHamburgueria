<?php
require_once("header.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");


// Inicialize o objeto $usuarioDao, se necessário
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

if($usuarioDao) {
    $usuarioDao->ADMdestroyToken();
}
?>
