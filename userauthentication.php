<?php
$host = "localhost";
$user = "root";
$pass = "";
$banco = "cadastro";

$conexao = mysql_connect($host,$user,$pass) or die(mysql_error());
mysql_select_db($banco) or die(mysql_error());
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

$sql = mysql_query("SELECT * FROM usuarios WHERE usuario ='$usuario' AND senha='$senha'") or die(mysql_error());
$row = mysql_num_rows($sql);
if($row>0){
   session_start();
    $_SESSION['usuario'] = $_POST['usuario'];
    $_SESSION['senha'] = $_POST['senha'];
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