<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_fournisseur SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_fournisseur SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = strtoupper($_POST['newcode']);
				$checkcode = $bdd->query("SELECT code FROM rob_fournisseur WHERE code='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce code fournisseur existe d&eacute;j&agrave';
				}
				else
				{
					$cat = $_POST['cat'];
					$desc = $_POST['desc'];
					$desc = str_replace("'","\'",$desc);
					$telephone = $_POST['telephone'];
					$mail = $_POST['mail'];
					$bdd->query("INSERT INTO rob_fournisseur VALUES('', '$code', '$desc', '$cat', '', '00000', '', 'FRANCE', '$telephone', '', '$mail', 1)");
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
					$adresse = $_POST['modadresse'];
					$adresse = str_replace("'","\'",$adresse);
					$ville = strtoupper($_POST['modville']);
					$ville = str_replace("'","\'",$ville);
					$pays = strtoupper($_POST['modpays']);
					$pays = str_replace("'","\'",$pays);
					$cp = $_POST['modcp'];
					$telephone = $_POST['modtelephone'];
					$fax = $_POST['modfax'];
					$mail = $_POST['modmail'];
					$bdd->query("UPDATE rob_fournisseur SET code='$code', Description='$desc', typeFrnsID='$cat', adresse='$adresse', ville='$ville', pays='$pays', cp='$cp', telephone='$telephone', fax='$fax', mail='$mail' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<!-- Background Image Specific to each page -->
	<div class="background-tables background-image"></div>
	<div class="overlay"></div>

	<?php include("partials/tablesnavbar.php"); ?>

	<section class="container section-container section-toggle" id="saisie-frais">
		<div class="section-title" id="toggle-title2">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Ajouter un nouveau fournisseur
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>
		<div id="toggle-content2" style="display: none;">
			<div class="table-responsive">
				<form action="fournisseur.php" method="post">
					<table class="table">
						<tbody>
							<td>
								<input class="form-control" type="text" size="50" name="newcode" placeholder="Code" />
							</td>
							<td>
								<input class="form-control" type="text" size="50" name="desc" placeholder="Description" />
							</td>
							<td>
								<select name="cat" class="form-control form-control-auto" >
									<option>Type de fournisseur</option>
									<?php
										$affcollab = $bdd->query("SELECT * FROM rob_catfrs WHERE actif='1' ORDER BY categorie");
										while ($optioncoll = $affcollab->fetch())
										{
											echo '<option value='.$optioncoll['ID'].'>'.$optioncoll['categorie'].'</option>';
										}
										$affcollab->closeCursor();
									?>
								</select>
							</td>
							<td>
								<input class="form-control" type="text" size="50" name="mail" placeholder="E-mail" />
							</td>
							<td>
								<input class="form-control" type="text" size="50" name="telephone" placeholder="T&eacute;l&eacute;phone" />
							</td>
							<td>
								<input class="btn btn-primary" type="submit" Value="Ajouter" />
							</td>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</section>

	<section class="container section-container section-toggle" id="effectif-interne">
		<div class="section-title" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-up"></i>
				Liste des fournisseurs
				<i class="fa fa-chevron-up"></i>
			</h1>
		</div>
		<div id="toggle-content">
			<div class="table-responsive">
				<table class="table table-striped" id="effectif-interne-table">
					<thead>
						<tr>
							<th>Code</th>
							<th>Description</th>
							<th>Type de fournisseur</th>
							<th>Mail</th>
							<th>Telephone</th>
							<th colspan="2">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$req="SELECT T1.code, T1.Description, T1.actif, T1.ID, T2.categorie, T1.telephone, T1.mail FROM rob_fournisseur T1
							INNER JOIN rob_catfrs T2 ON T1.typeFrnsID = T2.ID
							ORDER BY T2.categorie, T1.Description";
						$reponse = $bdd->query($req);
						while ($donnee = $reponse->fetch() ) {
						?>
							<tr>
								<td><?php echo $donnee['code'];?></td>
								<td><?php echo $donnee['Description'];?></td>
								<td><?php echo $donnee['categorie'];?></td>
								<td><?php if ($donnee['mail'] == '') { echo '-'; } else { echo $donnee['mail']; } ?></td>
								<td><?php if ($donnee['telephone'] == '') { echo '-'; } else { echo $donnee['telephone']; } ?></td>
								<?php if ($donnee['actif'] == 1) { ?>
									<td>
										<form action="fournisseur.php" method="post">
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" />
											<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
										</form>
									</td>
								<?php } else { ?>
									<td>
										<form action="fournisseur.php" method="post">
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" />
											<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
										</form>
									</td>
								<?php } ?>
								<td>
									<form action="modif_fournisseur.php" method="post">
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