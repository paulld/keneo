<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND ($_SESSION['id_lev_tms'] >= 4 OR $_SESSION['id_lev_exp'] >= 4))
{
	include("headerlight.php");
	?>

	<div class="background-db-management background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title toggle-botton-margin">
			<h1>
				Reporting
			</h1>
		</div>
	
		<div id="coeur">
			<?php
			if ($_SESSION['id_lev_tms'] >= 4)
			{
			}
			?>
		</div>
	</section>
	<?php
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>