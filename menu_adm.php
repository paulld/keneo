<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND $_SESSION['id_lev_menu'] >= 4) 
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
		if ($_SESSION['id_lev_menu'] == 6)
		{
			$men= "SELECT * FROM rob_ssmenu T1 WHERE T1.actif=1 AND T1.ADM != 0 ORDER BY ADM";
		} else {
			$men= "SELECT T1.lien, T1.desc1 FROM rob_ssmenu T1
			INNER JOIN rob_menu_rights T2 ON T1.ID = T2.ssmenu_id
			WHERE T1.actif=1 AND T1.ADM != 0 AND T2.menu <= ".$_SESSION['id_lev_menu']."
			ORDER BY ADM";
		}
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
	header("location:accueil.php");
}
?>