<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND $_SESSION['id_lev_tms'] >= 4)
{
include("headerlight.php");

	$i = 1;
	$k = "";
	?>
	
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_adm.php">Administration</a></li>
		<li><a class="typ" href="#"><span>Validation des temps</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Validation des temps</div>
	
	<div id="coeur" name="coeur">
		<?php
		include("valtemps-coeur.php");
		?>

	</div>

	<?php
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>
