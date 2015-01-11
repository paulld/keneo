<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'] AND ($_SESSION['id_lev_tms'] >= 4 OR $_SESSION['id_lev_exp'] >= 4))
{
	include("headerlight.php");
	?>

	<div class="background-temps background-image"></div>
	<div class="overlay"></div>

	<?php
	if ($_SESSION['id_lev_tms'] >= 4)
	{
	?>
	<section class="container section-container" id="historique-temps">
		<div class="section-title">
			<h1>Export des temps</h1>
		</div>
		<form action="temps-exp.php" method="post" target="_blank">
			<div class="form-inner">
				<?php
				echo '<input class="form-control form-control-small form-control-centered" type="text" id="datejourdeb" name="datejourdeb" placeholder="A partir du..." />';
				echo '<input class="form-control form-control-small form-control-centered" type="text" id="datejourfin" name="datejourfin" placeholder="Jusqu\'au..." />';
				echo '<input class="btn btn-small btn-primary" type="submit" Value="Extraire" name="Valider" />';
				?> 
			</div>
		</form>
	</section>
	<?php
	}
	if ($_SESSION['id_lev_exp'] >= 4)
	{
	?>
	<section class="container section-container" id="historique-temps">
		<div class="section-title">
			<h1>Export des frais</h1>
		</div>
		<form action="frais-exp.php" method="post" target="_blank">
			<div class="form-inner">
			<?php
			echo '<input class="form-control form-control-small form-control-centered" type="text" id="datejourstrt" name="datejourstrt" placeholder="A partir du..." />';
			echo '<input class="form-control form-control-small form-control-centered" type="text" id="datejourend" name="datejourend" placeholder="Jusqu\'au..." />';
			//option
			echo '<select class="form-control form-control-small form-control-centered" name="validation">';
				echo'<option value="2">Valid&eacute;s seulement</option>';
				echo'<option value="1">Valid&eacute;es et en attente de validation</option>';
				echo'<option value="0">Tous les frais</option>';
			echo '</select><input class="btn btn-small btn-primary" type="submit" Value="Extraire" name="Valider" />';
			?> 
			</div>
		</form>
	</section>
	<?php
	}
	include("footer.php");
}
else
{
	header("location:accueil.php");
}
?>