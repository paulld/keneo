<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
<!-- Background Image Specific to each page -->
	<div class="background-transactions background-image"></div>
	<div class="overlay"></div>

	<div class="container nav-tabs-outer" id="mainMenuDB">
		<ul class="nav nav-tabs nav-justified">
			<li class="active"><a role="presentation" href="#"><span>Vue d'Ensemble</span></a></li>
			<li><a role="presentation" href="devis.php"><span>Devis</span></a></li>
			<li><a role="presentation" href="#"><span>Bons de commandes</span></a></li>
			<li><a role="presentation" href="#"><span>Journal</span></a></li>
		</ul>
	</div>

	<section class="container section-container" id="transaction-dashboard">
		<div class="section-title">
			<h1>Ma Vue d'Ensemble</h1>
		</div>
	</section>

	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>