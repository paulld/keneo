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
	<div class="background-db-management background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title">
			<h1>Client-Projet-Missions management</h1>
		</div>

		<div class="back-buttons">
			<a class="btn btn-default" href="imputation.php"><i class="fa fa-arrow-left"></i> Retour &agrave; Clients</a>
			<?php
				if (isset($_POST['IDrel']))
				{ echo '<a class="btn btn-default" href="rell1l2.php?IDrel='.$_POST['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projets</a>'; }
				else { echo '<a class="btn btn-default" href="rell1l2.php?IDrel='.$_GET['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projets</a>'; }
			?>
		</div>

		<?php
		if ($imputtmp !=0 AND $imput2tmp !=0 AND $errvar == 0) {
		?>
		
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Client</th>
						<th>Projet</th>
						<th>Mission</th>
						<th>Description</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody>
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
				if ($checkrep != 0) {
					while ($donnee = $reponse->fetch() ) {
						?>
						<tr>
							<td><?php echo $donnee[7];?></td>
							<td><?php echo $donnee[8];?></td>
							<td><?php echo $donnee[0];?></td>
							<td><?php if ($donnee[1] != "") { echo $donnee[1]; } ?></td>
							<?php if ($donnee[6] == 1)
							{ ?>
								<td>
									<form action="rell1l2l3.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
										<input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver la relation"><i class="fa fa-toggle-on"></i></button>
									</form>
								</td>
								<td>
									<form action="rell1l2l3l4.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
										<input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" />
										<button class="btn btn-small btn-default btn-icon btn-orange" type="submit" title="Vers les missions en relation" name="relat"><i class="fa fa-link"></i></button>
									</form>
								</td>
								<?php
							} else {
								?>
								<td>
									<form action="rell1l2l3.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
										<input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer la relation"><i class="fa fa-toggle-off"></i></button>
									</form>
								</td>
								<td>
									<form action="rell1l2l3l4.php" method="post">
										<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
										<input type="hidden" value="<?php echo $donnee[5];?>" name="IDrel3" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Vers les missions en relation" name="relat"><i class="fa fa-link"></i></button>
									</form>
								</td>
								<?php
							}
							?>
							<td>
								<form action="modif_imputl3.php" method="post">
									<input type="hidden" value="<?php echo $donnee[5];?>" name="IDmodif" />
									<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
									<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
									<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
								</form>
							</td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="5">
							Pas de relation existante
						</td>
					</tr>
					<?php
				}
				$reponse->closeCursor();
				?>
				</tbody>
			</table>
		
		
		<h2>Ajouter une nouvelle relation Client-Projet-Mission</h2>
		<form action="rell1l2l3.php" method="post">
			<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
			<input type="hidden" value="<?php echo $imput2tmp; ?>" name="IDrel2" />
			<table id="tablerestit" class="table table-striped table-align-top">
				<thead>
					<tr>
						<th>Client</th>
						<th>Projet</th>
						<th colspan="3">Mission</th>
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
							<select class="form-control" onchange="showOption(this.value)">
								<option value="0">Mission existante</option>
								<option value="1">Ajouter une mission</option>
							</select>
						</td>
						<td class="show-option" id="show-option-0">
							<?php include("partials/currmission.php"); ?>
						</td>
						<td class="show-option" id="show-option-1" style="display: none;">
							<?php include("partials/newmission.php"); ?>
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