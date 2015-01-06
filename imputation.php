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
					$bdd->query("INSERT INTO rob_imputl1 VALUES('', '$code', '$desc', '$respfact', 1, '$plan')");
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
					$bdd->query("UPDATE rob_imputl1 SET description='$desc', respfactID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div class="background-frais background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="historique-frais">

	<div class="section-title">
		<h1>Client management</h1>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<td id="t-containertit">Client</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit">Alias</td>
				<td id="t-containertit">Responsable facturation</td>
				<td id="t-containertit" colspan="3">Actions</td>
			</tr>
		</thead>
		<tbody>
		<?php
		$req="SELECT T1.code, T1.description, T1. plan, T2.prenom, T2.nom ,T1.actif, T1.ID
			FROM rob_imputl1 T1
			LEFT JOIN rob_user T2 ON T1.respfactID = T2.ID
			ORDER BY T1.description";
		$reponse = $bdd->query($req);
		$i=1;
		while ($donnee = $reponse->fetch() )
		{
		?>
			<tr>
				<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
				<td id="t-container<?php echo $i;?>"><?php if ($donnee[1] == "") { echo '-'; } else { echo $donnee[1]; }?></td>
				<td id="t-container<?php echo $i;?>"><?php if ($donnee[2] == "") { echo '-'; } else { echo $donnee[2]; }?></td>
				<td id="t-container<?php echo $i;?>"><?php if ($donnee[3] == "") { echo '-'; } else { echo $donnee[3].' '.$donnee[4]; }?></td>
				<?php if ($donnee[5] == 1)
				{ ?>
					<td id="t-ico<?php echo $i;?>"><form action="imputation.php" method="post"><input type="hidden" value="<?php echo $donnee[6];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
					<td id="t-ico<?php echo $i;?>"><form action="rell1l2.php" method="post"><input type="hidden" value="<?php echo $donnee[6];?>" name="IDrel" /><input border=0 src="images/RoB_relact.png" type=image Value=submit title="Vers les projets en relation avec ce client" name="relat"></form></td>
					<?php
				}
				else
				{
					?>
					<td id="t-ico<?php echo $i;?>"><form action="imputation.php" method="post"><input type="hidden" value="<?php echo $donnee[6];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
					<td id="t-ico<?php echo $i;?>"><form action="rell1l2.php" method="post"><input type="hidden" value="<?php echo $donnee[6];?>" name="IDrel" /><input border=0 src="images/RoB_reldeact.png" type=image Value=submit title="Vers les projets en relation avec ce client" name="relat"></form></td>
					<?php
				}
				?>
				<td id="t-ico<?php echo $i;?>"><form action="modif_imputation.php" method="post"><input type="hidden" value="<?php echo $donnee[6];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
			</tr>
		<?php
			if ($i == 1) { $i = 2; } else { $i = 1; }
		}
		$reponse->closeCursor();
		?>
		</tbody>
	</table>

	<h2>Ajouter un nouveau client</h2>
	<table id="tablerestit" class="table table-striped temp-table">
		<tr>
			<td id="t-containertit">Code</td>
			<td id="t-containertit">Client</td>
			<td id="t-containertit">Alias</td>
			<td id="t-containertit">Responsable</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="imputation.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="15" name="newcode" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="desc" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="3" name="plan" /></td>
			<td id="t-container">
				<?php echo ' <select name="respfact" id="w_input_90" >';
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
			<td id="t-container"><input id="w_input_90val" type="submit" Value="Ajouter" /></td>
		</tr>
		</form>
	</table>
	</section>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>