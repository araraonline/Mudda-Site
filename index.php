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
        <title>Inbox System</title>
    </head>
    
    <body>
    <?php
    if(isset($_GET['msg'])){
    	$id = $_GET['msg'];
    	$stmt = $conexao->prepare("SELECT * FROM messages WHERE id = ?");
    	$stmt->bind_param('i',$id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	$stmt->close();
    	$stmt = $conexao->prepare("UPDATE messages SET open='1' WHERE id = ?");
    	$stmt->bind_param('i',$id);
    	$stmt->execute();
    	$stmt->close();
    	$row = $result->fetch_assoc();
    	$from = $row['from'];
		$email = $row['email'];
		$subject = $row['subject'];
		$date = $row['date'];
		$time = time_passed($row['time']);
		$message = utf8_encode($row['message']); 
     ?>
     <div id="msg">
     	<a class="back" href="./">←Retornar</a>
     	<table>
     		<tr>
     			<td>Remetente :<?php echo $from; ?></td>
     			<td>Email :<?php echo $email; ?></td>
     			<td>Data :<?php echo $date; ?></td>
     			<td>Tempo :<?php echo $time; ?></td>
     		</tr>
     	</table>
     	<pre><?php echo $message; ?></pre>
     	<a class="remove" href="?remove=<?php echo $id;?>"> Deletar a mensagem </a>
     </div>
     <?php exit();}?>
     <?php 
     	if(isset($_GET['remove']))
     	{
     		$id = $_GET['remove'];
     		$stmt = $conexao->prepare("DELETE FROM messages WHERE id = ?");
    		$stmt->bind_param('i',$id);
    		$remove = $stmt->execute();
    		if ($remove)
    		{
    			echo '<script>window.location="./";</script>';
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
    			$p = $_GET['p'];
    			$stmt = $conexao->prepare("SELECT * FROM messages");
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
				$stmt = $conexao->prepare("SELECT * FROM messages ORDER BY id DESC LIMIT ?,?");
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

					if($row['open']==0){
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
    </body>
    
</html>