<?php

require_once "controllers/EstoqueController.php";
require_once "models/Estoque.php";

$controller = new EstoqueController();
$estoques = $controller->findAll();

// Verificar se existe uma mensagem definida na sessão
if (isset($_SESSION['mensagem'])) {
    echo "<script>alert('" . $_SESSION['mensagem'] . "')</script>";
    unset($_SESSION['mensagem']); // Limpar a variável de sessão após exibir o alerta
}
?>