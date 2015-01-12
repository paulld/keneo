<?php
	session_start();
	include("appel_db.php");

	if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND ($_SESSION['id_lev_menu'] == 4 OR $_SESSION['id_lev_menu'] ==6)) {
		include("headerlight.php");
		// TODO: REMOVE THE 2 NEXT LINES AFTER CHECK THAT THEY'RE USELESS
		$i = 1;
		$k = "";
?>
	
<!-- Background Image Specific to each page -->
	<div class="background-db-management background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title toggle-botton-margin">
			<h1>
				Tableaux de bord
			</h1>
		</div>


	<!-- <div id="haut">Tableaux de bord</div> -->
	
	<div id="coeur" name="coeur">
		
		<div id="filt" name="filt">
			<?php include("tabbord-filter.php"); ?>
		</div>

		<div id="req" name="req">
			<?php include("tabbord-req.php"); ?>
		</div>

	</div>

	<?php
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>
