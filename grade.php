<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDrock'];
		$bdd->query("UPDATE rob_grade SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDrock'];
			$bdd->query("UPDATE rob_grade SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newPhase']))
			{
				$grade = $_POST['newGrade'];
				$checkPhase = $bdd->query("SELECT grade FROM rob_grade WHERE grade='$grade'");
				$gradepris = $checkPhase->rowCount();
				$checkPhase->closeCursor();
				if ($gradepris != 0)
				{
					echo 'Ce grade existe d&eacute;j&agrave';
				}
				else
				{
					$seuil = $_POST['newSeuil'];
					$bdd->query("INSERT INTO rob_grade VALUES('', '$grade', '$seuil', 1)");
				}
			}
			else
			{
				if (isset($_POST['modif']))
				{
					$modID = $_POST['IDrock'];
					$grade = $_POST['modgrade'];
					$seuil = $_POST['modseuil'];
					$bdd->query("UPDATE rob_grade SET grade='$grade', seuil='$seuil' WHERE ID='$modID'");
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
			<h1>Grades</h1>
		</div>

		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Grade</th>
						<th>Seuil</th>
						<th colspan="2" class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT grade, seuil, actif, ID
						FROM rob_grade
						ORDER BY grade";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch()) {
						if ($donnee['actif'] == 1) { $verr =""; } else { $verr = " disabled"; }
						?>
						<form action="grade.php" method="post">
							<tr>
							<?php
							echo '<td><input class="form-control" type="text" value="'.$donnee['grade'].'" name="modgrade"'.$verr.' /></td>';
							echo '<td><input class="form-control" type="text" value="'.$donnee['seuil'].'" name="modseuil"'.$verr.' /></td>';
							?>
							<td class="text-center">
								<?php
								echo '<input type="hidden" value="'.$donnee['ID'].'" name="IDrock" />'; 
									// echo '<input class="btn btn-primary" name="modif" type="submit" Value="V" title="Valider les modifications"'.$verr.'/>'; 
								echo '<button class="btn btn-icon btn-blue" name="modif" type="submit" title="Valider les modifications"'.$verr.'><i class="fa fa-floppy-o"></i></button>'; 
								?>
							</td>
							<td class="text-center">
								<?php if ($donnee['actif'] == 1) { ?>
									<!-- <input class="btn btn-danger" id="btAct" name="IDinact" type="submit" Value="X" title="D&eacute;sactiver le grade" /> -->
									<button class="btn btn-icon btn-green" id="btAct" name="IDinact" type="submit" title="D&eacute;sactiver le Grade"><i class="fa fa-toggle-on"></i></button>
								<?php } else { ?>
									<!-- <input class="btn btn-success" id="btInact" name="IDact" type="submit" Value="A" title="Activer le grade" /> -->
									<button class="btn btn-icon btn-red" id="btInact" name="IDact" type="submit" title="Activer le Grade"><i class="fa fa-toggle-off"></i></button>
								<?php } ?>
								</td>
							</tr>
						</form>
					<?php }
					$reponse->closeCursor();
					?>
				</tbody>
			</table>
		</div>

		<h2>Ajouter un nouveau grade</h2>
		<div class="table-responsive">
			<form action="grade.php" method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Grade</th>
							<th>Seuil</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="form-control" type="text" size="20" name="newGrade" /></td>
							<td><input class="form-control" type="text" size="50" name="newSeuil" /></td>
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