<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<!-- Background Image Specific to each page -->
		<div class="background-tables background-image"></div>
		<div class="overlay"></div>

		<?php include("partials/tablesnavbar.php"); ?>

	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>