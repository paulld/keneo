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
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>Profils</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Profils</div>

	<div id="coeur">
		<table id="tablerestit" class="table table-striped temp-table">
			<tr>
				<td id="t-containertit">Code</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit">Nature</td>
				<td id="t-containertit">Co&ucirc;t th&eacute;orique</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT T1.code, T1.Description, T1.actif, T1.ID, T2.Description, T1.coutTheo, T3.Description FROM rob_profil T1
				INNER JOIN rob_nature2 T2 ON T1.nat2ID = T2.ID
				INNER JOIN rob_nature1 T3 ON T2.natID1 = T3.ID
				ORDER BY T3.Description, T2.Description, T1.Description";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[1];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[6];?> <?php echo $donnee[4];?></td>
					<td id="t-container<?php echo $i;?>" style="text-align:right"><?php echo $donnee[5];?></td>
					<?php if ($donnee[2] == 1)
					{ ?>
						<td id="t-ico<?php echo $i;?>"><form action="profil.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
						<?php
					}
					else
					{
						?>
						<td id="t-ico<?php echo $i;?>"><form action="profil.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
						<?php
					}
					?>
					<td id="t-ico<?php echo $i;?>"><form action="modif_profil.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
				</tr>
			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter un nouveau profil</div>
	<table id="tablerestit" class="table table-striped temp-table">
		<tr>
			<td id="t-containertit">Code</td>
			<td id="t-containertit">Description</td>
			<td id="t-containertit">Nature 2</td>
			<td id="t-containertit">Co&ucirc;t th&eacute;orique</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="profil.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="20" name="newcode" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="60" name="desc" /></td>
			<td id="t-container">
				<?php echo ' <select name="cat" id="w_input_90" >';
					echo '<option></option>';
					$req="SELECT T1.Description, T2.Description, T1.ID FROM rob_nature2 T1
						INNER JOIN rob_nature1 T2 ON T1.natID1 = T2.ID
						WHERE T1.actif=1 AND T2.actif=1
						ORDER BY T2.Description, T1.Description";
					$affcollab = $bdd->query($req);
					while ($optioncoll = $affcollab->fetch())
					{
						echo '<option value='.$optioncoll[2].'>'.$optioncoll[1].' '.$optioncoll[0].'</option>';
					}
					$affcollab->closeCursor();
				echo '</select>';
				?>
			</td>
			<td id="t-container"><input style="text-align:right" id="w_inputtxt_90" type="number" name="cout" /></td>
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