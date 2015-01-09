<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND $_SESSION['id_lev_tms'] >= 4)
{
include("headerlight.php");

	$i = 1;
	$k = "";
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
