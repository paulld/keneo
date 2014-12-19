<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headersom.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_ana1 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_ana1 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newdesc']))
			{
				$code = $_POST['newdesc'];
				$checkcode = $bdd->query("SELECT desc1 FROM rob_ana1 WHERE desc1='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce code analytique existe d&eacute;j&agrave';
				}
				else
				{
					$desc = str_replace("'","\'",$code);
					$bdd->query("INSERT INTO rob_ana1 VALUES('', '$desc', '', '', 1)");
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$desc = $_POST['moddesc'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("UPDATE rob_ana1 SET desc1='$desc' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div id="menu">
		<div id="navigationMap">
			<ul><li><a class="typ" href="accueil.php"><img src="images/RoB_Home.png" /></a></li><li><a class="typ" href="table.php"><span>Tables</span></a></li><li><a class="typ" href="#"><span>Analytique 1</span></a></li></ul>
		</div>
		<table id="tablerestit" class="table">
			<tr>
				<td id="t-containertit">Analytique 1</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT desc1, actif, ID
				FROM rob_ana1
				ORDER BY desc1";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch() )
			{
			?>
				<tr>
					<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
					<?php if ($donnee[1] == 1)
					{ ?>
						<td id="t-ico<?php echo $i;?>"><form action="ana.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver le code"></form></td>
						<?php
					}
					else
					{
						?>
						<td id="t-ico<?php echo $i;?>"><form action="ana.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer le code"></form></td>
						<?php
					}
					?>
					<td id="t-ico<?php echo $i;?>"><form action="modif_ana.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDmodif" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
				</tr>
			<?php
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div><br/>
	<div id="menu">
		<div id="subtit">AJOUTER UN NOUVEL AXE ANALYTIQUE 1</div>
		<table id="tablerestit" class="table">
			<tr>
				<td id="t-containertit">Description</td>
				<td id="t-containertit">Actions</td>
			</tr>
			<form action="ana.php" method="post">
			<tr>
				<td id="t-container"><input id="w_inputtxt_90" type="text" size="80" name="newdesc" /></td>
				<td id="t-container"><input id="w_input_90val" type="submit" Value="Ajouter" /></td>
			</tr>
			</form>
		</table>
	</div>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>