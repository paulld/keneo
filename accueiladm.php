<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{

	//Ecriture de la page
	include("headersom.php");
	?>
	<div id="menu">
		<div id="navigationMap">
			<ul><li><a class="typ" href="#"><img src="images/RoB_Home.png" /></a></li></ul>
		</div>
		<?php
		$men= "SELECT * FROM rob_menus WHERE actif=1 ORDER BY code";
 		$menu = $bdd->query($men);
 		while ($donmen = $menu->fetch())
 		{
			$i = 1;
			$curr_menu_id=$donmen['ID'];
			$req= "SELECT T1.lien, T1.desc1 FROM rob_ssmenu T1
					INNER JOIN rob_menu_rights T3 ON T1.ID = T3.ssmenu_id AND T1.menu_id=T3.menu_id
					WHERE T1.actif = 1 AND T3.level = ".$_SESSION['id_lev_menu']." AND T1.menu_id = ".$donmen['ID']." 
					ORDER BY T1.code";
			$reponse = $bdd->query($req);
			$checkrep = $reponse->rowCount();
			if ($checkrep != 0)
			{
				echo '<ul id="navigationMenu'.$curr_menu_id.'">';
				while ($donnee = $reponse->fetch())
				{
					echo '<li><a class="typ" href="'.$donnee['lien'].'"><span>'.$donnee['desc1'].'</span></a></li>';
					if ($i == 1) { $i=2; } else { $i=1; }
				}
				echo '</ul>';
			}
			$reponse->closeCursor();
		}
		$menu->closeCursor();
 		?>	</div>
	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>