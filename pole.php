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
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>P&ocirc;le</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">P&ocirc;le</div>

	<div id="coeur">
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">P&ocirc;le</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit">Responsable</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT T1.code, T1.desc1, T2.prenom, T2.nom ,T1.actif, T1.ID
				FROM rob_pole T1
				LEFT JOIN rob_user T2 ON T1.respID = T2.ID
				ORDER BY T1.code";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
					<td id="t-container<?php echo $i;?>"><?php if ($donnee[1] == "") { echo '-'; } else { echo $donnee[1]; }?></td>
					<td id="t-container<?php echo $i;?>"><?php if ($donnee[3] == "") { echo '-'; } else { echo $donnee[3].' '.$donnee[2]; }?></td>
					<?php if ($donnee[4] == 1)
					{ ?>
						<td id="t-ico<?php echo $i;?>"><form action="pole.php" method="post"><input type="hidden" value="<?php echo $donnee[5];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
						<?php
					}
					else
					{
						?>
						<td id="t-ico<?php echo $i;?>"><form action="pole.php" method="post"><input type="hidden" value="<?php echo $donnee[5];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
						<?php
					}
					?>
					<td id="t-ico<?php echo $i;?>"><form action="modif_pole.php" method="post"><input type="hidden" value="<?php echo $donnee[5];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
				</tr>
			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter un nouveau p&ocirc;le</div>
	<table id="tablerestit">
		<tr>
			<td id="t-containertit">Code</td>
			<td id="t-containertit">Description</td>
			<td id="t-containertit">Responsable</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="pole.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="15" name="newcode" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="desc" /></td>
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
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>