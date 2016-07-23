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
<html>
    <head>
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

$stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ? AND senha = ?");  // stmt - statement
$stmt->bind_param('ss',  $usuario, $senha);
$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows > 0){
   session_start();
    $_SESSION['usuario'] = $_POST['usuario'];
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