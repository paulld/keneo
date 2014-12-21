<?php
if(isset($k)) { $recupinfo = 1; $page = 1; $p=$k; } else {
	if(isset($p)) { $recupinfo = 1; $page = 0; } else {
		session_start(); $recupinfo = 0; $p = intval($_GET['p']); $m = intval($_GET['m']); $page = intval($_GET['page']); } }
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$mis= "SELECT T1.imputID3, T4.code, T4.description FROM rob_imprel3 T1
			INNER JOIN rob_imputl3 T4 ON T4.ID = T1.imputID3
			INNER JOIN rob_imputl2 T2 ON T2.ID = T1.imputID2
			INNER JOIN rob_imputl1 T3 ON T3.ID = T1.imputID
			WHERE T1.actif = 1 AND T2.actif = 1 AND T3.actif = 1 AND T4.actif = 1 AND T1.imputID = ".$p." AND T1.imputID2 = ".$m." 
			ORDER BY T4.description";

	$repmis = $bdd->query($mis);
	$checkmis = $repmis->rowCount();
	if ($checkmis != 0)
	{
		echo '<select class="form-control form-control-small" name="mission" onchange="showCategorie(this.value)">';
		echo '<option value="none">Mission</option>';
		while ($donl3 = $repmis->fetch())
		{
			if ($recupinfo == 1)
			{
				if ($donl3[0] == $c)
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
		if ($page == 1 OR $page == 2)
		{
			echo '<span id="txtHint3"></span>';
		}
	}
	else
	{
		echo 'Pas de mission n&eacute;cessaire.<input type="hidden" value="0" name="mission" />';
	}

	$repmis->closeCursor();
	 
}
?> 