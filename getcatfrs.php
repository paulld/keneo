<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	$f = intval($_GET['f']);
		
	$pro= "SELECT T2.ID, T2.code, T2.categorie FROM rob_catfrs T2
			INNER JOIN rob_fournisseur T1 ON T2.ID = T1.typeFrnsID
			WHERE T1.actif = 1 AND T2.actif = 1 AND T1.ID = ".$f;

	$reppro = $bdd->query($pro);
	$optimput = $reppro->fetch();
	echo ' [<input type="hidden" name="frsType" value="'.$optimput[0].'" />'.$optimput[2].']';
	$reppro->closeCursor();
	
}
?> 