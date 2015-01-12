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
						if ($_POST['newcomb3'] != 0)
						{
							$newcomb3 = $_POST['newcomb3'];
						}
						else
						{
							$test = 'Code &Eacute;v&eacute;nement inexistant (2)';
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
							if (isset($_POST['reldate'])) { $reldate = date('Y-m-d',mktime(0,0,0,substr($_POST['reldate'],3,2),substr($_POST['reldate'],0,2),substr($_POST['reldate'],6,4))); } else { $reldate = "0000-00-00"; }
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
						$date = date('Y-m-d',mktime(0,0,0,substr($_POST['moddate'],3,2),substr($_POST['moddate'],0,2),substr($_POST['moddate'],6,4)));
						$bdd->query("UPDATE rob_comprel3 SET lieu='$lieu', date='$date' WHERE ID='$modIDrel'");
					}
				}
			}
		}
	}
	?>
	
	<!--
			
-->
	<div class="background-competitions background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="historique-frais">

		<div class="section-title">
			<h1>Comp&eacute;tition-Type-&Eacute;v&eacute;nements management</h1>
		</div>

		<div class="back-buttons">
			<a class="btn btn-default" href="competition.php"><i class="fa fa-arrow-left"></i> Retour &agrave; Comp&eacute;titions</a>
			<?php
			if (isset($_POST['IDrel']))
			{ echo '<a class="btn btn-default" href="comprell1l2.php?IDrel='.$_POST['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Comp&eacute;tition-Types</a>'; }
			else { echo '<a class="btn btn-default" href="comprell1l2.php?IDrel='.$_GET['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Comp&eacute;tition-Types</a>'; }
			?>
		</div>

		<?php
		if ($imputtmp !=0 AND $imput2tmp !=0 AND $errvar == 0) {
		?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Comp&eacute;tition</th>
							<th>Type</th>
							<th>&Eacute;v&eacute;nement</th>
							<th>Description</th>
							<th>Lieu</th>
							<th>Date</th>
							<th colspan="3">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$req="SELECT T3.code, T3.description, T0.ID, T0.imputID, T0.imputID2, T0.imputID3, T0.actif, 
												 T1.code, T2.code, T0.lieu, T0.date
							FROM rob_comprel3 T0
							INNER JOIN rob_compl3 T3 ON T3.ID = T0.imputID3
							INNER JOIN rob_compl2 T2 ON T2.ID = T0.imputID2
							INNER JOIN rob_compl1 T1 ON T1.ID = T0.imputID
							WHERE T0.imputID2 = ".$imput2tmp." AND T0.imputID = ".$imputtmp."
							ORDER BY T3.code";
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
									<td><?php echo $donnee[9]; ?></td>
									<td><?php if ($donnee[10] != "0000-00-00") { echo date("d/m/Y",strtotime($donnee[10])); } ?></td>
									<?php if ($donnee[6] == 1)
									{ ?>
										<td>
											<form action="comprell1l2l3.php" method="post">
												<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
												<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
												<input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" />
												<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver la relation"><i class="fa fa-toggle-on"></i></button>
											</form>
										</td>
										<?php
									}
									else
									{
										?>
										<td>
											<form action="comprell1l2l3.php" method="post">
												<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
												<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
												<input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" />
												<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer la relation"><i class="fa fa-toggle-off"></i></button>
											</form>
										</td>
										<?php
									}
									?>
									<td>
										<form action="modif_compl3.php" method="post">
											<input type="hidden" value="<?php echo $donnee[5];?>" name="IDmodif" />
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
											<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
										</form>
									</td>
									<td>
										<form action="modif_relev.php" method="post">
											<input type="hidden" value="<?php echo $donnee[2];?>" name="IDmodif" />
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
											<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier le lieu et la date de cette relation" name="modif"><i class="fa fa-calendar"></i></button>
										</form>
									</td>
								</tr>
							<?php
							}
						} else {
							?>
							<tr><td colspan="5">Pas de relation existante</td></tr>';
							<?php
						}
						$reponse->closeCursor();
						?>
					</tbody>
				</table>
			</div>
			
			<h2>Ajouter une nouvelle relation Comp&eacute;tition-Type-&Eacute;V&eacute;nement</h2>
			<form action="comprell1l2l3.php" method="post">
				<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
				<input type="hidden" value="<?php echo $imput2tmp; ?>" name="IDrel2" />
				<div class="table-responsive">
					<table class="table table-striped table-align-top">
						<thead>
							<tr>
								<th>Comp&eacute;tition</th>
								<th>Type</th>
								<th colspan="2">&Eacute;v&eacute;nement</th>
								<th colspan="3">Lieu &amp; Date</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<?php
									$repimpid = $bdd->query("SELECT * FROM rob_compl1 WHERE ID='$imputtmp'");
									$donimpid = $repimpid->fetch();
									echo '<input class="form-control" type="text" size="12" value="'.$donimpid['code'].'" disabled="disabled" />';
									$repimpid->closeCursor();
									?>
								</td>
								<td>
									<?php
									$repimpid = $bdd->query("SELECT * FROM rob_compl2 WHERE ID='$imput2tmp'");
									$donimpid = $repimpid->fetch();
									echo '<input class="form-control" type="text" size="12" value="'.$donimpid['code'].'" disabled="disabled" />';
									$repimpid->closeCursor();
									?>
								</td>

								<td>
									<select class="form-control" onchange="showOption(this.value)">
										<option value="0">&Eacute;v&eacute;nement existant</option>
										<option value="1">Ajouter un &Eacute;v&eacute;nement</option>
									</select>
								</td>
								<td class="show-option" id="show-option-0">
									<?php include("partials/currevent.php"); ?>
								</td>
								<td class="show-option" id="show-option-1" style="display: none;">
									<?php include("partials/newevent.php"); ?>
								</td>

								<td>
									<input class="form-control" type="text" size="13" name="rellieu" placeholder="Lieu" /> 
								</td>
								<td>
									<input class="form-control datepicker" type="text" name="reldate" placeholder="Date" size="13" />
								</td>
								<td>
									<input class="btn btn-primary" type="submit" Value="Ajouter" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		<?php
		} else {
			if ($info != '') { echo '<div>'.$info.'</div>'; }
			if ($test != '') { echo '<div>'.$test.'</div>'; }
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