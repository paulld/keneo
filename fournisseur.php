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
					$bdd->query("INSERT INTO rob_fournisseur VALUES('', '$code', '$desc', '$cat', 1)");
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
					$bdd->query("UPDATE rob_fournisseur SET code='$code', Description='$desc', typeFrnsID='$cat' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<section class="container section-container section-toggle" id="effectif-interne">
		<div class="section-title" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Liste des fournisseurs
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>
		
	<div id="toggle-content" style="display: none;">
		<table class="table table-striped" id="effectif-interne-table">
			<thead>
				<tr>
					<td id="t-containertit">Code</td>
					<td id="t-containertit">Description</td>
					<td id="t-containertit">Type de fournisseur</td>
					<td id="t-containertit" colspan="2">Actions</td>
				</tr>
			</thead>
			<tbody>
				<?php
				$req="SELECT T1.code, T1.Description, T1.actif, T1.ID, T2.categorie FROM rob_fournisseur T1
					INNER JOIN rob_catfrs T2 ON T1.typeFrnsID = T2.ID
					ORDER BY T2.categorie, T1.Description";
				$reponse = $bdd->query($req);
				$i=1;
				while ($donnee = $reponse->fetch() )
				{
				?>
					<tr>
						<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
						<td id="t-container<?php echo $i;?>"><?php echo $donnee[1];?></td>
						<td id="t-container<?php echo $i;?>"><?php echo $donnee[4];?></td>
						<?php if ($donnee[2] == 1)
						{ ?>
							<td id="t-ico<?php echo $i;?>"><form action="fournisseur.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
							<?php
						}
						else
						{
							?>
							<td id="t-ico<?php echo $i;?>"><form action="fournisseur.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
							<?php
						}
						?>
						<td id="t-ico<?php echo $i;?>"><form action="modif_fournisseur.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
					</tr>
				<?php
					if ($i == 1) { $i = 2; } else { $i = 1; }
				}
				$reponse->closeCursor();
				?>
			</tbody>
		</table>
	</div>
	</section>

	<section class="container section-container section-toggle" id="saisie-frais">
		<div class="section-title" id="toggle-title3">
			<h1>
				<i class="fa fa-chevron-up"></i>
				Ajouter un nouveau fournisseur
				<i class="fa fa-chevron-up"></i>
			</h1>
		</div>
		<div id="toggle-content3">
			<div class="form-inner">
				<form action="fournisseur.php" method="post">
					<input class="form-control form-control-small" type="text" size="50" name="newcode" placeholder="Code" />
					<input class="form-control form-control-small" type="text" size="50" name="desc" placeholder="Description" />
						<?php echo ' <select name="cat" class="form-control form-control-small" >';
							echo '<option>Type de fournisseur</option>';
							$affcollab = $bdd->query("SELECT * FROM rob_catfrs WHERE actif='1' ORDER BY categorie");
							while ($optioncoll = $affcollab->fetch())
							{
								echo '<option value='.$optioncoll['ID'].'>'.$optioncoll['categorie'].'</option>';
							}
							$affcollab->closeCursor();
						echo '</select>';
						?>
					<input class="btn btn-small btn-primary" type="submit" Value="Ajouter" />
				</form>
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