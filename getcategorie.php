<?php
if(isset($c) AND isset($p) AND isset($m) AND isset($k)) { $recupinfo = 1; } else {session_start(); $c = intval($_GET['c']); $p = intval($_GET['p']); $m = intval($_GET['m']); $recupinfo = 0; }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$cat= "SELECT T1.imputID4, T5.code, T5.description FROM rob_imprel4 T1
			INNER JOIN rob_imputl4 T5 ON T5.ID = T1.imputID4
			INNER JOIN rob_imputl3 T4 ON T4.ID = T1.imputID3
			INNER JOIN rob_imputl2 T2 ON T2.ID = T1.imputID2
			INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID
			WHERE T1.actif = 1 AND T2.actif = 1 AND T3.actif = 1 AND T4.actif = 1 AND T5.actif = 1 AND T1.imputID = ".$c." AND T1.imputID2 = ".$p." AND T1.imputID3 = ".$m." 
			ORDER BY T4.description";

	$repcat = $bdd->query($cat);
	$checkcat = $repcat->rowCount();
	if ($checkcat != 0)
	{
		echo '<select class="form-control form-control-small" name="categorie">';
		echo '<option value="none">Cat&eacute;gorie</option>';
		while ($donl4 = $repcat->fetch())
		{
			if ($recupinfo == 1)
			{
				if ($donl4[0] == $k)
				{
					echo '<option value='.$donl4[0].' selected>'.$donl4[2].'</option>';
				}
				else
				{
					echo '<option value='.$donl4[0].'>'.$donl4[2].'</option>';
				}
			}
			else
			{
				echo '<option value='.$donl4[0].'>'.utf8_encode($donl4[2]).'</option>';
			}

		}
		echo '</select>';
	}
	else
	{
		echo 'Pas de cat&eacute;gorie n&eacute;cessaire.<input type="hidden" value="0" name="categorie" />';
	}

	$repcat->closeCursor();
	 
}
?> 