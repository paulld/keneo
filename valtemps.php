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
	
	//Table hiérarchie
	$req = "CREATE TABLE IF NOT EXISTS tmp_hier".$_SESSION['ID']." (
		`respID` int(11) NOT NULL,
		`userID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$bdd->query($req);
	$req = "TRUNCATE TABLE tmp_hier".$_SESSION['ID']."";
	$bdd->query($req);
	$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT ".$_SESSION['ID'].", ID FROM rob_user_rights WHERE id_hier = ".$_SESSION['ID'].")";
	$bdd->query($req);
	$req = "INSERT INTO tmp_hier".$_SESSION['ID']." (SELECT ".$_SESSION['ID'].", a.ID FROM rob_user_rights a INNER JOIN tmp_hier".$_SESSION['ID']." b ON a.id_hier = b.userID)";
	$bdd->query($req);
	$req = "INSERT INTO tmp_hier".$_SESSION['ID']." VALUES (".$_SESSION['ID'].", ".$_SESSION['ID'].")";
	$bdd->query($req);
	?>
	
	<div class="background-temps background-image"></div>
	<div class="overlay"></div>

	<div id="filt" name="filt">
		<?php include("valtemps-filter.php"); ?>
	</div>

	<div id="req" name="req">
		<?php include("valtemps-req.php"); ?>
	</div>

	<?php 
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>
