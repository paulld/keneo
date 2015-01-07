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
		$bdd->query("UPDATE rob_imprel2 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_imprel2 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if ($errvar == 0 AND isset($_POST['newcomb2']) AND $_POST['newcomb2'] != '' OR $errvar == 0 AND isset($_POST['newprjcode']) AND $_POST['newprjcode'] != '')
			{
				if ($imputtmp != 0)
				{
					if (isset($_POST['newprjcode']))
					{
						if ($_POST['newprjcode'] != '')
						{
							$newprjcode = strtoupper($_POST['newprjcode']);
							$reponsesel = $bdd->query("SELECT * FROM rob_imputl2 WHERE code='$newprjcode'");
							$checkrep = $reponsesel->rowCount();
							$reponsesel->closeCursor();
							$newprjplan = strtoupper($_POST['newprjplan']);
							$newprjdesc = $_POST['newprjdesc'];
							$newprjdesc = str_replace("'","\'",$newprjdesc);
							$newprjresp = $_POST['newprjresp'];
							if ($checkrep != 0)
							{
								$info = 'Ce projet existe d&eacute;j&agrave';
							}
							else
							{
								$bdd->query("INSERT INTO rob_imputl2 VALUES('', '$newprjcode', '$newprjdesc', '$newprjresp', 1, '$newprjplan')") or die();
							}
							$reponsesel = $bdd->query("SELECT ID FROM rob_imputl2 WHERE code='$newprjcode' LIMIT 1");
							$checkrep = $reponsesel->rowCount();
							if ($checkrep != 0)
							{
								$donneesel = $reponsesel->fetch();
								$newcomb2 = $donneesel['ID'];
							}
							else
							{
								$test = 'Probl&egrave;me au moment de l\'insertion du projet';
							}
							$reponsesel->closeCursor();
						}
						else
						{
							$test = 'Code projet inexistant 1';
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
								$test = 'Code projet inexistant 2';
							}
						}
						else
						{
							$test = 'Code projet inexistant 3';
						}
					}
					if ($test == '')
					{
						$reponse = $bdd->query("SELECT ID FROM rob_imprel2 WHERE imputID='$imputtmp' AND  imputID2='$newcomb2'");
						$checkrep = $reponse->rowCount();
						if ($checkrep != 0)
						{
							$info = $info.'. Cette combinaison existe d&eacute;j&agrave';
						}
						else
						{
							$bdd->query("INSERT INTO rob_imprel2 VALUES('', '$imputtmp', '$newcomb2', 1)");
						}
						$reponse->closeCursor();
					}
				}
				else
				{
					$test = 'Probl&egrave;me code client, merci de recharger la page';
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
					$respfact = $_POST['modrespfact'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("UPDATE rob_imputl2 SET description='$desc', respID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div class="background-db-management background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-temps">
		<div class="section-title">
			<h1>Client-Projets management</h1>
		</div>
		<div id="coeur">
		<?php
		if ($imputtmp !=0 AND $errvar == 0) {
			?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Client</th>
						<th>Projet</th>
						<th>Description</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T2.code codeclient, T2.description description, T0.ID id, T0.imputID inputid, T0.imputID2 inputid2, T0.actif actif, T1.code codeprojet
						FROM rob_imprel2 T0
						INNER JOIN rob_imputl2 T2 ON T2.ID = T0.imputID2
						INNER JOIN rob_imputl1 T1 ON T1.ID = T0.imputID
						WHERE T0.imputID = ".$imputtmp."
						ORDER BY T2.description";
						
					$reponse = $bdd->query($req);
					$checkrep = $reponse->rowCount();
					if ($checkrep != 0) {
						while ($donnee = $reponse->fetch() )
						{
							?>
							<tr>
								<td><?php echo $donnee['codeprojet'];?></td>
								<td><?php echo $donnee['codeclient'];?></td>
								<td><?php if ($donnee['description'] != "") { echo $donnee['description']; } ?></td>
								<?php if ($donnee['actif'] == 1)
								{ ?>
									<td>
										<form action="rell1l2.php" method="post">
											<input type="hidden" value="<?php echo $donnee['inputid'];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDinact" />
											<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver la relation"><i class="fa fa-toggle-on"></i></button>
										</form>
									</td>
									<td>
										<form action="rell1l2l3.php" method="post">
											<input type="hidden" value="<?php echo $donnee['inputid'];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee['inputid2'];?>" name="IDrel2" />
											<button class="btn btn-small btn-default btn-icon btn-orange" type="submit" title="Vers les missions en relation" name="relat"><i class="fa fa-link"></i></button>
										</form>
									</td>
									<?php
								} else {
									?>
									<td>
										<form action="rell1l2.php" method="post">
											<input type="hidden" value="<?php echo $donnee['inputid'];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee['id'];?>" name="IDact" />
											<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer la relation"><i class="fa fa-toggle-off"></i></button>
										</form>
									</td>
									<td>
										<form action="rell1l2l3.php" method="post">
											<input type="hidden" value="<?php echo $donnee['inputid'];?>" name="IDrel" />
											<input type="hidden" value="<?php echo $donnee['inputid2'];?>" name="IDrel2" />
											<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Vers les missions en relation" name="relat"><i class="fa fa-link"></i></button>
										</form>
									</td>
									<?php
								}
								?>
								<td>
									<form action="modif_imputl2.php" method="post">
										<input type="hidden" value="<?php echo $donnee['inputid2'];?>" name="IDmodif" />
										<input type="hidden" value="<?php echo $imputtmp;?>" name="IDrel" />
										<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
									</form>
								</td>
							</tr>
						<?php
						}
					}
					$reponse->closeCursor();
					?>
				</tbody>
			</table>
		</div>
		
		<h2>Ajouter une nouvelle relation client-Projet</h2>
			<form action="rell1l2.php" method="post">
				<table class="table table-striped table-align-top">
					<thead>
						<tr>
							<th>Client</th>
							<th colspan="3">Projet</th>
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
								<input type="hidden" value="<?php echo $imputtmp; ?>" name="IDrel" />
								<div id="ProjetHint">
									<div class="col-sm-3">
									<select class="form-control" name="client" onchange="showNewProj(this.value)">
										<option value="0">Projet existant</option>
										<option value="1">Ajouter un projet</option>
									</select>
									</div>
									<div class="col-sm-8">
										<?php
										echo ' <select class="form-control" name="newcomb2">';
											echo ' <option value="0"></option>';
											$req = "SELECT * FROM rob_imputl2 WHERE actif=1 ORDER BY code";
											$affpro = $bdd->query($req);
											while ($optionpro = $affpro->fetch()) {
												if (substr($optionpro['code'],0,3) != 'ABS') {
													echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
												}
											}
										echo '</select> ';
										$affpro->closeCursor();
										?>
									</div>
								</div>
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