<?php

  require_once("template/header.php");

  if($usuarioDao) {
    $usuarioDao->destroyToken();
  }