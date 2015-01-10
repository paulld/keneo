<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	// ==================== TRAITEMENT =====================
	$prblm = 0;
	if (isset($_POST['new_user']))
	{
		$new_ctHybride = $_POST['new_ctHybride'];
		$new_ctReel = $_POST['new_ctReel'];
		$new_user = $_POST['new_user'];
		$new_validFrom = date('Y-m-d',mktime(0,0,0,substr($_POST['new_validFrom'],3,2),substr($_POST['new_validFrom'],0,2),substr($_POST['new_validFrom'],6,4)));
		$new_validTo = date('Y-m-d',mktime(0,0,0,substr($_POST['new_validTo'],3,2),substr($_POST['new_validTo'],0,2),substr($_POST['new_validTo'],6,4)));
		$req = "INSERT INTO rob_user_fi VALUES('','$new_user', '$new_ctHybride', '$new_ctReel', '$new_validFrom', '$new_validTo')";
		echo $req;
		$bdd->query($req);
	}
	else
	{
		if (isset($_POST['IDmodif']) AND isset($_POST['ctHybride']) AND isset($_POST['ctReel']) AND isset($_POST['validFrom']) AND isset($_POST['validTo']))
		{
			$validFrom = date('Y-m-d',mktime(0,0,0,substr($_POST['validFrom'],3,2),substr($_POST['validFrom'],0,2),substr($_POST['validFrom'],6,4)));
			$validTo = date('Y-m-d',mktime(0,0,0,substr($_POST['validTo'],3,2),substr($_POST['validTo'],0,2),substr($_POST['validTo'],6,4)));
			$req = "UPDATE rob_user_fi SET 
				ctHybride = ".$_POST['ctHybride'].",
				ctReel = ".$_POST['ctReel'].",
				validFrom = '".$validFrom."',
				validTo = '".$validTo."'
				WHERE ID = ".$_POST['IDmodif'];
			$bdd->query($req);
		}
		else
		{
			if (isset($_POST['IDmodif']))
			{
				$prblm = 1;
			}
		}
	}

	if ($prblm == 1)
	{
		echo '<div id="message">l\'enregistrement n\'a pas pu &ecirc;tre modifi&eacute;</div>';
	}
	// ==================== SAISIE =====================
	?>
	
	
	<div class="background-frais background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container section-toggle" id="saisie-frais">
		<div class="section-title" id="toggle-title3">
			<h1>
				<i class="fa fa-chevron-up"></i>
				Ajouter une nouvelle combinaison
				<i class="fa fa-chevron-up"></i>
			</h1>
		</div>
		<div id="toggle-content3">
			<form action="donneesfi.php" method="post">
				<table class="table table-striped temp-table">
					<thead>
						<tr>
							<th>Nom</th>
							<th>Co&ucirc;t hybride</th>
							<th>Co&ucirc;t r&eacute;el</th>
							<th>Valide du</th>
							<th>Valide jusqu'&agrave;</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php
								echo '<select class="form-control" name="new_user"><option value=0>User...</option>';
								$reponse = $bdd->query("SELECT nom, prenom, ID FROM rob_user WHERE actif=1 ORDER BY nom");
								while ($donnee = $reponse->fetch() )
								{
									echo '<option value="'.$donnee['ID'].'">'.$donnee['nom'].'. '.substr ($donnee['prenom'],0,1).'</option>';
								}
								$reponse->closeCursor();
								echo '</select>';
								?>
							</td>
							<td>
								<input class="form-control" type="text" name="new_ctHybride" style="text-align:right" />
							</td>
							<td>
								<input class="form-control" type="text" name="new_ctReel" style="text-align:right" />
							</td>
							<td>
								<input class="form-control" id="datefrais" type="text" name="new_validFrom" placeholder="JJ/MM/AAAA" />
							</td>
							<td>
								<input class="form-control" id="dateTransac" type="text" name="new_validTo" placeholder="JJ/MM/AAAA" />
							</td>
							<td>
								<input class="btn btn-primary" type="submit" Value="Ajouter" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</section>

	<section class="container section-container section-toggle" id="effectif-interne">
		<div class="section-title" id="toggle-title">
			<h1>
				<i class="fa fa-chevron-up"></i>
				Donn&eacute;es financi&egrave;res - Effectif interne
				<i class="fa fa-chevron-up"></i>
			</h1>
		</div>
		<div id="toggle-content" >
			<table class="table table-striped" id="effectif-interne-table">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Co&ucirc;t hybride</th>
						<th>Co&ucirc;t r&eacute;el</th>
						<th>Valide du</th>
						<th>Valide jusqu'&agrave;</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req = "SELECT T2.nom nom, T2.prenom prenom, T1.ID ID, T1.ctHybride ctHybride, T1.ctReel ctReel, T1.validFrom validFrom, T1.validTo validTo
							FROM rob_user_fi T1 
							INNER JOIN rob_user T2 ON T1.userID = T2.ID
							INNER JOIN rob_user_rights T3 ON T1.userID = T3.ID
							WHERE T3.extstd = 2 ORDER BY T2.nom, T1.validFrom";
					$reponse = $bdd->query($req );
					while ($donnee = $reponse->fetch() ) {
						?>
						<form action="donneesfi.php" method="post">
							<tr>
								<td>
									<?php echo $donnee['nom'].'. '.substr ($donnee['prenom'],0,1);?>
								</td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="ctHybride" value="<?php echo $donnee['ctHybride'];?>" style="text-align:right" /></td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="ctReel" value="<?php echo $donnee['ctReel'];?>" style="text-align:right" /></td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="validFrom" value="<?php echo date("d/m/Y",strtotime($donnee['validFrom']));?>" /></td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="validTo" value="<?php echo date("d/m/Y",strtotime($donnee['validTo']));?>" /></td>
								<td>
									<input type="hidden" value="<?php echo $donnee['ID'];?>" name="IDmodif" />
									<input class="btn btn-primary form-control-inner-small" type="submit" Value="MaJ" />
								</td>
							</tr>
						</form>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</section>

	<section class="container section-container section-toggle" id="effectif-externe">
		<div class="section-title" id="toggle-title2">
			<h1>
				<i class="fa fa-chevron-down"></i>
				Team Keneo - Effectif externe
				<i class="fa fa-chevron-down"></i>
			</h1>
		</div>
		<div id="toggle-content2" style="display: none;">
			<table class="table table-striped" id="effectif-externe-table">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Co&ucirc;t hybride</th>
						<th>Co&ucirc;t r&eacute;el</th>
						<th>Valide du</th>
						<th>Valide jusqu'&agrave;</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req = "SELECT T2.nom nom, T2.prenom prenom, T1.ID ID, T1.ctHybride ctHybride, T1.ctReel ctReel, T1.validFrom validFrom, T1.validTo validTo
							FROM rob_user_fi T1 
							INNER JOIN rob_user T2 ON T1.userID = T2.ID
							INNER JOIN rob_user_rights T3 ON T1.userID = T3.ID
							WHERE T3.extstd = 1 ORDER BY T2.nom, T1.validFrom";
					$reponse = $bdd->query($req );
					while ($donnee = $reponse->fetch() ) {
						?>
						<form action="donneesfi.php" method="post">
							<tr>
								<td>
									<?php echo $donnee['nom'].'. '.substr ($donnee['prenom'],0,1);?>
								</td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="ctHybride" value="<?php echo $donnee['ctHybride'];?>" style="text-align:right" /></td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="ctReel" value="<?php echo $donnee['ctReel'];?>" style="text-align:right" /></td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="validFrom" value="<?php echo date("d/m/Y",strtotime($donnee['validFrom']));?>" /></td>
								<td>
									<input class="form-control form-control-inner-small" type="text" name="validTo" value="<?php echo date("d/m/Y",strtotime($donnee['validTo']));?>" /></td>
								<td>
									<input type="hidden" value="<?php echo $donnee['ID'];?>" name="IDmodif" />
									<input class="btn btn-primary form-control-inner-small" type="submit" Value="MaJ" />
								</td>
							</tr>
						</form>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</section>

<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>