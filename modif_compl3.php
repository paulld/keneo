<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");	if (isset($_POST['IDmodif']))	{		$modID = $_POST['IDmodif'];		$temp_nom = $bdd->query("SELECT * FROM rob_compl3 WHERE ID='$modID'");		while ($cur_nom = $temp_nom->fetch())		{			$code = $cur_nom['code'];			$plan = $cur_nom['plan'];			$desc = $cur_nom['description'];			$respfact = $cur_nom['respID'];		}		$temp_nom->closeCursor();	}	if (isset($_POST['IDinact']))	{		$id = $_POST['IDinact'];		$bdd->query("UPDATE rob_comprel3 SET actif=0 WHERE ID='$id'");	}	else	{		if (isset($_POST['IDact']))		{			$id = $_POST['IDact'];			$bdd->query("UPDATE rob_comprel3 SET actif=1 WHERE ID='$id'");		}	}	?>		  <!-- =================== SAISIE ================= -->	<div class="background-competitions background-image"></div>	<div class="overlay"></div>	<section class="container section-container section-toggle" id="saisie-temps">		<div class="section-title">			<h1>Modification</h1>		</div>				<form class="form-horizontal" action="comprell1l2l3.php" method="post">			<input type="hidden" value="<?php echo $modID;?>" name="modID" />						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Code (10 charact&egrave;res)* :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="12" value="<?php echo $code;?>" name="modcode" />				</div>			</div>						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Code court (3 charact&egrave;res) :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="5" value="<?php echo $plan;?>" name="modplan" />				</div>			</div>						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Description :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="50" value="<?php echo $desc;?>" name="moddesc" />				</div>			</div>						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Responsable :</label>				<div class="col-sm-6 col-xs-8">					<select class="form-control" name="modrespfact">						<option></option>						<?php							$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");							while ($optioncoll = $affcollab->fetch()) {								if ($optioncoll['ID'] == $respfact) {									echo '<option value='.$optioncoll['ID'].' selected>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';								} else {									echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';								}							}							$affcollab->closeCursor();						?>					</select>				</div>			</div>			<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<?php					if (isset($_POST['IDrel'])) {						echo '<input type="hidden" value="'.$_POST['IDrel'].'" name="IDrel" />';						echo '<input type="hidden" value="'.$_POST['IDrel2'].'" name="IDrel2" />';					}					?>					<input class="btn btn-primary" type="submit" Value="Enregistrer" name="Valider" />					<span class="small">* Champ obligatoire</span>				</div>			</div>						<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<?php						if (isset($_POST['IDrel'])) { 							echo '<div><a class="btn btn-default" href="comprell1l2.php?IDrel='.$_POST['IDrel'].'">Retour &agrave; Comp&eacute;tition-Types</a></div>';						} else { 							echo '<div><a class="btn btn-default" href="comprell1l2.php?IDrel='.$_GET['IDrel'].'">Retour &agrave; Comp&eacute;tition-Types</a></div>';						}									?>				</div>			</div>			<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<?php						if (isset($_POST['IDrel'])) {							echo '<div><a class="btn btn-default" href="comprell1l2l3.php?IDrel='.$_POST['IDrel'].'&amp;IDrel2='.$_POST['IDrel2'].'">Retour &agrave; Comp&eacute;tition-Type-&Eacute;v&eacute;nements</a></div>';						} else {							echo '<div><a class="btn btn-default" href="comprell1l2l3.php?IDrel='.$_GET['IDrel'].'&amp;IDrel2='.$_GET['IDrel2'].'">Retour &agrave; Comp&eacute;tition-Type-&Eacute;v&eacute;nements</a></div>';						}									?>				</div>			</div>		</form>	</div>	<?php	include("footer.php");}else{	header("location:index.php");}?>