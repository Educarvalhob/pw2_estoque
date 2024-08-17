<?php
require_once "controllers/UsuarioController.php";

$usuarioController = new UsuarioController();

if (isset($_GET['pg']) && $_GET['pg'] === 'logout') {
    $usuarioController->logout();
}
?>