<?php
$error=false;
$view=0;

if(isset($_GET['t'])&&!empty($_GET['t'])&&ctype_digit($_GET['t']))
{
	$view=1;
	if(isset($_GET['b'])&&!empty($_GET['b'])&&ctype_digit($_GET['b']))
	{
		$view=2;
	}
}
else
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
	<head>
		<title></title>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="index.css">
	</head>
	<body>
		<?php
		require_once('parts/header.php');
		?>
		<div id="content">
			<div id="contentinr">
				<form action="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>" method="post">
					<div class="inputlabel">Create a new board:</div>
					<textarea autocomplete="off" placeholder="Board text" class="text" type="text" name="newBoardName"></textarea>
					<input style="width:100%" value="create" class="button" type="submit" name="newBoardSubmit">
				</form>
				<hr class="contenthr">
				<?php
				try
				{
					$d=new PDO('mysql:host=localhost;dbname=rchan_main;charset=utf8','username','password');
					$d->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$d->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
					$s=$d->prepare('SELECT id,text,op,date,votes,views,ifpinned,replies,ifimg,imgurl FROM boards WHERE threadid=:threadid');
					$s->bindValue(':threadid',$_GET['t'],PDO::PARAM_INT);
					$s->execute();
					$r=$s->fetchAll(PDO::FETCH_ASSOC);
					$d=null;

					if(!empty($r))
					{
						for($z=0;$z<count($r);$z++)
						{
							echo '<div class="board">';
								echo '<a href="'."http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&b=".$r[$z]['id'].'" class="boardtopbar"><b>'.$r[$z]['op'].'</b> - '.$r[$z]['date'].' - No.'.$r[$z]['id'].' - '.$r[$z]['replies'].' replies<font class="votes">'.$r[$z]['votes'].' point(s)</font></a>';
								echo '<div class="boardtext">'.$r[$z]['text'].'</div>';
								echo '<div class="boardreply">';
									echo '<a class="replybutton" href="'."http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&b=".$r[$z]['id'].'&r=true">reply</a>';
								echo '</div>';
							echo '</div>';
							if(isset($r[$z+1])&&!empty($r[$z+1]))
							{
								echo '<hr class="contenthr">';
							}
						}
					}
					else
					{
						echo '(empty)';
					}
				}
				catch(PDOException $e)
				{
					$error=$e->getMessage();
				}
				?>
			</div>
		</div>
		<?php
		if($error)
		{
			echo '<div id="error">'.$error.'</div>';
		}
		?>
	</body>
</html>
