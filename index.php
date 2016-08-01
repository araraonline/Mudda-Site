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
        <title>Inbox System</title>
    </head>
    
    <body>
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
				$stmt = $conexao->prepare("SELECT * FROM messages");  // stmt - statement
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc())
				{
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
						echo '<td>'.$id.'</td>';
						echo '<td>'.$from.'</td>';
						echo '<td>'.$email.'</td>';
						echo '<td>'.$subject.'</td>';
						echo '<td>'.$date.'-'.$time.'</td>';
						echo '<td>'.$open.'</td>';
					echo '<tr>';
				}
			?>

    </table>
    </body>
    
</html>