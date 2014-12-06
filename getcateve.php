<?php
if(isset($c) AND isset($t) AND isset($e)) { $recupinfo = 1; } else {session_start(); $recupinfo = 0; $c = intval($_GET['c']); $t = intval($_GET['t']); $e = intval($_GET['e']); }
include("appel_db.php");
if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$mis= "SELECT * FROM rob_comprel3 WHERE actif = 1 AND imputID = ".$c." AND imputID2 = ".$t." AND imputID3 = ".$e;

	$repmis = $bdd->query($mis);
	$checkmis = $repmis->rowCount();
	if ($checkmis != 0)
	{
		$donl3 = $repmis->fetch();
		if (date("Y", strtotime($donl3['date'])) == 1970) { $dateTmp = date("d/m/Y", strtotime($donl3['date'])); } else { $dateTmp = date("d/m/Y"); }
		echo $donl3['lieu'].' - '.$dateTmp;
	}
	$repmis->closeCursor();
	 
}
?> 