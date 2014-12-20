<?php
if(isset($c) AND isset($t) AND isset($e)) { $recupinfo = 1; $page = 1; } else {session_start(); $recupinfo = 0; $c = intval($_GET['c']); $t = intval($_GET['t']); $page = intval($_GET['page']); }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$mis= "SELECT T0.imputID3, T3.code, T3.description FROM rob_comprel3 T0
			INNER JOIN rob_compl3 T3 ON T3.ID = T0.imputID3
			INNER JOIN rob_compl2 T2 ON T2.ID = T0.imputID2
			INNER JOIN rob_compl1 T1 ON T1.ID = T0.imputID
			WHERE T0.actif = 1 AND T1.actif = 1 AND T2.actif = 1 AND T3.actif = 1 AND T0.imputID = ".$c." AND T0.imputID2 = ".$t." 
			ORDER BY T3.description";

	$repmis = $bdd->query($mis);
	$checkmis = $repmis->rowCount();
	if ($checkmis != 0)
	{
		echo '<select class="form-control" name="evnmt" onchange="showCatEve(this.value)">';
		echo '<option value="none">&Eacute;v&eacute;nement</option>';
		while ($donl3 = $repmis->fetch())
		{
			if ($recupinfo == 1)
			{
				if ($donl3[0] == $e)
				{
					echo '<option value='.$donl3[0].' selected>'.$donl3[2].'</option>';
				}
				else
				{
					echo '<option value='.$donl3[0].'>'.$donl3[2].'</option>';
				}
			}
			else
			{
				echo '<option value='.$donl3[0].'>'.utf8_encode($donl3[2]).'</option>';
			}

		}
		echo '</select>';
		if ($page == 2) { echo '<span id="txtHint6"></span>'; }
	}
	else
	{
		echo 'Pas d\'&eacute;v&eacute;nement n&eacute;cessaire.'.$c.'-'.$t.'-'.$checkmis.'<input type="hidden" value="0" name="evnmt" />';
	}

	$repmis->closeCursor();
	 
}
?> 