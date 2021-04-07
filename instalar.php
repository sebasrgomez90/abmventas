<?php

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "sebasrgomez90";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "Sebastian";
$usuario->apellido = "Gomez";
$usuario->correo = "sebasr.gomez90@gmail.com";
$usuario->insertar();

?>