<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");	if (isset($_POST['IDmodif']))	{		$id = $_POST['IDmodif'];		$temp_nom = $bdd->query("SELECT * FROM rob_pole WHERE ID='$id'");		while ($cur_nom = $temp_nom->fetch())		{			$modID = $cur_nom['ID'];			$code = $cur_nom['code'];			$desc = $cur_nom['desc1'];			$respfact = $cur_nom['respID'];		}		$temp_nom->closeCursor();	}	?>		  <!-- =================== SAISIE ================= -->	<div class="background-tables background-image"></div>	<div class="overlay"></div>	<section class="container section-container section-toggle">		<div class="section-title">			<h1>Modification</h1>		</div>		<form class="form-horizontal" action="pole.php" method="post">			<input type="hidden" value="<?php echo $modID;?>" name="modID" />						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Code (10 charact&egrave;res)* :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="12" value="<?php echo $code;?>" name="modcode" />				</div>			</div>						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Description :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="50" value="<?php echo $desc;?>" name="moddesc" /></div>				</div>			</div>						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Responsable facturation :</label>				<div class="col-sm-6 col-xs-8">					<select class="form-control" name="modrespfact">						<option></option>						<?php 							$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");							while ($optioncoll = $affcollab->fetch())							{								if ($optioncoll['ID'] == $respfact)								{									echo '<option value='.$optioncoll['ID'].' selected>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';								}								else								{									echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';								}							}							$affcollab->closeCursor();						?>					</select>				</div>			</div>			<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<button class="btn btn-primary" type="submit" name="Valider"><i class="fa fa-floppy-o"></i> Enregistrer</button>					<span class="small">* Champ obligatoire</span>				</div>			</div>			<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<a class="btn btn-default" type="button" href="pole.php"><i class="fa fa-arrow-left"></i> Retour &agrave; P&ocirc;le</a>				</div>			</div>		</form>	</div>	<?php	include("footer.php");}else{	header("location:index.php");}?>