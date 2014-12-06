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
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>Nature 2</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Nature 2</div>

	<div id="coeur">
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Nature 2</td>
				<td id="t-containertit">Compte</td>
				<td id="t-containertit">Nature 1</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT T1.Description, T1.actif, T1.ID, T2.Description, T1.Compte FROM rob_nature2 T1
				INNER JOIN rob_nature1 T2 ON T1.natID1 = T2.ID
				ORDER BY T2.Description, T1.Description";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[4];?></td>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[3];?></td>
					<?php if ($donnee[1] == 1)
					{ ?>
						<td id="t-ico<?php echo $i;?>"><form action="nature2.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
						<?php
					}
					else
					{
						?>
						<td id="t-ico<?php echo $i;?>"><form action="nature2.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
						<?php
					}
					?>
					<td id="t-ico<?php echo $i;?>"><form action="modif_nature2.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
				</tr>
			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter une nouvelle nature de niveau 2</div>
	<table id="tablerestit">
		<tr>
			<td id="t-containertit">Description</td>
			<td id="t-containertit">Compte</td>
			<td id="t-containertit">Nature1</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="nature2.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="newdesc" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="compte" /></td>
			<td id="t-container">
				<?php echo ' <select name="nat1" id="w_input_90" >';
					echo '<option></option>';
					$affcollab = $bdd->query("SELECT * FROM rob_nature1 WHERE actif='1' ORDER BY Description");
					while ($optioncoll = $affcollab->fetch())
					{
						echo '<option value='.$optioncoll['ID'].'>'.$optioncoll['Description'].'</option>';
					}
					$affcollab->closeCursor();
				echo '</select>';
				?>
			</td>
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