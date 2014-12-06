<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="#"><span>Administration</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Administration</div>
	<ul id="mainMenuADM">
		<?php
		$men= "SELECT * FROM rob_ssmenu WHERE actif=1 AND ADM != 0 ORDER BY ADM";
 		$menu = $bdd->query($men);
 		while ($donnee = $menu->fetch())
 		{
			echo '<li><a class="typ" href="'.$donnee['lien'].'"><span>'.$donnee['desc1'].'</span></a></li>';
		}
		$menu->closeCursor();
 		?>
	</ul>
	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>