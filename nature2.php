<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_nature2 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_nature2 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newdesc']))
			{
				$code = $_POST['newdesc'];
				$checkcode = $bdd->query("SELECT Description FROM rob_nature2 WHERE Description='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Cette nature 2 existe d&eacute;j&agrave';
				}
				else
				{
					$nat1 = $_POST['nat1'];
					$compte = $_POST['compte'];
					$desc = str_replace("'","\'",$code);
					$bdd->query("INSERT INTO rob_nature2 VALUES('', '$desc', '$compte', '$nat1', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$compte = $_POST['modcompte'];
					$desc = $_POST['moddesc'];
					$desc = str_replace("'","\'",$desc);
					$nat1 = $_POST['modnat1'];
					$bdd->query("UPDATE rob_nature2 SET Description='$desc', natID1='$nat1', Compte='$compte' WHERE ID='$modID'");
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
			<h1>Nature 2</h1>
		</div>
		
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nature 2</th>
						<th>Compte</th>
						<th>Nature 1</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T1.Description, T1.actif, T1.ID, T2.Description, T1.Compte FROM rob_nature2 T1
						INNER JOIN rob_nature1 T2 ON T1.natID1 = T2.ID
						ORDER BY T2.Description, T1.Description";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch() ) {
					?>
						<tr>
							<td><?php echo $donnee[0];?></td>
							<td><?php echo $donnee[4];?></td>
							<td><?php echo $donnee[3];?></td>
							<?php if ($donnee[1] == 1) { ?>
								<td>
									<form action="nature2.php" method="post">
										<input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
									</form>
								</td>
							<?php } else { ?>
								<td>
									<form action="nature2.php" method="post">
										<input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
									</form>
								</td>
							<?php } ?>
							<td>
								<form action="modif_nature2.php" method="post">
									<input type="hidden" value="<?php echo $donnee[2];?>" name="IDmodif" />
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

		<h2>Ajouter une nouvelle nature de niveau 2</h2>
		<div class="table-responsive">
			<form action="nature2.php" method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Description</th>
							<th>Compte</th>
							<th>Nature1</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="form-control" type="text" size="50" name="newdesc" /></td>
							<td><input class="form-control" type="text" size="50" name="compte" /></td>
							<td>
								<select name="nat1" class="form-control">
									<option></option>
								<?php 
									$affcollab = $bdd->query("SELECT * FROM rob_nature1 WHERE actif='1' ORDER BY Description");
									while ($optioncoll = $affcollab->fetch())
									{
										echo '<option value='.$optioncoll['ID'].'>'.$optioncoll['Description'].'</option>';
									}
									$affcollab->closeCursor();
								?>
								</select>
							</td>
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