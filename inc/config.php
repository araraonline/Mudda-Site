<?php
header('Content-type: text/html; charset=utf-8');  
$host = "176.32.230.251";
$user = "cl57-mudda";
$pass = "Mudda#5656";
$banco = "cl57-mudda";
$conexao = new mysqli($host, $user, $pass, $banco);
if($conexao->connect_errno > 0) {
    die("Erro ao conectar-se no banco de dados!");   // $conexao->connect_error pra debugar
}
?>