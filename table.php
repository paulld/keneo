<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<div class="background-frais background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="saisie-frais">
	<div class="section-title">
		<h1>
			Tables
		</h1>
	</div>

	<div class="form-inner">
		Acc&eacute;der &agrave; la table : <select class="form-control form-control-small" name="table" onchange="location=this.options[selectedIndex].value;" >
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
	</section>
	<?php
	include("footer.php");
}
else
{ 
	header("location:index.php");
}
?>