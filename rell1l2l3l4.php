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
	<div class="background-db-management background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title">
			<h1>Client-Projet-Mission-Cat&eacute;gories management</h1>
		</div>

		<div class="back-buttons">
			<a class="btn btn-default" href="imputation.php"><i class="fa fa-arrow-left"></i> Retour &agrave; Clients</a>
			<?php
				if (isset($_POST['IDrel'])) { 
					echo '<a class="btn btn-default" href="rell1l2.php?IDrel='.$_POST['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projets</a>';
					echo '<a class="btn btn-default" href="rell1l2l3.php?IDrel='.$_POST['IDrel'].'&amp;IDrel2='.$_POST['IDrel2'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projet-Missions</a>'; 
				} else { 
					echo '<a class="btn btn-default" href="rell1l2.php?IDrel='.$_GET['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projets</a>';
					echo '<a class="btn btn-default" href="rell1l2l3.php?IDrel='.$_GET['IDrel'].'&amp;IDrel2='.$_GET['IDrel2'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projet-Missions</a>'; 
				}
			?>
		</div>

	<?php
	if ($imputtmp !=0 AND $imput2tmp !=0 AND $imput3tmp !=0 AND $errvar == 0)
	{
		?>
		
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Client</th>
					<th>Projet</th>
					<th>Mission</th>
					<th>Cat&eacute;gorie</th>
					<th>Description</th>
					<th colspan="3">Actions</th>
				</tr>
			</thead>
			<tbody>
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
					
					while ($donnee = $reponse->fetch() )
					{
						?>
						<tr>
							<td><?php echo $donnee[8];?></td>
							<td><?php echo $donnee[9];?></td>
							<td><?php echo $donnee[10];?></td>
							<td><?php echo $donnee[0];?></td>
							<td><?php if ($donnee[1] != "") { echo $donnee[1]; } ?></td>
							<?php if ($donnee[7] == 1)
							{ ?>
								<td>&nbsp;</td>
								<td>
									<form action="rell1l2l3l4.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
										<input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" />
										<input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver la relation"><i class="fa fa-toggle-on"></i></button>
									</form>
								</td>
								<?php
							}
							else
							{
								?>
								<td>&nbsp;</td>
								<td>
									<form action="rell1l2l3l4.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
										<input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" />
										<input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer la relation"><i class="fa fa-toggle-off"></i></button>
									</form>
								</td>
								<?php
							}
							?>
							<td>
								<form action="modif_imputl4.php" method="post">
									<input type="hidden" value="<?php echo $donnee[6];?>" name="IDmodif" />
									<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
									<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
									<input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" />
									<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
								</form>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr><td colspan="6">Pas de relation existante</td></tr>';
				}
				$reponse->closeCursor();
				?>
			</tbody>
		</table>
		

		<h2>Ajouter une nouvelle relation Client-Projet-Mission-Categorie</h2>
		<form action="rell1l2l3l4.php" method="post">
			<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
			<input type="hidden" value="<?php echo $imput2tmp; ?>" name="IDrel2" />
			<input type="hidden" value="<?php echo $imput3tmp; ?>" name="IDrel3" />
			<table class="table table-striped temp-table table-align-top">
				<thead>
					<tr>
						<th>Client</th>
						<th>Projet</th>
						<th>Mission</th>
						<th colspan="3">Cat&eacute;gorie</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?php
							$repimpid = $bdd->query("SELECT * FROM rob_imputl1 WHERE ID='$imputtmp'");
							$donimpid = $repimpid->fetch();
							echo '<input class="form-control" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
							$repimpid->closeCursor();
							?>
						</td>
						<td>
							<?php
							$repimpid = $bdd->query("SELECT * FROM rob_imputl2 WHERE ID='$imput2tmp'");
							$donimpid = $repimpid->fetch();
							echo '<input class="form-control" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
							$repimpid->closeCursor();
							?>
						</td>
						<td>
							<?php
							$repimpid = $bdd->query("SELECT * FROM rob_imputl3 WHERE ID='$imput3tmp'");
							$donimpid = $repimpid->fetch();
							echo '<input class="form-control" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
							$repimpid->closeCursor();
							?>
						</td>

						<td>
							<select onchange="showOption(this.value)" class="form-control">
								<option value="0">Cat&eacute;gorie existante</option>
								<option value="1">Ajouter une cat&eacute;gorie</option>
							</select>
						</td>
						<td class="show-option" id="show-option-0">
							<?php include("currcategorie.php"); ?>
						</td>
						<td class="show-option" id="show-option-1" style="display: none;">
							<?php include("newcategorie.php"); ?>
						</td>

						<td>
							<input class="btn btn-primary" type="submit" Value="Ajouter" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	<?php
	} else {
		if ($info != '') { echo '<div id="bas">'.$info.'</div>'; }
		if ($test != '') { echo '<div id="bas">'.$test.'</div>'; }
		echo "Probleme dans l'initialisation des variables - ".$errvar.' - '.$imputtmp;
	}
	?> 
</section>
	<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>