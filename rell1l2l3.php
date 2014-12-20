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
		$bdd->query("UPDATE rob_imprel3 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_imprel3 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if ($errvar == 0 AND isset($_POST['newcomb3']) AND $_POST['newcomb3'] != '' OR $errvar == 0 AND isset($_POST['newmiscode']) AND $_POST['newmiscode'] != '')
			{
				if ($imputtmp != 0 AND $imput2tmp != 0)
				{
					if (isset($_POST['newmiscode']))
					{
						if ($_POST['newmiscode'] != '')
						{
							$newmiscode = strtoupper($_POST['newmiscode']);
							$reponsesel = $bdd->query("SELECT * FROM rob_imputl3 WHERE code='$newmiscode'");
							$checkrep = $reponsesel->rowCount();
							$reponsesel->closeCursor();
							$newmisplan = strtoupper($_POST['newmisplan']);
							$newmisdesc = $_POST['newmisdesc'];
							$newmisdesc = str_replace("'","\'",$newmisdesc);
							$newmisresp = $_POST['newmisresp'];
							if ($checkrep != 0)
							{
								$info = 'Cette mission &eacute;xiste d&eacute;j&agrave';
							}
							else
							{
								$bdd->query("INSERT INTO rob_imputl3 VALUES('', '$newmiscode', '$newmisdesc', '$newmisresp', 1, '$newmisplan')") or die();
							}
							$reponsesel = $bdd->query("SELECT ID FROM rob_imputl3 WHERE code='$newmiscode' LIMIT 1");
							$checkrep = $reponsesel->rowCount();
							if ($checkrep != 0)
							{
								$donneesel = $reponsesel->fetch();
								$newcomb3 = $donneesel['ID'];
							}
							else
							{
								$test = 'Probl&egrave;me au moment de l\'insertion de la mission';
							}
							$reponsesel->closeCursor();
						}
						else
						{
							$test = 'Code mission inexistant (1)';
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
								$test = 'Code mission inexistant (2)';
							}
						}
						else
						{
							$test = 'Code mission inexistant (3)';
						}
					}
					if ($test == '')
					{
						if (isset($_POST['respfact'])) { $respfact = $_POST['respfact']; } else { $respfact = 0; }
						$reponse = $bdd->query("SELECT ID FROM rob_imprel3 WHERE imputID='$imputtmp' AND imputID2='$imput2tmp' AND imputID3='$newcomb3'");
						$checkrep = $reponse->rowCount();
						if ($checkrep != 0)
						{
							$info = $info.'. Cette combinaison existe d&eacute;j&agrave';
						}
						else
						{
							$bdd->query("INSERT INTO rob_imprel3 VALUES('', '$imputtmp', '$imput2tmp', '$newcomb3', 1)");
						}
						$reponse->closeCursor();
					}
				}
				else
				{
					$test = 'Probl&egrave;me code client/ projet, merci de recharger la page';
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
					$bdd->query("UPDATE rob_imputl3 SET description='$desc', respID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
			<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
			<li><a class="typ" href="imputation.php"><span>Clients</span></a></li>
			<?php
			if (isset($_POST['IDrel']))
			{ echo '<li><a class="typ" href="rell1l2.php?IDrel='.$_POST['IDrel'].'"><span>Client-Projets</span></a></li>'; }
			else { echo '<li><a class="typ" href="rell1l2.php?IDrel='.$_GET['IDrel'].'"><span>Client-Projets</span></a></li>'; }
			?>
			<li><a class="typ" href="#"><span>Client-Projet-Missions</span></a></li>
		</ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Client-Projet-Missions management</div>

	<?php
	if ($imputtmp !=0 AND $imput2tmp !=0 AND $errvar == 0)
	{
		?>
		<div id="coeur">
			<table id="tablerestit" class="table table-striped temp-table">
				<tr>
					<td id="t-containertit">Client</td>
					<td id="t-containertit">Projet</td>
					<td id="t-containertit">Mission</td>
					<td id="t-containertit">Description</td>
					<td id="t-containertit" colspan="3">Actions</td>
				</tr>
				<?php
				$req="SELECT T3.code, T3.description, T0.ID, T0.imputID, T0.imputID2, T0.imputID3, T0.actif, T1.code, T2.code
					FROM rob_imprel3 T0
					INNER JOIN rob_imputl3 T3 ON T3.ID = T0.imputID3
					INNER JOIN rob_imputl2 T2 ON T2.ID = T0.imputID2
					INNER JOIN rob_imputl1 T1 ON T1.ID = T0.imputID
					WHERE T0.imputID2 = ".$imput2tmp." AND T0.imputID = ".$imputtmp."
					ORDER BY T3.description";
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
							<?php if ($donnee[6] == 1)
							{ ?>
								<td id="t-ico<?php echo $i;?>"><form action="rell1l2l3.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver la relation"></form></td>
								<td id="t-ico<?php echo $i;?>"><form action="rell1l2l3l4.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" /><input border=0 src="images/RoB_relact.png" type=image Value=submit title="Vers les missions en relation" name="relat"></form></td>
								<?php
							}
							else
							{
								?>
								<td id="t-ico<?php echo $i;?>"><form action="rell1l2l3.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer la relation"></form></td>
								<td id="t-ico<?php echo $i;?>"><form action="rell1l2l3l4.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" /><input border=0 src="images/RoB_reldeact.png" type=image Value=submit title="Vers les missions en relation" name="relat"></form></td>
								<?php
							}
							?>
							<td id="t-ico<?php echo $i;?>"><form action="modif_imputl3.php" method="post"><input type="hidden" value="<?php echo $donnee[5];?>" name="IDmodif" /><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
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
		
		<div id="sstitre">Ajouter une nouvelle relation Client-Projet-Mission</div>
		<table id="tablerestit" class="table table-striped temp-table">
			<tr>
				<td id="t-containertit">Client</td>
				<td id="t-containertit">Projet</td>
				<td id="t-containertit">Mission</td>
			</tr>
			<tr>
				<td id="t-container">
					<?php
					$repimpid = $bdd->query("SELECT * FROM rob_imputl1 WHERE ID='$imputtmp'");
					$donimpid = $repimpid->fetch();
					echo '<input id="w_inputtxt_90" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
					$repimpid->closeCursor();
					?>
				</td>
				<td id="t-container">
					<?php
					$repimpid = $bdd->query("SELECT * FROM rob_imputl2 WHERE ID='$imput2tmp'");
					$donimpid = $repimpid->fetch();
					echo '<input id="w_inputtxt_90" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
					$repimpid->closeCursor();
					?>
				</td>
				<td id="t-container">
					<form action="rell1l2l3.php" method="post">
						<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
						<input type="hidden" value="<?php echo $imput2tmp; ?>" name="IDrel2" />
						<div id="ProjetHint">
							<select onchange="showNewMis(this.value)" id="w_input_90">
								<option value="0">Mission existante</option>
								<option value="1">Ajouter une mission</option>
							</select>
							<?php
							echo ' <select name="newcomb3" id="w_input_90" >';
							echo ' <option value=0></option>';
							$req = "SELECT * FROM rob_imputl3 WHERE actif=1 ORDER BY code";
							$affpro = $bdd->query($req);
							while ($optionpro = $affpro->fetch())
							{
								echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
							}
							echo '</select> ';
							$affpro->closeCursor();
							?>
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