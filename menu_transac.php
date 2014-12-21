<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="#"><span>Journal</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Journal</div>
	<ul id="mainMenuDB">
		<li><a class="typ" href="devis.php"><span>Devis</span></a></li>
		<li><a class="typ" href="#"><span>Bons de commandes</span></a></li>
		<li><a class="typ" href="#"><span>Journal</span></a></li>
	</ul>
	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>