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

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title toggle-botton-margin" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Validation des temps
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>

		<?php
		include("valtemps-coeur.php");
		?>

	</section>

	<?php
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>
