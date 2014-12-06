<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_tva SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_tva SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = $_POST['newcode'];
				$checkcode = $bdd->query("SELECT type FROM rob_tva WHERE type='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce type de TVA existe d&eacute;j&agrave';
				}
				else
				{
					$desc = $_POST['desc'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("INSERT INTO rob_tva VALUES('', '$code', '$desc', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = $_POST['modcode'];
					$desc = $_POST['moddesc'];
					$code = str_replace("'","\'",$code);
					$bdd->query("UPDATE rob_tva SET taux='$desc', type='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>Taux de TVA</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Taux de TVA</div>

	<div id="coeur">
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Type</td>
				<td id="t-containertit">Taux</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT T1.type, T1.taux, T1.actif, T1.ID
				FROM rob_tva T1
				ORDER BY T1.type";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
					<td id="t-container<?php echo $i;?>" align="right"><?php echo $donnee[1]*100 .'%';?></td>
					<?php if ($donnee[2] == 1)
					{ ?>
						<td id="t-ico<?php echo $i;?>"><form action="tva.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
						<?php
					}
					else
					{
						?>
						<td id="t-ico<?php echo $i;?>"><form action="tva.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
						<?php
					}
					?>
					<td id="t-ico<?php echo $i;?>"><form action="modif_tva.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
				</tr>
			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter un nouveau taux</div>
	<table id="tablerestit">
		<tr>
			<td id="t-containertit">Type</td>
			<td id="t-containertit">Taux</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="tva.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="newcode" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="10" name="desc" placeholder="0.000" /></td>
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