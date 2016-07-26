<?php
<<<<<<< HEAD
header('Content-type: text/html; charset=utf-8');  
$host = "176.32.230.251";
$user = "cl57-mudda";
$pass = "Mudda#5656";
$banco = "cl57-mudda";
=======
$host = "localhost";
$user = "root";
$pass = "";
$banco = "cadastro";

>>>>>>> 1dc5d74b7edfcc34882d9e3340629b079189dbc7
$conexao = new mysqli($host, $user, $pass, $banco);
if($conexao->connect_errno > 0) {
    die("Erro ao conectar-se no banco de dados!");   // $conexao->connect_error pra debugar
}
?>
<?php
    session_start();
    if(!isset($_SESSION["usuario"]))
    {
        header("Location:login.php");
        exit;
    }else{
        echo "<center> Bem vindo! Mudda ".$_SESSION["nome"]."</center>";
    }
?>
<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin CP</title>
    </head>
    
    <body>
        <center><a href="logout.php">Sair</a></center>
    </body>
    
</html>