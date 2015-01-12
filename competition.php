<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['IDinact']))
	{
		$id = $_POST['IDinact'];
		$bdd->query("UPDATE rob_compl1 SET actif=0 WHERE ID='$id'");
	}
	else
	{
		if (isset($_POST['IDact']))
		{
			$id = $_POST['IDact'];
			$bdd->query("UPDATE rob_compl1 SET actif=1 WHERE ID='$id'");
		}
		else
		{
			if (isset($_POST['newcode']))
			{
				$code = strtoupper($_POST['newcode']);
				$checkcode = $bdd->query("SELECT code FROM rob_compl1 WHERE code='$code'");
				$codepris = $checkcode->rowCount();
				$checkcode->closeCursor();
				if ($codepris != 0)
				{
					echo 'Ce code de comp&eacute;tition existe d&eacute;j&agrave';
				}
				else
				{
					$plan = strtoupper($_POST['plan']);
					$desc = $_POST['desc'];
					$respfact = $_POST['respfact'];
					$desc = str_replace("'","\'",$desc);
					$bdd->query("INSERT INTO rob_compl1 VALUES('', '$code', '$desc', '$respfact', 1, '$plan')");
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
					$bdd->query("UPDATE rob_compl1 SET description='$desc', respfactID='$respfact', plan='$plan', code='$code' WHERE ID='$modID'");
				}
			}
		}
	}
	?>
	<div class="background-competitions background-image"></div>
	<div class="overlay"></div>

	<section class="container section-container" id="historique-frais">

		<div class="section-title">
			<h1>Comp&eacute;titions management</h1>
		</div>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Comp&eacute;tition</th>
						<th>Description</th>
						<th>Alias</th>
						<th>Responsable</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$req="SELECT T1.code, T1.description, T1. plan, T2.prenom, T2.nom ,T1.actif, T1.ID
						FROM rob_compl1 T1
						LEFT JOIN rob_user T2 ON T1.respfactID = T2.ID
						ORDER BY T1.code";
					$reponse = $bdd->query($req);
					while ($donnee = $reponse->fetch() ) {
					?>
						<tr>
							<td>
								<?php echo $donnee[0];?>
							</td>
							<td>
								<?php if ($donnee[1] == "") { echo '-'; } else { echo $donnee[1]; }?>
							</td>
							<td>
								<?php if ($donnee[2] == "") { echo '-'; } else { echo $donnee[2]; }?>
							</td>
							<td>
								<?php if ($donnee[3] == "") { echo '-'; } else { echo $donnee[3].' '.$donnee[4]; }?>
							</td>
							<?php 
							if ($donnee[5] == 1) { 
								?>
								<td>
									<form action="competition.php" method="post">
										<input type="hidden" value="<?php echo $donnee[6];?>" name="IDinact" />
										<button class="btn btn-small btn-default btn-icon btn-green" type="submit" title="D&eacute;sactiver le code"><i class="fa fa-toggle-on"></i></button>
									</form>
								</td>
								<td>
									<form action="comprell1l2.php" method="post">
										<input type="hidden" value="<?php echo $donnee[6];?>" name="IDrel" />
										<button class="btn btn-small btn-default btn-icon btn-orange" type="submit" title="Vers les projets en relation avec ce client" name="relat"><i class="fa fa-link"></i></button>
									</form>
								</td>
								<?php
							} else {
								?>
								<td>
									<form action="competition.php" method="post">
										<input type="hidden" value="<?php echo $donnee[6];?>" name="IDact" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Activer le code"><i class="fa fa-toggle-off"></i></button>
									</form>
								</td>
								<td>
									<form action="comprell1l2.php" method="post">
										<input type="hidden" value="<?php echo $donnee[6];?>" name="IDrel" />
										<button class="btn btn-small btn-default btn-icon btn-red" type="submit" title="Vers les projets en relation avec ce client" name="relat"><i class="fa fa-link"></i></button>

									</form>
								</td>
								<?php
							}
								?>
								<td>
									<form action="modif_competition.php" method="post">
										<input type="hidden" value="<?php echo $donnee[6];?>" name="IDmodif" />
										<button class="btn btn-small btn-default btn-icon btn-blue" type="submit" title="Modifier les informations" name="modif"><i class="fa fa-pencil-square-o"></i></button>
									</form>
								</td>
						</tr>
					<?php
					}
					$reponse->closeCursor();
					?>
				</tbody>
			</table>
		</div>

		<h2>Ajouter une nouvelle comp&eacute;tition</h2>
		<form action="competition.php" method="post">
			<div class="table-responsive">
				<table class="table table-striped temp-table">
					<thead>
						<tr>
							<th>Code</th>
							<th>Comp&eacute;tition</th>
							<th>Alias</th>
							<th>Responsable</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input class="form-control" type="text" size="15" name="newcode" />
							</td>
							<td>
								<input class="form-control" type="text" size="50" name="desc" />
							</td>
							<td>
								<input class="form-control" type="text" size="3" name="plan" />
							</td>
							<td>
								<select class="form-control" name="respfact">
									<option></option>
									<?php 
										$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
										while ($optioncoll = $affcollab->fetch()) {
											echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
										}
										$affcollab->closeCursor();
									?>
								</select>
							</td>
							<td>
								<input class="btn btn-primary" type="submit" Value="Ajouter" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
	</section>
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>