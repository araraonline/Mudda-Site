<?php 

// Para ignorar warnings
error_reporting(E_ALL & ~E_NOTICE & ~8192);

//include para conectar no banco
require_once "inc/config.php";

//include para ver quanto tempo passou
require_once "inc/time.php";
 ?>

<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/estilo.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <title>Inbox System</title>
    </head>
    
    <body>
    <div id="tudo">
    <?php
    session_start();
    if(!isset($_SESSION["usuario"]))
    {
        header("Location:login.php");
        exit;
    }else{
        echo "<center><h3>Bem vindo! Mudda ".$_SESSION["nome"]."</h3></center>";
    }
?>
     <center><a class="btn btn-primary" href="logout.php">Sair</a></center>
    <?php
    if(isset($_GET['msg'])){
    	$usuarionumber = $_SESSION['usernumber'];
    	$id = $_GET['msg'];
    	$stmt = $conexao->prepare("SELECT * FROM messages WHERE id = ?");
    	$stmt->bind_param('i',$id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	$stmt->close();
    	$stmt = $conexao->prepare("UPDATE $usuarionumber SET seen='1' WHERE idmessage = ?");
    	$stmt->bind_param('i',$id);
    	$stmt->execute();
    	$stmt->close();
    	$row = $result->fetch_assoc();
    	$anwser = $row['respondido'];
    	$from = $row['from'];
		$email = $row['email'];
		$subject = $row['subject'];
		$date = $row['date'];
		$time = time_passed($row['time']);
		$message = utf8_encode($row['message']);
     ?>
     <div id="msg">
     	<a class="back" href="mailbox.php">←Retornar</a>
     	<table>
     		<tr>
     			<td>Remetente :<?php echo $from; ?></td>
     			<td>Email :<?php echo $email; ?></td>
     			<td>Data :<?php echo $date; ?></td>
     			<td>Tempo :<?php echo $time; ?></td>
     		</tr>
     	</table>
     	<pre id="messagebox"><?php echo $message; ?></pre>
     	<a class="btn btn-danger" href="?remove=<?php echo $id;?>"> Deletar a mensagem </a>

     
     <?php
     	if($anwser == 0){
     	echo '<form method="post">';
     	echo '<textarea class="resposta" name="resposta"></textarea>';
     	echo '<input type="submit" name="submit" value="Enviar" class="btn btn-success"/>';
     	echo '</form>';
     }
          	if(isset($_POST['submit']))
     	{
     		$stmt = $conexao->prepare("SELECT * FROM messages WHERE id = ?");
    		$stmt->bind_param('i',$id);
    		$stmt->execute();
    		$result = $stmt->get_result();
    		$row = $result->fetch_assoc();
    		$stmt->close();

    		if($row['respondido']==0){
     		$message1 = $_POST['resposta'];
     		$emailTo = $email;
     		$subjec = "[Mudda Resposta] \"".$subject."\"";
     		$from = "mudda@mudda.com";
     		if(mail($emailTo,$subjec,$message1,$from)){
     		$stmt = $conexao->prepare("UPDATE messages SET respondido='1' WHERE id = ?");	
    		$stmt->bind_param('i',$id);
    		$stmt->execute();
    		$stmt->close();
    		header("Refresh:0");
     		exit();
     		}
     		else
     		{
     			echo "Tente Novamente!";
     		} 
     	}else
     	{
     		echo "<script type='text/javascript'>alert('Ja foi respondido mane');</script>";
     		header("Refresh:0");
     	}
     }
     exit();
     }?>
     </div>
     <?php 
     	if(isset($_GET['remove']))
     	{
     		$usuarionumber = $_SESSION['usernumber'];
     		$id = $_GET['remove'];
     		$stmt = $conexao->prepare("DELETE FROM $usuarionumber WHERE idmessage = ?");
    		$stmt->bind_param('i',$id);
    		$remove = $stmt->execute();
    		if ($remove)
    		{
    			echo '<script>window.location="mailbox.php";</script>';
    		}
    		else
    		{
    			die("Da refresh na pagina ae!");
    		}
    		$stmt->close();
     		exit();
     	}
      ?>
    <table>
    	<tr>
    		<th>#</th>
    		<th>Remetente</th>
    		<th>Email</th>
    		<th>Assunto</th>
    		<th>Envio</th>
    		<th>Visto</th>
    	</tr>
    		<?php
    			// pegar numero de rows
    			$limit = 5;
    			$usuarionumber = $_SESSION['usernumber'];
    			$p = $_GET['p'];
    			$stmt = $conexao->prepare("SELECT * FROM $usuarionumber");
				$stmt->execute();
				$result = $stmt->get_result();	
				$getNumberRows = $result->num_rows;
    			$total = ceil($getNumberRows/$limit);

				if(!isset($p) || $p <=0){
					$counter = 0;
					$offset = 0;
				}
				else {
					$offset = ceil($p - 1) * $limit;
					$counter = (5 * $p) - 5;
				}
				$stmt->close();
				// filtrar a partir do numero de rows 
				$stmt = $conexao->prepare("SELECT * FROM messages INNER JOIN $usuarionumber ON id = idmessage ORDER BY idmessage DESC LIMIT ?,?");
				$stmt->bind_param('ii',$offset,$limit);  // stmt - statement
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc())
				{
					$counter++;
					$id = $row['id'];
					$from = $row['from'];
					$email = $row['email'];
					$subject = $row['subject'];
					$date = $row['date'];
					$time = time_passed($row['time']);

					if($row['seen']==0){
						$open = "Not Opened";
					}else{
						$open = "Opened";
					}
					echo '<tr>';
						echo '<td><a href="?msg='.$id.'">'.$counter.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$from.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$email.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$subject.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$date.'-'.$time.'</a></td>';
						echo '<td><a href="?msg='.$id.'">'.$open.'</a></td>';
					echo '<tr>';
				}
				$stmt->close();
			
			?>

    </table>
    <?php
    $test = 1;
    if($getNumberRows==0)
    {
    	echo "<div id='nomsg'>";
		echo "<center>Nenhuma Mensagem Nova :(</center>";
		echo "</div>";
    }
    if($getNumberRows > $limit){
    echo '<div id="pages">';
    	for($i=1;$i <=$total;$i++)
		{
			if((!isset($p)||$p<=0) && $i==1)
			{
    		echo  '<a  class="active">'.$i.'</a>';
    		}
			else
			{
				echo  ($i == $p) ? '<a  class="active">'.$i.'</a>' : '<a href="?p='.$i.'">'.$i.'</a>';
			}
		}
	echo '</div>';	
}
     ?>
     </div>
    </body>
    
</html>