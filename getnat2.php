<?php
if(isset($a)) { $recupinfo = 1; } else {session_start(); $a = intval($_GET['a']); $recupinfo = 0; }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$pro= "SELECT ID, Description FROM rob_nature2 WHERE actif = 1 AND natID1 = ".$a." ORDER BY Description";

	$reppro = $bdd->query($pro);
	$checkpro = $reppro->rowCount();
	if ($checkpro != 0)
	{
		echo '<select name="nature2ID" onchange="showProfil(this.value)" />';
			echo '<option value="none">Sous nature...</option>';
			while ($donpro = $reppro->fetch())
			{
				if ($recupinfo == 1)
				{
					if ($donpro[0] == $a2)
					{
						echo '<option value='.$donpro[0].' selected>'.$donpro[1].'</option>';
					}
					else
					{
						echo '<option value='.$donpro[0].'>'.$donpro[1].'</option>';
					}
				}
				else
				{
					echo '<option value='.$donpro[0].'>'.utf8_encode($donpro[1]).'</option>';
				}
			}
		echo '</select>';
	}
	else
	{
		echo 'Pas de sous nature n&eacute;cessaire.<input type="hidden" value="0" name="nature2ID" />';
	}
	$reppro->closeCursor();
	 
}
?> 