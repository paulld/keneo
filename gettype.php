<?php
if(isset($p)) { $recupinfo = 1; } else {session_start(); $p = intval($_GET['p']); $recupinfo = 0; }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
		
	$pro= "SELECT T1.imputID2, T2.code, T2.description FROM rob_comprel2 T1
			INNER JOIN rob_compl2 T2 ON T2.ID = T1.imputID2
			INNER JOIN rob_compl1 T3 ON T3.ID = T1.imputID
			WHERE T1.actif = 1 AND T2.actif = 1 AND T1.imputID = ".$p." AND T3.actif = 1 
			ORDER BY T2.code";

	$reppro = $bdd->query($pro);
	$checkpro = $reppro->rowCount();
	if ($checkpro != 0)
	{
		echo '<select class="form-control form-control-small" name="typecomp" id="typecomp" onchange="showEvnmt(this.value)">';
		echo '<option value="none">Type</option>';
		while ($donpro = $reppro->fetch())
		{
			if ($recupinfo == 1)
			{
				if ($donpro[0] == $m)
				{
					echo '<option value='.$donpro[0].' selected>'.$donpro[2].'</option>';
				}
				else
				{
					echo '<option value='.$donpro[0].'>'.$donpro[2].'</option>';
				}
			}
			else
			{
				echo '<option value='.$donpro[0].'>'.utf8_encode($donpro[2]).'</option>';
			}
		}
		echo '</select>';
	}
	else
	{
		echo 'Pas de projet n&eacute;cessaire.<input type="hidden" value="0" name="typecomp" />';
	}
	$reppro->closeCursor();
	 
}
?> 