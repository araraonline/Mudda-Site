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
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
            <meta charset="utf-8" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title></title>
        
    <script type="text/javascript">
    function loginsuccessifully(){
     setTimeout("window.location='painel.php'",2500);
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
$stmt = $conexao->prepare("SELECT nome FROM usuarios WHERE usuario = ? AND senha = ?");  // stmt - statement
$stmt->bind_param('ss',  $usuario, md5($usuario.$senha));
$stmt->execute();
$result = $stmt->get_result();
$row = $row = $result->fetch_assoc();
if($result->num_rows > 0){
   session_start();
    $_SESSION['usuario'] = $_POST['usuario'];
    $_SESSION['nome'] = utf8_encode($row["nome"]);
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