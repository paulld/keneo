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
	$imput3tmp = 0;

	//Variables relationelles
	if (isset($_POST['IDrel']) ) { $imputtmp = $_POST['IDrel']; } else { if (isset($_GET['IDrel'])) { $imputtmp = $_GET['IDrel']; } else { $errvar = 1; } };
	if (isset($_POST['IDrel2']) ) { $imput2tmp = $_POST['IDrel2']; } else { if (isset($_GET['IDrel2'])) { $imput2tmp = $_GET['IDrel2']; } else { $errvar = 2; } };
	if (isset($_POST['IDrel3']) ) { $imput3tmp = $_POST['IDrel3']; } else { if (isset($_GET['IDrel3'])) { $imput3tmp = $_GET['IDrel3']; } else { $errvar = 3; } };

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_imprel4 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_imprel4 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if ($errvar == 0 AND isset($_POST['newcomb4']) AND $_POST['newcomb4'] != '' OR $errvar == 0 AND isset($_POST['newcatcode']) AND $_POST['newcatcode'] != '')
			{
				if ($imputtmp != 0 AND $imput2tmp != 0 AND $imput3tmp != 0)
				{
					if (isset($_POST['newcatcode']))
					{
						if ($_POST['newcatcode'] != '')
						{
							$newcatcode = strtoupper($_POST['newcatcode']);
							$reponsesel = $bdd->query("SELECT * FROM rob_imputl4 WHERE code='$newcatcode'");
							$checkrep = $reponsesel->rowCount();
							$reponsesel->closeCursor();
							$newcatplan = strtoupper($_POST['newcatplan']);
							$newcatdesc = $_POST['newcatdesc'];
							$newcatdesc = str_replace("'","\'",$newcatdesc);
							$newcatresp = $_POST['newcatresp'];
							if ($checkrep != 0)
							{
								$info = 'Cette cat&eactute;gorie &eacute;xiste d&eacute;j&agrave';
							}
							else
							{
								$bdd->query("INSERT INTO rob_imputl4 VALUES('', '$newcatcode', '$newcatdesc', '$newcatresp', 1, '$newcatplan')") or die();
							}
							$reponsesel = $bdd->query("SELECT ID FROM rob_imputl4 WHERE code='$newcatcode' LIMIT 1");
							$checkrep = $reponsesel->rowCount();
							if ($checkrep != 0)
							{
								$donneesel = $reponsesel->fetch();
								$newcomb4 = $donneesel['ID'];
							}
							else
							{
								$test = 'Probl&egrave;me au moment de l\'insertion de la cat&eactute;gorie';
							}
							$reponsesel->closeCursor();
						}
						else
						{
							$test = 'Code cat&eactute;gorie inexistant (1)';
						}
					}
					else
					{
						if (isset($_POST['newcomb4']))
						{
							if ($_POST['newcomb4'] != 0)
							{
								$newcomb4 = $_POST['newcomb4'];
							}
							else
							{
								$test = 'Code cat&eactute;gorie inexistant (2)';
							}
						}
						else
						{
							$test = 'Code cat&eactute;gorie inexistant (3)';
						}
					}
					if ($test == '')
					{
						if (isset($_POST['respfact'])) { $respfact = $_POST['respfact']; } else { $respfact = 0; }
						$reponse = $bdd->query("SELECT ID FROM rob_imprel4 WHERE imputID='$imputtmp' AND imputID2='$imput2tmp' AND imputID3='$imput3tmp' AND imputID4='$newcomb4'");
						$checkrep = $reponse->rowCount();
						if ($checkrep != 0)
						{
							$info = $info.'. Cette combinaison existe d&eacute;j&agrave';
						}
						else
						{
							$bdd->query("INSERT INTO rob_imprel4 VALUES('', '$imputtmp', '$imput2tmp', '$imput3tmp', '$newcomb4', 1)");
						}
						$reponse->closeCursor();
					}
				}
				else
				{
					$test = 'Probl&egrave;me code client/ projet/ mission, merci de recharger la page';
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
					$bdd->query("UPDATE rob_imputl4 SET description='$desc', respID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
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
			{ echo '<li><a class="typ" href="rell1l2.php?IDrel='.$_POST['IDrel'].'"><span>Client-Projets</span></a></li>';
			echo '<li><a class="typ" href="rell1l2l3.php?IDrel='.$_POST['IDrel'].'&amp;IDrel2='.$_POST['IDrel2'].'"><span>Client-Projet-Missions</span></a></li>'; }
			else { echo '<li><a class="typ" href="rell1l2.php?IDrel='.$_GET['IDrel'].'"><span>Client-Projets</span></a></li>';
			echo '<li><a class="typ" href="rell1l2l3.php?IDrel='.$_GET['IDrel'].'&amp;IDrel2='.$_GET['IDrel2'].'"><span>Client-Projet-Missions</span></a></li>'; }
			?>
			<li><a class="typ" href="#"><span>Client-Projet-Mission-Cat&eacute;gories</span></a></li>
		</ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Client-Projet-Mission-Cat&eacute;gories management</div>

	<?php
	if ($imputtmp !=0 AND $imput2tmp !=0 AND $imput3tmp !=0 AND $errvar == 0)
	{
		?>
		<div id="coeur">
			<table id="tablerestit" class="table table-striped temp-table">
				<tr>
					<td id="t-containertit">Client</td>
					<td id="t-containertit">Projet</td>
					<td id="t-containertit">Mission</td>
					<td id="t-containertit">Cat&eacute;gorie</td>
					<td id="t-containertit">Description</td>
					<td id="t-containertit" colspan="3">Actions</td>
				</tr>
				<?php
				$req="SELECT T4.code, T4.description, T0.ID, T0.imputID, T0.imputID2, T0.imputID3, T0.imputID4, T0.actif, T1.code, T2.code, T3.code
					FROM rob_imprel4 T0
					INNER JOIN rob_imputl4 T4 ON T4.ID = T0.imputID4
					INNER JOIN rob_imputl3 T3 ON T3.ID = T0.imputID3
					INNER JOIN rob_imputl2 T2 ON T2.ID = T0.imputID2
					INNER JOIN rob_imputl1 T1 ON T1.ID = T0.imputID
					WHERE T0.imputID3 = ".$imput3tmp." AND T0.imputID2 = ".$imput2tmp." AND T0.imputID = ".$imputtmp."
					ORDER BY T4.description";
				$reponse = $bdd->query($req);
				$checkrep = $reponse->rowCount();
				if ($checkrep != 0)
				{
					$i = 1;
					while ($donnee = $reponse->fetch() )
					{
						?>
						<tr>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[8];?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[9];?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[10];?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
							<td id="t-container<?php echo $i;?>"><?php if ($donnee[1] != "") { echo $donnee[1]; } ?></td>
							<?php if ($donnee[7] == 1)
							{ ?>
								<td id="t-ico<?php echo $i;?>">&nbsp;</td>
								<td id="t-ico<?php echo $i;?>"><form action="rell1l2l3l4.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver la relation"></form></td>
								<?php
							}
							else
							{
								?>
								<td id="t-ico<?php echo $i;?>">&nbsp;</td>
								<td id="t-ico<?php echo $i;?>"><form action="rell1l2l3l4.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer la relation"></form></td>
								<?php
							}
							?>
							<td id="t-ico<?php echo $i;?>"><form action="modif_imputl4.php" method="post"><input type="hidden" value="<?php echo $donnee[6];?>" name="IDmodif" /><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
						</tr>
						<?php
						if ($i == 1) { $i = 2; } else { $i = 1; }
					}
				} else {
					echo '<tr><td colspan="6">Pas de relation existante</td></tr>';
				}
				$reponse->closeCursor();
				?>
			</table>
		</div>

		<div id="sstitre">Ajouter une nouvelle relation Client-Projet-Mission-Categorie</div>
		<table id="tablerestit" class="table table-striped temp-table">
			<tr>
				<td id="t-containertit">Client</td>
				<td id="t-containertit">Projet</td>
				<td id="t-containertit">Mission</td>
				<td id="t-containertit">Cat&eacute;gorie</td>
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
					<?php
					$repimpid = $bdd->query("SELECT * FROM rob_imputl3 WHERE ID='$imput3tmp'");
					$donimpid = $repimpid->fetch();
					echo '<input id="w_inputtxt_90" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
					$repimpid->closeCursor();
					?>
				</td>
				<td id="t-container">
					<form action="rell1l2l3l4.php" method="post">
						<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
						<input type="hidden" value="<?php echo $imput2tmp; ?>" name="IDrel2" />
						<input type="hidden" value="<?php echo $imput3tmp; ?>" name="IDrel3" />
						<div id="ProjetHint">
							<select onchange="showNewCat(this.value)" id="w_input_90">
								<option value="0">Cat&eacute;gorie existante</option>
								<option value="1">Ajouter une cat&eacute;gorie</option>
							</select>
							<?php
							echo ' <select name="newcomb4" id="w_input_90" >';
							echo ' <option value=0></option>';
							$req = "SELECT * FROM rob_imputl4 WHERE actif=1 ORDER BY code";
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