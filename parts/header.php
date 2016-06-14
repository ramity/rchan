<div id="header">
	<a id="headerlogo" href="https://ramity.com/projects/rchan/">R</a>
	<div id="headerinr">
		<?php
		try
		{
			$d=new PDO('mysql:host=localhost;dbname=rchan_main;charset=utf8','username','password');
			$d->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$d->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
			$s=$d->prepare('SELECT id,name FROM threads');
			$s->execute();
			$r=$s->fetchAll(PDO::FETCH_ASSOC);
			$d=null;

			echo '[ ';

			if(!empty($r))
			{
				for($z=0;$z<count($r);$z++)
				{
					echo '<a class="threaditem" href="https://ramity.com/projects/rchan/v?t='.$r[$z]['id'].'">'.$r[$z]['name'].'</a>';
					if(isset($r[$z+1])&&!empty($r[$z+1]))
					{
						echo ' / ';
					}
				}
			}
			else
			{
				echo '(empty)';
			}
			echo ' ]';
		}
		catch(PDOException $e)
		{
			$error=$e->getMessage();
		}
		?>
	</div>
</div>
