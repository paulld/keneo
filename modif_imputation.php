<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");	if (isset($_POST['IDmodif']))	{		$id = $_POST['IDmodif'];		$temp_nom = $bdd->query("SELECT * FROM rob_imputl1 WHERE ID='$id'");		while ($cur_nom = $temp_nom->fetch())		{			$modID = $cur_nom['ID'];			$code = $cur_nom['code'];			$plan = $cur_nom['plan'];			$desc = $cur_nom['description'];			$respfact = $cur_nom['respfactID'];		}		$temp_nom->closeCursor();	}	?>		    <!-- =================== SAISIE ================= -->	<div class="background-db-management background-image"></div>	<div class="overlay"></div>	<section class="container section-container section-toggle" id="saisie-temps">		<div class="section-title">			<h1>Modification</h1>		</div>		<div id="coeur">			<form class="form-horizontal" action="imputation.php" method="post">				<input type="hidden" value="<?php echo $modID;?>" name="modID" />								<div class="form-group">					<label for="modcode" class="col-xs-4 control-label">Code (10 charact&egrave;res)* :</label>					<div class="col-sm-6 col-xs-8">						<input class="form-control" type="text" size="12" value="<?php echo $code;?>" name="modcode" />					</div>				</div>								<div class="form-group">					<label for="modplan" class="col-xs-4 control-label">Code court (3 charact&egrave;res) :</label>					<div class="col-sm-6 col-xs-8">						<input class="form-control" type="text" size="5" value="<?php echo $plan;?>" name="modplan" />					</div>				</div>				<div class="form-group">					<label for="moddesc" class="col-xs-4 control-label">Description :</label>					<div class="col-sm-6 col-xs-8">						<input class="form-control" type="text" size="80" value="<?php echo $desc;?>" name="moddesc" />					</div>				</div>				<div class="form-group">					<label for="modrespfact" class="col-xs-4 control-label">Responsable facturation :</label>					<div class="col-sm-6 col-xs-8">						<?php 						echo ' <select class="form-control" name="modrespfact">';							echo '<option></option>';							$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");							while ($optioncoll = $affcollab->fetch()) {								if ($optioncoll['ID'] == $respfact) {									echo '<option value='.$optioncoll['ID'].' selected>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';								} else {									echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';								}							}							$affcollab->closeCursor();						echo '</select>'; 						?>					</div>				</div>								<div class="form-group">					<div class="col-xs-offset-4 col-xs-8">						<input class="btn btn-primary" type="submit" Value="Enregistrer" name="Valider" />						<span class="small">* Champ obligatoire</span>					</div>				</div>				<div class="form-group">					<div class="col-xs-offset-4 col-xs-8">						<a class="btn btn-default" type="button" href="imputation.php">Retour</a>					</div>				</div>			</form>		</div>	</section>	<?php	include("footer.php");}else{	header("location:index.php");}?>