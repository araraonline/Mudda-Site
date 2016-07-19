<?php
$host = "localhost";
$user = "root";
$pass = "";
$banco = "cadastro";

$conexao = mysql_connect($host,$user,$pass) or die(mysql_error());
mysql_select_db($banco) or die(mysql_error());
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