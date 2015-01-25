<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['newdateFerie']))
	{
		$dateFerie = date('Y-m-d',mktime(0,0,0,substr($_POST['newdateFerie'],3,2),substr($_POST['newdateFerie'],0,2),substr($_POST['newdateFerie'],6,4)));
		$checkcode = $bdd->query("SELECT dateFerie FROM rob_ferie WHERE dateFerie='$dateFerie'");
		$codepris = $checkcode->rowCount();
		$checkcode->closeCursor();
		if ($codepris != 0)
		{
			echo 'Ce jour f&eacute;ri&eacute; existe d&eacute;j&agrave';
		}
		else
		{
			$desc = $_POST['newdesc'];
			$desc = str_replace("'","\'",$desc);
			$bdd->query("INSERT INTO rob_ferie VALUES('$dateFerie', '$desc')");
		}
	}
	else
	{
		if (isset($_POST['modif']))
		{
			$dateFerie = $_POST['moddateFerie'];
			$desc = $_POST['moddesc'];
			$desc = str_replace("'","\'",$desc);
			$bdd->query("UPDATE rob_ferie SET Description='$desc' WHERE dateFerie='$dateFerie'");
		}
		else
		{
			if (isset($_POST['Suppr']))
			{
				$dateFerie = $_POST['moddateFerie'];
				$bdd->query("DELETE FROM rob_ferie WHERE dateFerie='$dateFerie'");
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
			<h1>Jours f&eacute;ri&eacute;s</h1>
		</div>

		<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>Description</th>
							<th width="60px">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$req="SELECT dateFerie, Description
							FROM rob_ferie
							ORDER BY dateFerie DESC";
						$reponse = $bdd->query($req);
						while ($donnee = $reponse->fetch() ) {
						?>
						<form action="ferie.php" method="post">
							<tr>
								<td>
									<?php echo date("d/m/Y", strtotime($donnee[0]));?>
								</td>
								<td>
									<input class="form-control" type="text" size="50" value="<?php echo $donnee[1];?>" name="moddesc" />
								</td>
								<td style="width: 92px;">
									<input type="hidden" value="<?php echo $donnee[0];?>" name="moddateFerie" />
									<!-- <input class="btn btn-success" id="btValid" type="submit" value="V" title="Valider la modification" name="modif"> -->
									<button class="btn btn-icon btn-blue" id="btValid" type="submit" title="Valider la modification" name="modif"><i class="fa fa-floppy-o"></i></button>
									<!-- <input class="btn btn-danger" id="btSuppr" type="submit" value="S" title="Supprimer la ligne" name="Suppr" onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\'))" /> -->
									<button class="btn btn-icon btn-red" id="btSuppr" type="submit" title="Supprimer la ligne" name="Suppr" onclick="return(confirm(\'Etes-vous sur de vouloir supprimer cette entree?\'))"><i class="fa fa-trash-o"></i></button>
								</td>
							</tr>
						</form>
						<?php
						}
						$reponse->closeCursor();
						?>
					</tbody>
				</table>
		</div>

		<h2>Ajouter un nouveau jour f&eacute;ri&eacute;</h2>
		<div class="table-responsive">
			<form action="ferie.php" method="post">
				<table class="table table-striped temp-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Description</th>
							<th>Action</td>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td><input class="form-control" type="text" size="20" name="newdateFerie" /></td>
						<td><input class="form-control" type="text" size="50" name="newdesc" /></td>
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