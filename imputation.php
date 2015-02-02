<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_imputl1 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_imputl1 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = strtoupper($_POST['newcode']);
				$checkcode = $bdd->query("SELECT code FROM rob_imputl1 WHERE code='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce code d\'imputation existe d&eacute;j&agrave';
				}
				else
				{
					$plan = strtoupper($_POST['plan']);
					$desc = $_POST['desc'];
					$respfact = $_POST['respfact'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("INSERT INTO rob_imputl1 VALUES('', '$code', '$desc', '$respfact', '', '', '', '', '', '', '', '', 1, '$plan')");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = strtoupper($_POST['modcode']);
					$plan = strtoupper($_POST['modplan']);
					$desc = $_POST['moddesc'];
					$respfact = $_POST['modrespfact'];
					$desc = str_replace("'","\'",$desc);
					$adresse = $_POST['modadresse'];
					$adresse = str_replace("'","\'",$adresse);
					$cp = $_POST['modcp'];
					$ville = strtoupper($_POST['modville']);
					$pays = strtoupper($_POST['modpays']);
					$telephone = $_POST['modtelephone'];
					$fax = $_POST['modfax'];
					$mail = $_POST['modmail'];
					$ntva = $_POST['modntva'];
					$bdd->query("UPDATE rob_imputl1 
						SET description='$desc', 
							respfactID='$respfact', 
							plan='$plan', 
							code='$code',
							adresse = '$adresse',
							cp = '$cp',
							ville = '$ville',
							pays = '$pays',
							telephone = '$telephone',
							fax = '$fax',
							mail = '$mail',
							ntva = '$ntva'
						WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div class="background-clients background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title toggle-botton-margin" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Ajouter un nouveau client
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>
		<form action="imputation.php" method="post" id="toggle-content" style="display: none;">
		<div class="table-responsive">
			<table class="table table-striped temp-table">
				<thead>
					<tr>
						<th>Code</th>
						<th>Client</th>
						<th>Alias</th>
						<th>Responsable</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input class="form-control" type="text" size="15" name="newcode" /></td>
						<td><input class="form-control" type="text" size="50" name="desc" /></td>
						<td><input class="form-control" type="text" size="3" name="plan" /></td>
						<td>
							<?php echo ' <select name="respfact" class="form-control" >';
								echo '<option></option>';
								$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
								while ($optioncoll = $affcollab->fetch())
								{
									echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
								}
								$affcollab->closeCursor();
							echo '</select>';
							?>
						</td>
						<td><input class="btn btn-primary" type="submit" Value="Ajouter" /></td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
	</section>

	<section class="container section-container" id="liste-clients">
		<div class="section-title">
			<h1>Client management</h1>
		</div>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Client</th>
						<th>Description</th>
						<th>Alias</th>
						<th>Responsable</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$req="SELECT T1.code code, T1.description description, T1.plan plan, T2.prenom prenom, T2.nom nom ,T1.actif actif, T1.ID id
					FROM rob_imputl1 T1
					LEFT JOIN rob_user T2 ON T1.respfactID = T2.ID
					ORDER BY T1.description";
				$reponse = $bdd->query($req);
				
				while ($donnee = $reponse->fetch() )
				{
				?>
					<tr>
						<td><?php echo $donnee['code'];?></td>
						<td><?php if ($donnee['description'] == "") { echo '-'; } else { echo $donnee['description']; }?></td>
						<td><?php if ($donnee['plan'] == "") { echo '-'; } else { echo $donnee['plan']; }?></td>
						<td><?php if ($donnee['prenom'] == "") { echo '-'; } else { echo $donnee['prenom'].' '.$donnee['nom']; }?></td>
						<?php if ($donnee['actif'] == 1) { 
							?>
							<td>
								<form action="imputation.php" method="post">
									<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDinact" />
									<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
								</form>
							</td>
							<td>
								<form action="rell1l2.php" method="post">
									<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDrel" />
									<button class="btn btn-small btn-default btn-icon btn-orange" type="submit" title="Vers les projets en relation avec ce client" name="relat"><i class="fa fa-link"></i></button>
								</form>
							</td>
							<?php
						} else {
							?>
							<td>
								<form action="imputation.php" method="post">
									<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDact" />
									<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
								</form>
							</td>
							<td>
								<form action="rell1l2.php" method="post">
									<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDrel" />
									<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Vers les projets en relation avec ce client" name="relat"><i class="fa fa-link"></i></button>
								</form>
							</td>
							<?php
						}
							?>
						<td>
							<form action="modif_imputation.php" method="post">
								<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDmodif" />
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
	</section>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>