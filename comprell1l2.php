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

	//Variables relationelles
	if (isset($_POST['IDrel']) ) { $imputtmp = $_POST['IDrel']; } else { if (isset($_GET['IDrel'])) { $imputtmp = $_GET['IDrel']; } else { $errvar = 1; } };


	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_comprel2 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_comprel2 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if ($errvar == 0 AND isset($_POST['newcomb2']) AND $_POST['newcomb2'] != '' OR $errvar == 0 AND isset($_POST['newtypcode']) AND $_POST['newtypcode'] != '')
			{
				if ($imputtmp != 0)
				{
					if (isset($_POST['newtypcode']))
					{
						if ($_POST['newtypcode'] != '')
						{
							$newtypcode = strtoupper($_POST['newtypcode']);
							$checkcode = $bdd->query("SELECT code FROM rob_imputl1 WHERE code='$newtypcode'");
							$codepris = $checkcode->rowCount();
							$checkcode->closeCursor();
							$newtypplan = strtoupper($_POST['newtypplan']);
							$newtypdesc = $_POST['newtypdesc'];
							$newtypdesc = str_replace("'","\'",$newtypdesc);
							$newtypresp = $_POST['newtypresp'];
							if ($codepris != 0)
							{
								$info = 'Ce type existe d&eacute;j&agrave';
							}
							else
							{
								$bdd->query("INSERT INTO rob_compl2 VALUES('', '$newtypcode', '$newtypdesc', '$newtypresp', 1, '$newtypplan')") or die();
							}
							$reponsesel = $bdd->query("SELECT ID FROM rob_compl2 WHERE code='$newtypcode' LIMIT 1");
							$repl = $reponsesel->rowCount();
							if ($repl != 0)
							{
								$donneesel = $reponsesel->fetch();
								$newcomb2 = $donneesel['ID'];
							}
							else
							{
								$test = 'Probl&egrave;me au moment de l\'insertion du type';
							}
							$reponsesel->closeCursor();
						}
						else
						{
							$test = 'Code type inexistant 1';
						}
					}
					else
					{
						if (isset($_POST['newcomb2']))
						{
							if ($_POST['newcomb2'] != 0)
							{
								$newcomb2 = $_POST['newcomb2'];
							}
							else
							{
								$test = 'Code type inexistant 2';
							}
						}
						else
						{
							$test = 'Code type inexistant 3';
						}
					}
					if ($test == '')
					{
						$reponse = $bdd->query("SELECT ID FROM rob_comprel2 WHERE imputID='$imputtmp' AND  imputID2='$newcomb2'");
						$checkrep = $reponse->rowCount();
						if ($checkrep != 0)
						{
							$info = $info.'. Cette combinaison existe d&eacute;j&agrave';
						}
						else
						{
							$bdd->query("INSERT INTO rob_comprel2 VALUES('', '$imputtmp', '$newcomb2', 1)");
						}
						$reponse->closeCursor();
					}
				}
				else
				{
					$test = 'Probl&egrave;me code comp&eacute;tition, merci de recharger la page';
				}
			}
			else
			{
				if (isset($_POST['modID']))
				{
					$modID = $_POST['modID'];
					$code = $_POST['modcode'];
					$plan = $_POST['modplan'];
					$desc = $_POST['moddesc'];
					$respfact = $_POST['modrespfact'];
					$bdd->query("UPDATE rob_compl2 SET description='$desc', respID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li>
		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>
		<li><a class="typ" href="competition.php"><span>Comp&eacute;titions</span></a></li>
		<li><a class="typ" href="#"><span>Comp&eacute;tition-Types</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<div id="haut">Comp&eacute;tition-Types management</div>

	<?php
	if ($imputtmp !=0 AND $errvar == 0)
	{
		?>
		<div id="coeur">
			<table id="tablerestit" class="table">
				<tr>
					<td id="t-containertit">Comp&eacute;tition</td>
					<td id="t-containertit">Type</td>
					<td id="t-containertit">Description</td>
					<td id="t-containertit" colspan="3">Actions</td>
				</tr>
				<?php
				$req="SELECT T2.code, T2.description, T0.ID, T0.imputID, T0.imputID2, T0.actif, T1.code
					FROM rob_comprel2 T0
					INNER JOIN rob_compl2 T2 ON T2.ID = T0.imputID2
					INNER JOIN rob_compl1 T1 ON T1.ID = T0.imputID
					WHERE T0.imputID = ".$imputtmp."
					ORDER BY T2.code";
				$reponse = $bdd->query($req);
				$checkrep = $reponse->rowCount();
				if ($checkrep != 0)
				{
					$i = 1;
					while ($donnee = $reponse->fetch() )
					{
						?>
						<tr>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[6];?></td>
							<td id="t-container<?php echo $i;?>"><?php echo $donnee[0];?></td>
							<td id="t-container<?php echo $i;?>"><?php if ($donnee[1] != "") { echo $donnee[1]; } ?></td>
							<?php if ($donnee[5] == 1)
							{ ?>
								<td id="t-ico<?php echo $i;?>"><form action="comprell1l2.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" /><input border=0 src="images/RoB_activ.png" type=image Value=submit title="Desactiver la relation"></form></td>
								<td id="t-ico<?php echo $i;?>"><form action="comprell1l2l3.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input border=0 src="images/RoB_relact.png" type=image Value=submit title="Vers les missions en relation" name="relat"></form></td>
								<?php
							}
							else
							{
								?>
								<td id="t-ico<?php echo $i;?>"><form action="comprell1l2.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" /><input border=0 src="images/RoB_deactiv.png" type=image Value=submit title="Activer la relation"></form></td>
								<td id="t-ico<?php echo $i;?>"><form action="comprell1l2l3.php" method="post"><input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" /><input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" /><input border=0 src="images/RoB_reldeact.png" type=image Value=submit title="Vers les missions en relation" name="relat"></form></td>
								<?php
							}
							?>
							<td id="t-ico<?php echo $i;?>"><form action="modif_compl2.php" method="post"><input type="hidden" value="<?php echo $donnee[4];?>" name="IDmodif" /><input type="hidden" value="<?php echo $imputtmp;?>" name="IDrel" /><input border=0 src="images/RoB_info.png" type=image Value=submit title="Modifier les informations" name="modif"></form></td>
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
		
		<div id="sstitre">Ajouter une nouvelle relation Comp&eacute;tition-Type</div>
		<table id="tablerestit" class="table">
			<tr>
				<td id="t-containertit">Comp&eacute;tition</td>
				<td id="t-containertit">Type</td>
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
					<form action="comprell1l2.php" method="post">
						<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
						<div id="ProjetHint">
							<select name="client" onchange="showNewTyp(this.value)" id="w_input_90">
								<option value="0">Type existant</option>
								<option value="1">Ajouter un type</option>
							</select>
							<?php
							echo ' <select name="newcomb2" id="w_input_90" >';
							echo ' <option value=0></option>';
							$req = "SELECT * FROM rob_compl2 WHERE actif=1 ORDER BY code";
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
	?>
	</div>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>