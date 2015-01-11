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

	<!-- <a class="typ" href="competition.php"><span>Comp&eacute;titions</span> -->
	
	<div class="background-competitions background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="historique-frais">

		<div class="section-title">
			<h1>Comp&eacute;tition-Types management</h1>
		</div>

		<div class="back-buttons">
			<a class="btn btn-default" href="competition.php"><i class="fa fa-arrow-left"></i> Retour &agrave; Comp&eacute;titions</a>
		</div>

		<?php
		if ($imputtmp !=0 AND $errvar == 0) {
		?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Comp&eacute;tition</th>
						<th>Type</th>
						<th>Description</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T2.code, T2.description, T0.ID, T0.imputID, T0.imputID2, T0.actif, T1.code
						FROM rob_comprel2 T0
						INNER JOIN rob_compl2 T2 ON T2.ID = T0.imputID2
						INNER JOIN rob_compl1 T1 ON T1.ID = T0.imputID
						WHERE T0.imputID = ".$imputtmp."
						ORDER BY T2.code";
					$reponse = $bdd->query($req);
					$checkrep = $reponse->rowCount();
					if ($checkrep != 0) {
						while ($donnee = $reponse->fetch() ) {
							?>
							<tr>
								<td><?php echo $donnee[6];?></td>
								<td><?php echo $donnee[0];?></td>
								<td><?php if ($donnee[1] != "") { echo $donnee[1]; } ?></td>
								<?php 
								if ($donnee[5] == 1) { 
								?>
									<td>
										<form action="comprell1l2.php" method="post">
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee[2];?>" name="IDinact" />
											<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver la relation"><i class="fa fa-toggle-on"></i></button>
										</form>
									</td>
									<td>
										<form action="comprell1l2l3.php" method="post">
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
											<button class="btn btn-small btn-default btn-icon btn-orange" type="submit" title="Vers les missions en relation" name="relat"><i class="fa fa-link"></i></button>
										</form>
									</td>
								<?php
								} else {
								?>
									<td>
										<form action="comprell1l2.php" method="post">
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee[2];?>" name="IDact" />
											<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer la relation"><i class="fa fa-toggle-off"></i></button>
										</form>
									</td>
									<td>
										<form action="comprell1l2l3.php" method="post">
											<input type="hidden" value="<?php echo $donnee[3];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee[4];?>" name="IDrel2" />
											<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Vers les missions en relation" name="relat"><i class="fa fa-link"></i></button>
										</form>
									</td>
								<?php
								}
								?>
								<td>
									<form action="modif_compl2.php" method="post">
										<input type="hidden" value="<?php echo $donnee[4];?>" name="IDmodif" />
										<input type="hidden" value="<?php echo $imputtmp;?>" name="IDrel" />
										<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
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
		
		
		<h2>Ajouter une nouvelle relation Comp&eacute;tition-Type</h2>
		<form action="comprell1l2.php" method="post">
			<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
			<table class="table table-striped table-align-top">
				<thead>
					<tr>
						<th>Comp&eacute;tition</th>
						<th colspan="3">Type</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?php
							$repimpid = $bdd->query("SELECT * FROM rob_compl1 WHERE ID='$imputtmp'");
							$donimpid = $repimpid->fetch();
							echo '<input class="form-control" type="text" size="15" value="'.$donimpid['code'].'" disabled="disabled" />';
							$repimpid->closeCursor();
							?>
						</td>

						<td>
							<select class="form-control" name="client" onchange="showOption(this.value)">
								<option value="0">Type existant</option>
								<option value="1">Ajouter un type</option>
							</select>
						</td>
						<td class="show-option" id="show-option-0">
							<?php include("currtype.php"); ?>
						</td>
						<td class="show-option" id="show-option-1" style="display: none;">
							<?php include("newtype.php"); ?>
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