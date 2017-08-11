<?php 
header('Content-type: text/html; charset=utf-8');
// Para ignorar warnings
//error_reporting(E_ALL & ~E_NOTICE & ~8192);

//include para conectar no banco
require_once "inc/config.php";

//include para ver quanto tempo passou
require_once "inc/time.php";
 ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
            <meta charset="utf-8" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title></title>
        
    <script type="text/javascript">
    function loginsuccessifully(){
     setTimeout("window.location='mailbox.php'",2500);
    }
    
    function loginfailed(){
    setTimeout("window.location='login.php'",2500);
    }
    </script>
    </head>
    <body>
<?php
$usuario=$_POST['usuario'];
$senha=$_POST['senha'];
$stmt = $conexao->prepare("SELECT nome,usernumber FROM usuarios WHERE usuario = ? AND senha = ?");  // stmt - statement
$stmt->bind_param('ss',  $usuario, md5($usuario.$senha));
$stmt->execute();
$result = $stmt->get_result();
$row = $row = $result->fetch_assoc();
if($result->num_rows > 0){
   session_start();
    $_SESSION['usuario'] = $_POST['usuario'];
    $_SESSION['nome'] = utf8_encode($row["nome"]);
    $_SESSION['usernumber'] = $row["usernumber"];
    echo "<center>Logado com sucesso</center>";
    echo "<script>loginsuccessifully()</script>";
}
else{
    echo "<center>Falha no login</center>";
    echo "<script>loginfailed()</script>";
}
?>
    </body>
</html>