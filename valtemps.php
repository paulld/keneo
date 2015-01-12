<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND $_SESSION['id_lev_tms'] >= 4)
{
	include("headerlight.php");

	if (isset($_POST['valtps']))
	{
		$clientid = $_POST['vimputIDl1'];
		$projetid = $_POST['vimputIDl2'];
		$missionid = $_POST['vimputIDl3'];
		$userid = $_POST['vuserID'];
		$mois = $_POST['vdatejour'];
		$validation = $_POST['valtps'];
		$matricule = $_SESSION['ID'];
		$req = "UPDATE rob_temps SET validation = '$validation', userValidID = '$matricule' WHERE userID = '$userid' AND imputID = '$clientid' AND imputIDl2 = '$projetid' AND imputIDl3 = '$missionid' AND MONTH(datejour) = '$mois'";
		$bdd->query($req);
	}
	?>
	
	<div class="background-temps background-image"></div>
	<div class="overlay"></div>

	<?php
	include("valtemps-coeur.php");

	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>
