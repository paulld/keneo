<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_activite SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_activite SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = strtoupper($_POST['newcode']);
				$checkcode = $bdd->query("SELECT code FROM rob_activite WHERE code='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Cette activit&eacute; existe d&eacute;j&agrave';
				}
				else
				{
					$desc = $_POST['newdesc'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("INSERT INTO rob_activite VALUES('', '$code', '$desc', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = $_POST['modcode'];
					$desc = $_POST['moddesc'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("UPDATE rob_activite SET Description='$desc', code='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>Activit&eacute;s</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Activit&eacute;s</div>

	<div id="coeur">
		<table id="tablerestit" class="table table-striped temp-table">
			<tr>
				<td id="t-containertit">Code</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT code, Description, actif, ID
				FROM rob_activite
				ORDER BY Description";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[1];?></td>
					<?php if ($donnee[2] == 1)
					{ ?>
						<td id="t-ico<?php echo $i;?>"><form action="activite.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
						<?php
					}
					else
					{
						?>
						<td id="t-ico<?php echo $i;?>"><form action="activite.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
						<?php
					}
					?>
					<td id="t-ico<?php echo $i;?>"><form action="modif_activite.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
				</tr>
			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter une nouvelle activit&eacute;</div>
	<table id="tablerestit" class="table table-striped temp-table">
		<tr>
			<td id="t-containertit">Code</td>
			<td id="t-containertit">Description</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="activite.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="20" name="newcode" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="newdesc" /></td>
			<td id="t-container"><input id="w_input_90val" type="submit" Value="Ajouter" /></td>
		</tr>
		</form>
	</table>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>