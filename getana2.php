<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$a = intval($_GET['a']);
		
	$pro= "SELECT ID, desc1 FROM rob_ana2 WHERE actif = 1 AND anaID1 = ".$a." ORDER BY desc1";

	$reppro = $bdd->query($pro);
	$checkpro = $reppro->rowCount();
	if ($checkpro != 0)
	{
		echo 'Analytique 2 : <select name="ana2ID" />';
			echo '<option>...</option>';
			while ($optimput = $reppro->fetch())
			{
				echo '<option value='.$optimput['ID'].'>'.utf8_encode($optimput['desc1']).'</option>';
			}
		echo '</select>';
	}
	else
	{
		echo 'Pas d\'axe analytique 2 n&eacute;cessaire.<input type="hidden" value="0" name="ana2ID" />';
	}
	$reppro->closeCursor();
	 
}
?> 