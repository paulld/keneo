<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_tva SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_tva SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = $_POST['newcode'];
				$checkcode = $bdd->query("SELECT type FROM rob_tva WHERE type='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce type de TVA existe d&eacute;j&agrave';
				}
				else
				{
					$desc = $_POST['desc'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("INSERT INTO rob_tva VALUES('', '$code', '$desc', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = $_POST['modcode'];
					$desc = $_POST['moddesc'];
					$code = str_replace("'","\'",$code);
					$bdd->query("UPDATE rob_tva SET taux='$desc', type='$code' WHERE ID='$modID'");
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
			<h1>Taux de TVA</h1>
		</div>

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Type</th>
						<th>Taux</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T1.type, T1.taux, T1.actif, T1.ID
						FROM rob_tva T1
						ORDER BY T1.type";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch() ) {
					?>
						<tr>
							<td><?php echo $donnee[0];?></td>
							<td><?php echo $donnee[1]*100 .'%';?></td>
							<?php if ($donnee[2] == 1) { ?>
								<td>
									<form action="tva.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
									</form>
								</td>
							<?php } else { ?>
								<td>
									<form action="tva.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
									</form>
								</td>
							<?php } ?>
							<td>
								<form action="modif_tva.php" method="post">
									<input type="hidden" value="<?php echo $donnee[3];?>" name="IDmodif" />
									<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
								</form>
							</td>
						</tr>
					<?php
					}
					$reponse->closeCursor();
					?>
				</tbody>
			</table>
		</div>

		<h2>Ajouter un nouveau taux</h2>
		<div class="table-responsive">
			<form action="tva.php" method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Type</th>
							<th>Taux</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="form-control" type="text" size="50" name="newcode" /></td>
							<td><input class="form-control" type="text" size="10" name="desc" placeholder="0.000" /></td>
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