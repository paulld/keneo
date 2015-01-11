<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$ok='';
	if (isset($_POST['Valider']))
	{
		$flag = $_POST['flag'];
		$dateval = date("Y-m-d");
		$bdd->query("UPDATE rob_frais SET validation = 2, datevalid = '$dateval' WHERE noteNum='$flag'");
		$ok = 'La note de frais '.$flag.' a &eacute;t&eacute; valid&eacute;e le '.date("d/m/Y");
	}
	else
	{
		if (isset($_POST['Rejeter']))
		{
			$flag = $_POST['flagrej'];
			$dateval = date("Y-m-d");
			$bdd->query("UPDATE rob_frais SET validation = 1, datevalid = '$dateval' WHERE noteNum='$flag'");
			$ok = 'La note de frais '.$flag.' a &eacute;t&eacute; rejet&eacute;e le '.date("d/m/Y");
		}
	}
	?>

		
    <!-- =================== SAISIE ================= -->
	<div class="background-temps background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="historique-temps">
		<div class="section-title">
			<h1>Validation des frais</h1>
		</div>
		<form action="valfrais.php" method="post">
			<div class="form-inner">
				<select class="form-control form-control-small form-control-centered" name="flag" />
					<option>S&eacute;lectionez la note...</option>
					<?php
					$reqimput = $bdd->query("SELECT DISTINCT noteNum FROM rob_frais WHERE validation != 2 AND noteNum != '' ORDER BY noteNum");
					while ($optimput = $reqimput->fetch())
					{
						echo '<option value='.$optimput['noteNum'].'>'.$optimput['noteNum'].'</option>';
					}
					$reqimput->closeCursor();
					?>
				</select>
				<input class="btn btn-small btn-primary" type="submit" Value="Valider la note" name="Valider" />
			</div>
		</form>
		<?php
			if ($ok != '' and isset($_POST['Valider']))
			{
				echo '<div class="form-error-message">'.$ok.'</b></div>';
			}
		?>
	</section>
		
	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title">
			<h1>Rejeter une note de frais</h1>
		</div>
		<form action="valfrais.php" method="post">
			<div class="form-inner">
				<input class="form-control form-control-small form-control-centered" type="text" name="flagrej" />
				<input class="btn btn-small btn-primary" type="submit" Value="Rejeter la note" name="Rejeter" />
			</div>
		</form>
		<?php
			if ($ok != '' and isset($_POST['Rejeter']))
			{
				echo '<div class="form-error-message">'.$ok.'</b></div>';
			}
		?>
	</section>
	
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>