<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	$info = '';
	$test = '';
	$errvar = 0;
	$imputtmp = 0;
	$imput2tmp = 0;

	//Variables relationelles
	if (isset($_POST['IDrel']) ) { $imputtmp = $_POST['IDrel']; } else { if (isset($_GET['IDrel'])) { $imputtmp = $_GET['IDrel']; } else { $errvar = 1; } };
	if (isset($_POST['IDrel2']) ) { $imput2tmp = $_POST['IDrel2']; } else { if (isset($_GET['IDrel2'])) { $imput2tmp = $_GET['IDrel2']; } else { $errvar = 2; } };

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_comprel3 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_comprel3 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if ($errvar == 0 AND isset($_POST['newcomb3']) AND $_POST['newcomb3'] != '' OR $errvar == 0 AND isset($_POST['newevecode']) AND $_POST['newevecode'] != '')
			{
				if ($imputtmp != 0 AND $imput2tmp != 0)
				{
					if (isset($_POST['newevecode']))
					{
						if ($_POST['newevecode'] != '')
						{
							$newevecode = strtoupper($_POST['newevecode']);
							$reponsesel = $bdd->query("SELECT * FROM rob_compl3 WHERE code='$newevecode'");
							$checkrep = $reponsesel->rowCount();
							$reponsesel->closeCursor();
							$neweveplan = strtoupper($_POST['neweveplan']);
							$newevedesc = $_POST['newevedesc'];
							$newevedesc = str_replace("'","\'",$newevedesc);
							$neweveresp = $_POST['neweveresp'];
							if ($checkrep != 0)
							{
								$info = 'Cet &Eacute;v&eacute;nement &eacute;xiste d&eacute;j&agrave';
							}
							else
							{
								$bdd->query("INSERT INTO rob_compl3 VALUES('', '$newevecode', '$newevedesc', '$neweveresp', 1, '$neweveplan')") or die();
							}
							$reponsesel = $bdd->query("SELECT ID FROM rob_compl3 WHERE code='$newevecode' LIMIT 1");
							$checkrep = $reponsesel->rowCount();
							if ($checkrep != 0)
							{
								$donneesel = $reponsesel->fetch();
								$newcomb3 = $donneesel['ID'];
							}
							else
							{
								$test = 'Probl&egrave;me au moment de l\'insertion de l\'&Eacute;v&eacute;nement';
							}
							$reponsesel->closeCursor();
						}
						else
						{
							$test = 'Code &Eacute;v&eacute;nement inexistant (1)';
						}
					}
					else
					{
						if (isset($_POST['newcomb3']))
						{
							if ($_POST['newcomb3'] != 0)
							{
								$newcomb3 = $_POST['newcomb3'];
							}
							else
							{
								$test = 'Code &Eacute;v&eacute;nement inexistant (2)';
							}
						}
						else
						{
							$test = 'Code &Eacute;v&eacute;nement inexistant (3)';
						}
					}
					if ($test == '')
					{
						if (isset($_POST['respfact'])) { $respfact = $_POST['respfact']; } else { $respfact = 1; }
						$reponse = $bdd->query("SELECT ID FROM rob_comprel3 WHERE imputID='$imputtmp' AND imputID2='$imput2tmp' AND imputID3='$newcomb3'");
						$checkrep = $reponse->rowCount();
						if ($checkrep != 0)
						{
							$info = $info.'. Cette combinaison existe d&eacute;j&agrave';
						}
						else
						{
							if (isset($_POST['rellieu'])) { $rellieu = strtoupper($_POST['rellieu']); } else { $rellieu = ""; }
							if (isset($_POST['reldate'])) { $reldate = $_POST['reldate']; } else { $reldate = "0000-00-00"; }
							$bdd->query("INSERT INTO rob_comprel3 VALUES('', '$imputtmp', '$imput2tmp', '$newcomb3', 1, '$rellieu', '$reldate')");
						}
						$reponse->closeCursor();
					}
				}
				else
				{
					$test = 'Probl&egrave;me code comp&eacute;tition/ type, merci de recharger la page';
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
					$desc = str_replace("'","\'",$desc);
					$respfact = $_POST['modrespfact'];
					$bdd->query("UPDATE rob_compl3 SET description='$desc', respID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
				}
				else
				{
					if (isset($_POST['modIDrel']))
					{
						$modIDrel = $_POST['modIDrel'];
						$lieu = strtoupper($_POST['modlieu']);
						$date = $_POST['moddate'];
						$bdd->query("UPDATE rob_comprel3 SET lieu='$lieu', date='$date' WHERE ID='$modIDrel'");
					}
				}
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul>
			<li><a class="typ" href="accueil.php">Home</a></li>
			<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
			<li><a class="typ" href="competition.php"><span>Comp&eacute;tition</span></a></li>
			<?php
			if (isset($_POST['IDrel']))
			{ echo '<li><a class="typ" href="comprell1l2.php?IDrel='.$_POST['IDrel'].'"><span>Comp&eacute;tition-Types</span></a></li>'; }
			else { echo '<li><a class="typ" href="comprell1l2.php?IDrel='.$_GET['IDrel'].'"><span>Comp&eacute;tition-Types</span></a></li>'; }
			?>
			<li><a class="typ" href="#"><span>Comp&eacute;tition-Type-&Eacute;v&eacute;nements</span></a></li>
		</ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Comp&eacute;tition-Type-&Eacute;v&eacute;nements management</div>

	<?php
	if ($imputtmp !=0 AND $imput2tmp !=0 AND $errvar == 0)
	{
		?>
		<div id="coeur">
			<table id="tablerestit">
				<tr>
					<td id="t-containertit">Comp&eacute;tition</td>
					<td id="t-containertit">Type</td>
					<td id="t-containertit">&Eacute;v&eacute;nement</td>
					<td id="t-containertit">Description</td>
					<td id="t-containertit">Lieu</td>
					<td id="t-containertit">Date</td>
					<td id="t-containertit" colspan="3">Actions</td>
				</tr>
				<?php
				$req="SELECT T3.code, T3.description, T0.ID, T0.imputID, T0.imputID2, T0.imputID3, T0.actif, T1.code, T2.code, T0.lieu, T0.date
					FROM rob_comprel3 T0
					INNER JOIN rob_compl3 T3 ON T3.ID = T0.imputID3
					INNER JOIN rob_compl2 T2 ON T2.ID = T0.imputID2
					INNER JOIN rob_compl1 T1 ON T1.ID = T0.imputID
					WHERE T0.imputID2 = ".$imput2tmp." AND T0.imputID = ".$imputtmp."
					ORDER BY T3.code";
				$reponse = $bdd->query($req);
				$checkrep = $reponse->rowCount();
				if ($checkrep != 0)
				{
					$i = 1;
					while ($donnee = $reponse->fetch() )
					{
						?>
						<tr>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[7];?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[8];?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
							<td id="t-container<?php echo $i;?>"><?php if ($donnee[1] != "") { echo $donnee[1]; } ?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[9]; ?></td>
							<td id="t-container<?php echo $i;?>"><?php if ($donnee[10] != "0000-00-00") { echo $donnee[10]; } ?></td>
							<?php if ($donnee[6] == 1)
							{ ?>
								<td id="t-ico<?php echo $i;?>"><form action="comprell1l2l3.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver la relation"></form></td>
								<?php
							}
							else
							{
								?>
								<td id="t-ico<?php echo $i;?>"><form action="comprell1l2l3.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer la relation"></form></td>
								<?php
							}
							?>
							<td id="t-ico<?php echo $i;?>"><form action="modif_compl3.php" method="post"><input type="hidden" value="<?php echo $donnee[5];?>" name="IDmodif" /><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
							<td id="t-ico<?php echo $i;?>"><form action="modif_relev.php" method="post"><input type="hidden" value="<?php echo $donnee[2];?>" name="IDmodif" /><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input border=0 src="images/RoB_info.jpg" type=image Value=submit title="Modifier le lieu et la date de cette relation" name="modifrel"></form></td>
						</tr>
					<?php
						if ($i == 1) { $i = 2; } else { $i = 1; }
					}
				} else {
					echo '<tr><td colspan="5">Pas de relation existante</td></tr>';
				}
				$reponse->closeCursor();
				?>
			</table>
		</div>
		
		<div id="sstitre">Ajouter une nouvelle relation Comp&eacute;tition-Type-&Eacute;V&eacute;nement</div>
		<table id="tablerestit">
			<tr>
				<td id="t-containertit">Comp&eacute;tition</td>
				<td id="t-containertit">Type</td>
				<td id="t-containertit">&Eacute;v&eacute;nement | Lieu &amp; Date</td>
			</tr>
			<tr>
				<td id="t-container">
					<?php
					$repimpid = $bdd->query("SELECT * FROM rob_compl1 WHERE ID='$imputtmp'");
					$donimpid = $repimpid->fetch();
					echo '<input id="w_inputtxt_90" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
					$repimpid->closeCursor();
					?>
				</td>
				<td id="t-container">
					<?php
					$repimpid = $bdd->query("SELECT * FROM rob_compl2 WHERE ID='$imput2tmp'");
					$donimpid = $repimpid->fetch();
					echo '<input id="w_inputtxt_90" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
					$repimpid->closeCursor();
					?>
				</td>
				<td id="t-container">
					<form action="comprell1l2l3.php" method="post">
						<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
						<input type="hidden" value="<?php echo $imput2tmp; ?>" name="IDrel2" />
						<div id="ProjetHint">
							<select onchange="showNewEve(this.value)" id="w_input_90">
								<option value="0">&Eacute;v&eacute;nement existant</option>
								<option value="1">Ajouter un &Eacute;v&eacute;nement</option>
							</select>
							<?php
							echo ' <select name="newcomb3" id="w_input_90" >';
							echo ' <option value=0></option>';
							$req = "SELECT * FROM rob_compl3 WHERE actif=1 ORDER BY code";
							$affpro = $bdd->query($req);
							while ($optionpro = $affpro->fetch())
							{
								echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
							}
							echo '</select> ';
							$affpro->closeCursor();
							?>
							Lieu : <input id="w_inputtxt_90" type="text" size="15" name="rellieu" /> 
							Date : <input id="w_inputtxt_90" type="text" name="reldate" onclick="ds_sh(this);" style="cursor: text" />
							<input id="w_input_90val" type="submit" Value="Ajouter" />
						</div>
					</form>
				</td>
			</tr>
		</table>
	<?php
	} else {
		if ($info != '') { echo '<div id="bas">'.$info.'</div>'; }
		if ($test != '') { echo '<div id="bas">'.$test.'</div>'; }
		echo "Probleme dans l'initialisation des variables - ".$errvar.' - '.$imputtmp;
	}
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>