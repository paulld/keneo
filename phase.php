<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDrock'];
		$bdd->query("UPDATE rob_phase SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDrock'];
			$bdd->query("UPDATE rob_phase SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newPhase']))
			{
				$Phase = strtoupper($_POST['newPhase']);
				$checkPhase = $bdd->query("SELECT Phase FROM rob_phase WHERE Phase='$Phase'");
				$Phasepris = $checkPhase->rowCount();
				$checkPhase->closeCursor();
				if ($Phasepris != 0)
				{
					echo 'Cette phase existe d&eacute;j&agrave';
				}
				else
				{
					$desc = $_POST['newdesc'];
					$desc = str_replace("'","\'",$desc);
					$open = $_POST['newinputOpen'];
					$bdd->query("INSERT INTO rob_phase VALUES('', '$Phase', '$desc', '$open', 1)");
				}
			}
			else
			{
				if (isset($_POST['modif']))
				{
					$modID = $_POST['IDrock'];
					$Phase = $_POST['modphase'];
					$desc = $_POST['moddesc'];
					$desc = str_replace("'","\'",$desc);
					$open = $_POST['modinputOpen'];
					$bdd->query("UPDATE rob_phase SET Description='$desc', Phase='$Phase', inputOpen='$open' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<!-- Background Image Specific to each page -->
	<div class="background-tables background-image"></div>
	<div class="overlay"></div>

	<?php include("partials/tablesnavbar.php"); ?>

	<section class="container section-container" id="saisie-frais">
		<div class="section-title">
			<h1>Phases</h1>
		</div>

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Phase</th>
						<th>Description</th>
						<th>&Agrave; la saisie</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT Phase, Description, actif, ID, inputOpen
						FROM rob_phase
						ORDER BY Description";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch())
					{
						if ($donnee[2] == 1) { $verr =""; } else { $verr = " disabled"; }
						echo '<form action="phase.php" method="post"><tr>';
							echo '<td><input class="form-control" type="text" value="'.$donnee[0].'" name="modphase"'.$verr.' /></td>';
							echo '<td><input class="form-control" type="text" value="'.$donnee[1].'" name="moddesc"'.$verr.' /></td>';
							echo '<td><select class="form-control" name="modinputOpen"'.$verr.'>';
								if ($donnee[4] == 1) { $optsel = " selected"; } else { $optsel = ""; }
								echo '<option value="0">Inactif</option>';
								echo '<option value="1"'.$optsel.'>Actif</option>';
								echo '</select></td>';
							echo '<td><input type="hidden" value="'.$donnee[3].'" name="IDrock" />';
								echo '<input class="btn btn-primary" name="modif" type="submit" Value="V" title="Valider les modifications"'.$verr.' />';
							echo '</td>';
							echo '<td>';
							if ($donnee[2] == 1)
							{ 
								echo '<input class="btn btn-danger" id="btAct" name="IDinact" type="submit" Value="X" title="D&eacute;sactiver la Phase" />';
							} else {
								echo '<input class="btn btn-success" id="btInact" name="IDact" type="submit" Value="A" title="Activer la Phase" />';
							}
							echo '</td>';
						echo '</tr></form>';
					}
					$reponse->closeCursor();
					?>
				</tbody>
			</table>
		</div>

		<h2>Ajouter une nouvelle phase</h2>
		<div class="table-responsive">
			<form action="phase.php" method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Phase</th>
							<th>Description</th>
							<th>A la saisie</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="form-control" type="text" size="20" name="newPhase" /></td>
							<td><input class="form-control" type="text" size="50" name="newdesc" /></td>
							<td><select class="form-control" name="newinputOpen"><option value="0">Inactif</option><option value="1">Actif</option></select></td>
							<td><input class="btn btn-primary" type="submit" Value="Ajouter" /></td>
						</tr>
					</tbody>
				</table>
			</form>
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