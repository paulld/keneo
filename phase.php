<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDrock'];
		$bdd->query("UPDATE rob_phase SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDrock'];
			$bdd->query("UPDATE rob_phase SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newPhase']))
			{
				$Phase = strtoupper($_POST['newPhase']);
				$checkPhase = $bdd->query("SELECT Phase FROM rob_phase WHERE Phase='$Phase'");
				$Phasepris = $checkPhase->rowCount();
				$checkPhase->closeCursor();
				if ($Phasepris != 0)
				{
					echo 'Cette phase existe d&eacute;j&agrave';
				}
				else
				{
					$desc = $_POST['newdesc'];
					$desc = str_replace("'","\'",$desc);
					$open = $_POST['newinputOpen'];
					$bdd->query("INSERT INTO rob_phase VALUES('', '$Phase', '$desc', '$open', 1)");
				}
			}
			else
			{
				if (isset($_POST['modif']))
				{
					$modID = $_POST['IDrock'];
					$Phase = $_POST['modphase'];
					$desc = $_POST['moddesc'];
					$desc = str_replace("'","\'",$desc);
					$open = $_POST['modinputOpen'];
					$bdd->query("UPDATE rob_phase SET Description='$desc', Phase='$Phase', inputOpen='$open' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="table.php"><span>Tables</span></a></li>
		<li><a class="typ" href="#"><span>Phases</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Phases</div>

	<div id="coeur">
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Phase</td>
				<td id="t-containertit">Description</td>
				<td id="t-containertit">&Agrave; la saisie</td>
				<td id="t-containertit" colspan="2">Actions</td>
			</tr>
			<?php
			$req="SELECT Phase, Description, actif, ID, inputOpen
				FROM rob_phase
				ORDER BY Description";
			$reponse = $bdd->query($req);
			$i=1;
			while ($donnee = $reponse->fetch())
			{
				if ($donnee[2] == 1) { $verr =""; } else { $verr = " disabled"; }
				echo '<form action="phase.php" method="post"><tr>';
					echo '<td id="t-container'.$i.'"><input type="text" value="'.$donnee[0].'" name="modphase"'.$verr.' /></td>';
					echo '<td id="t-container'.$i.'"><input type="text" value="'.$donnee[1].'" name="moddesc"'.$verr.' /></td>';
					echo '<td id="t-container'.$i.'"><select name="modinputOpen"'.$verr.'>';
						if ($donnee[4] == 1) { $optsel = " selected"; } else { $optsel = ""; }
						echo '<option value="0">Inactif</option>';
						echo '<option value="1"'.$optsel.'>Actif</option>';
						echo '</select></td>';
					echo '<td id="t-ico'.$i.'"><input type="hidden" value="'.$donnee[3].'" name="IDrock" />';
						echo '<input id="btValid" name="modif" type="submit" Value="V" title="Modifier les informations"'.$verr.' />';
					echo '</td>';
					echo '<td id="t-ico'.$i.'">';
					if ($donnee[2] == 1)
					{ 
						echo '<input id="btAct" name="IDinact" type="submit" Value="A" title="Desactiver le Phase" />';
					} else {
						echo '<input id="btInact" name="IDact" type="submit" Value="X" title="Activer le Phase" />';
					}
					echo '</td>';
				echo '</tr></form>';
				if ($i == 1) { $i = 2; } else { $i = 1; }
			}
			$reponse->closeCursor();
			?>
		</table>
	</div>

	<div id="sstitre">Ajouter une nouvelle phase</div>
	<table id="tablerestit">
		<tr>
			<td id="t-containertit">Phase</td>
			<td id="t-containertit">Description</td>
			<td id="t-containertit">A la saisie</td>
			<td id="t-containertit">Actions</td>
		</tr>
		<form action="phase.php" method="post">
		<tr>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="20" name="newPhase" /></td>
			<td id="t-container"><input id="w_inputtxt_90" type="text" size="50" name="newdesc" /></td>
			<td id="t-container"><select name="newinputOpen"><option value="0">Inactif</option><option value="1">Actif</option></select></td>
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