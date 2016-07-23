<?php
$host = "localhost";
$user = "root";
$pass = "";
$banco = "cadastro";

$conexao = new mysqli($host, $user, $pass, $banco);
if($conexao->connect_errno > 0) {
    die("Erro ao conectar-se no banco de dados!");   // $conexao->connect_error pra debugar
}
?>
<?php
    session_start();
    if(!isset($_SESSION["usuario"])|| !isset($_SESSION["senha"]))
    {
        header("Location:login.php");
        exit;
    }else{
        echo "<center>Logado</center>";
    }
?>
<!doctype HTML>
<html>
    <head>
        <title>Admin CP</title>
    </head>
    
    <body>
        <center><a href="logout.php">Sair</a></center>
    </body>
    
</html>