<?php
$error=false;

if(isset($_POST['newThreadSubmit'])&&!empty($_POST['newThreadSubmit']))
{
	if(isset($_POST['newThreadName'])&&!empty($_POST['newThreadName']))
	{
		if(ctype_alnum($_POST['newThreadName']))
		{
			try
			{
				$d=new PDO('mysql:host=localhost;dbname=rchan_main;charset=utf8','username','password');
				$d->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$d->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

				$s=$d->prepare('SELECT id,name FROM threads');
				$s->execute();
				$r=$s->fetchAll(PDO::FETCH_ASSOC);

				if(empty($r))
				{
					$s=$d->prepare('INSERT INTO threads (name,creator,date) VALUES (:name,:creator,:date)');
					$s->bindValue(':name',$_POST['newThreadName'],PDO::PARAM_STR);
					$s->bindValue(':creator','anon',PDO::PARAM_STR);
					$s->bindValue(':date',date("F j, Y, g:i a"),PDO::PARAM_STR);
					$s->execute();
					header('Location: index.php');
				}
				else
				{
					$error="[SUBMISSION ERROR]: A thread of that name has already been created.";
				}
			}
			catch(PDOException $e)
			{
				$error=$e->getMessage();
			}
		}
		else
		{
			$error="[SUBMISSION ERROR]: You can't create a thread with special characters.";
		}
	}
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
				<form action="index.php" method="post">
					<div class="inputlabel">Create a new thread:</div>
					<input autocomplete="off" placeholder="Thread name" class="text" type="text" name="newThreadName">
					<input value="create" class="button" type="submit" name="newThreadSubmit">
				</form>
				<hr class="contenthr">
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
