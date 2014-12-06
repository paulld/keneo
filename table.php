<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li><li><a class="typ" href="#"><span>Tables</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Tables</div>

	<div id="coeur">
		Acc&eacute;der &agrave; la table : <select name="table" onchange="location=this.options[selectedIndex].value;" >
		<option>...</option>
		<?php
		$men= "SELECT * FROM rob_tables ORDER BY nom";
 		$menu = $bdd->query($men);
		//echo '<ul id="navigationTable">';
 		while ($donnee = $menu->fetch())
 		{
			//echo '<li><a class="typ" href="'.$donnee['lien'].'"><span>'.$donnee['nom'].'</span></a></li>';
			echo '<option value='.$donnee['lien'].'>'.$donnee['nom'].'</option>';
		}
		//echo '</ul>';
		$menu->closeCursor();
 		?>
		</select>
	</div>
	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>