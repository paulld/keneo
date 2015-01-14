<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_pole SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_pole SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = strtoupper($_POST['newcode']);
				$checkcode = $bdd->query("SELECT code FROM rob_pole WHERE code='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce code de p&ocirc;le existe d&eacute;j&agrave';
				}
				else
				{
					$desc = $_POST['desc'];
					$respfact = $_POST['respfact'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("INSERT INTO rob_pole VALUES('', '$code', '$desc', '', '', '$respfact', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = strtoupper($_POST['modcode']);
					$desc = $_POST['moddesc'];
					$respfact = $_POST['modrespfact'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("UPDATE rob_pole SET desc1='$desc', respID='$respfact', code='$code' WHERE ID='$modID'");
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
			<h1>P&ocirc;le</h1>
		</div>

		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>P&ocirc;le</th>
						<th>Description</th>
						<th>Responsable</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T1.code, T1.desc1, T2.prenom, T2.nom ,T1.actif, T1.ID
						FROM rob_pole T1
						LEFT JOIN rob_user T2 ON T1.respID = T2.ID
						ORDER BY T1.code";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch() )
					{
					?>
						<tr>
							<td><?php echo $donnee[0];?></td>
							<td><?php if ($donnee[1] == "") { echo '-'; } else { echo $donnee[1]; }?></td>
							<td><?php if ($donnee[3] == "") { echo '-'; } else { echo $donnee[3].' '.$donnee[2]; }?></td>
							<?php if ($donnee[4] == 1)
							{ ?>
								<td>
									<form action="pole.php" method="post">
										<input type="hidden" value="<?php echo $donnee[5];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
										<!-- <input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"> -->
									</form>
								</td>
								<?php
							}
							else
							{
								?>
								<td>
									<form action="pole.php" method="post">
										<input type="hidden" value="<?php echo $donnee[5];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
										<!-- <input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"> -->
									</form>
								</td>
								<?php
							}
							?>
							<td>
								<form action="modif_pole.php" method="post">
									<input type="hidden" value="<?php echo $donnee[5];?>" name="IDmodif" />
									<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
									<!-- <input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"> -->
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

		<h2>Ajouter un nouveau p&ocirc;le</h2>
		<div class="table-responsive">
			<form action="pole.php" method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Code</th>
							<th>Description</th>
							<th>Responsable</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input class="form-control" type="text" size="15" name="newcode" /></td>
							<td><input class="form-control" type="text" size="50" name="desc" /></td>
							<td>
								<select class="form-control" name="respfact" id="w_input_90" >
									<option></option>
								<?php 
									$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
									while ($optioncoll = $affcollab->fetch())
									{
										echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
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