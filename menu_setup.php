<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND ($_SESSION['id_lev_menu'] == 4 OR $_SESSION['id_lev_menu'] ==6)) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	
<!-- Background Image Specific to each page -->
	<div class="background-db-management background-image"></div>
	<div class="overlay"></div>
	
	<div id="mainMenuDB" class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1 col-sm-12">
				<div class="row">
					<?php
					if ($_SESSION['id_lev_menu'] == 6)
					{
						$men= "SELECT * FROM rob_ssmenu WHERE actif=1 AND DB != 0 ORDER BY DB";
					} else {
						$men= "SELECT * FROM rob_ssmenu T1 
							INNER JOIN rob_menu_rights T2 ON T1.ID = T2.ssmenu_id
							WHERE actif=1 AND DB != 0 AND T2.menu = ".$_SESSION['id_lev_menu']."
							ORDER BY DB";
					}
			 		$menu = $bdd->query($men);
			 		while ($donnee = $menu->fetch())
			 		{
			 			echo '<div class="col-lg-3 col-sm-4 col-xs-6">';
			 				echo '<div class="menu-item-outer">';
			 					echo '<a href="'.$donnee['lien'].'"><span class="menu-link"></span></a>';
			 					echo '<i class="fa fa-'.$donnee['ID'].'"></i><p>'.$donnee['desc1'].'</p>';
			 				echo '</div>';
			 			echo '</div>';
					}
					$menu->closeCursor();
			 		?>
			 	</div>
	 		</div>
 		</div>
	</div>
<?php include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>