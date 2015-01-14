<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_profil SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_profil SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = strtoupper($_POST['newcode']);
				$checkcode = $bdd->query("SELECT code FROM rob_profil WHERE code='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce profil existe d&eacute;j&agrave';
				}
				else
				{
					$cat = $_POST['cat'];
					$desc = $_POST['desc'];
					$desc = str_replace("'","\'",$desc);
					$cout = $_POST['cout'];
					$bdd->query("INSERT INTO rob_profil VALUES('', '$code', '$desc', '$cat', '$cout', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = strtoupper($_POST['modcode']);
					$desc = $_POST['moddesc'];
					$desc = str_replace("'","\'",$desc);
					$cat = $_POST['modcat'];
					$cout = $_POST['modcout'];
					$bdd->query("UPDATE rob_profil SET code='$code', Description='$desc', nat2ID='$cat', coutTheo='$cout' WHERE ID='$modID'");
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
			<h1>Profils</h1>
		</div>
		
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Code</th>
						<th>Description</th>
						<th>Nature</th>
						<th style="text-align:right; padding-right: 45px;">Co&ucirc;t th&eacute;orique</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T1.code, T1.Description, T1.actif, T1.ID, T2.Description, T1.coutTheo, T3.Description FROM rob_profil T1
						INNER JOIN rob_nature2 T2 ON T1.nat2ID = T2.ID
						INNER JOIN rob_nature1 T3 ON T2.natID1 = T3.ID
						ORDER BY T3.Description, T2.Description, T1.Description";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch() ) {
					?>
						<tr>
							<td><?php echo $donnee[0];?></td>
							<td><?php echo $donnee[1];?></td>
							<td><?php echo $donnee[6];?> <?php echo $donnee[4];?></td>
							<td style="text-align:right; padding-right: 45px;"><?php echo $donnee[5];?></td>
							<?php if ($donnee[2] == 1) { ?>
								<td>
									<form action="profil.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
									</form>
								</td>
							<?php } else { ?>
								<td>
									<form action="profil.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
									</form>
								</td>
							<?php } ?>
							<td>
								<form action="modif_profil.php" method="post">
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
		</table>

		<h2>Ajouter un nouveau profil</h2>
		<form action="profil.php" method="post">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Code</th>
							<th>Description</th>
							<th>Nature 2</th>
							<th>Co&ucirc;t th&eacute;orique</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input class="form-control" type="text" size="20" name="newcode" />
							</td>
							<td>
								<input class="form-control" type="text" size="60" name="desc" />
							</td>
							<td>
							 	<select class="form-control" name="cat" id="w_input_90" >
									<option></option>
									<?php
										$req="SELECT T1.Description, T2.Description, T1.ID FROM rob_nature2 T1
											INNER JOIN rob_nature1 T2 ON T1.natID1 = T2.ID
											WHERE T1.actif=1 AND T2.actif=1
											ORDER BY T2.Description, T1.Description";
										$affcollab = $bdd->query($req);
										while ($optioncoll = $affcollab->fetch()) {
											echo '<option value='.$optioncoll[2].'>'.$optioncoll[1].' '.$optioncoll[0].'</option>';
										}
										$affcollab->closeCursor();
									?>
								</select>
							</td>
							<td>
								<input style="text-align:right" class="form-control" type="number" name="cout" />
							</td>
							<td>
								<input class="btn btn-primary" type="submit" Value="Ajouter" />
							</td>
						</tr>
					</tbody>
				</table>
			</table>
		</form>

	</section>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>