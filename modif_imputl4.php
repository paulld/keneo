<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");	if (isset($_POST['IDmodif']))	{		$modID = $_POST['IDmodif'];		mysql_connect($DBHOST,$DBUSER,$DBPASSWD);		mysql_select_db ($DBNAME);		$temp_nom = mysql_query("SELECT * FROM rob_imputl4 WHERE ID='$modID'");		while ($cur_nom = mysql_fetch_array($temp_nom))		{			$code = $cur_nom['code'];			$plan = $cur_nom['plan'];			$desc = $cur_nom['description'];			$respfact = $cur_nom['respID'];		}		mysql_close();	}	if (isset($_POST['IDinact']))	{		mysql_connect($DBHOST,$DBUSER,$DBPASSWD);		mysql_select_db ($DBNAME);		$id = $_POST['IDinact'];		mysql_query("UPDATE rob_imprel4 SET actif=0 WHERE ID='$id'");		mysql_close();	}	else	{		if (isset($_POST['IDact']))		{			mysql_connect($DBHOST,$DBUSER,$DBPASSWD);			mysql_select_db ($DBNAME);			$id = $_POST['IDact'];			mysql_query("UPDATE rob_imprel4 SET actif=1 WHERE ID='$id'");			mysql_close();		}	}	?>		    <!-- =================== SAISIE ================= -->	<div class="background-clients background-image"></div>	<div class="overlay"></div>	<section class="container section-container section-toggle" id="saisie-temps">		<div class="section-title">			<h1>Modification</h1>		</div>		<div id="coeur">			<form class="form-horizontal" action="rell1l2l3l4.php" method="post">				<input type="hidden" value="<?php echo $modID;?>" name="modID" />				<?php					if (isset($_POST['IDrel'])) {						echo '<input type="hidden" value="'.$_POST['IDrel'].'" name="IDrel" />';						echo '<input type="hidden" value="'.$_POST['IDrel2'].'" name="IDrel2" />';						echo '<input type="hidden" value="'.$_POST['IDrel3'].'" name="IDrel3" />';					}				?>								<div class="form-group">					<label for="modcode" class="col-xs-4 control-label">Code (10 charact&egrave;res)* :</label>					<div class="col-sm-6 col-xs-8">						<input class="form-control" type="text" size="12" value="<?php echo $code;?>" name="modcode" />					</div>				</div>				<div class="form-group">					<label for="modplan" class="col-xs-4 control-label">Code court (3 charact&egrave;res) :</label>					<div class="col-sm-6 col-xs-8">						<input class="form-control" type="text" size="5" value="<?php echo $plan;?>" name="modplan" />					</div>				</div>								<div class="form-group">					<label for="moddesc" class="col-xs-4 control-label">Description :</label>					<div class="col-sm-6 col-xs-8">						<input class="form-control" type="text" size="80" value="<?php echo $desc;?>" name="moddesc" />					</div>				</div>								<div class="form-group">					<label for="modrespfact" class="col-xs-4 control-label">Responsable :</label>					<div class="col-sm-6 col-xs-8">					  <select class="form-control" name="modrespfact">							<option></option>								<?php 									mysql_connect($DBHOST,$DBUSER,$DBPASSWD);									mysql_select_db ($DBNAME);									$affcollab = mysql_query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");									mysql_close();									while ($optioncoll = mysql_fetch_array($affcollab)) {										if ($optioncoll['ID'] == $respfact) {											echo '<option value='.$optioncoll['ID'].' selected>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';										} else {											echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';										}									}								?>							</select>						</div>					</div>					<div class="form-group">						<div class="col-xs-offset-4 col-xs-8">							<input class="btn btn-primary" type="submit" Value="Enregistrer" name="Valider" />							<span class="small">* Champ obligatoire</span>						</div>					</div>					<div class="form-group">						<div class="col-xs-offset-4 col-xs-8">							<?php								if (isset($_POST['IDrel'])) { 									echo '<a class="btn btn-default" href="rell1l2.php?IDrel='.$_POST['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projets</a> ';									} else { 									echo '<a class="btn btn-default" href="rell1l2.php?IDrel='.$_GET['IDrel'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projets</a> ';									}							?>						</div>					</div>					<div class="form-group">						<div class="col-xs-offset-4 col-xs-8">							<?php								if (isset($_POST['IDrel'])) { 									echo '<a class="btn btn-default" href="rell1l2l3.php?IDrel='.$_POST['IDrel'].'&amp;IDrel2='.$_POST['IDrel2'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projet-Missions</a> ';								} else { 									echo '<a class="btn btn-default" href="rell1l2l3.php?IDrel='.$_GET['IDrel'].'&amp;IDrel2='.$_GET['IDrel2'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projet-Missions</a> ';								}							?>						</div>					</div>					<div class="form-group">						<div class="col-xs-offset-4 col-xs-8">							<?php								if (isset($_POST['IDrel'])) { 									echo '<a class="btn btn-default" href="rell1l2l3l4.php?IDrel='.$_POST['IDrel'].'&amp;IDrel2='.$_POST['IDrel2'].'&amp;IDrel3='.$_POST['IDrel3'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projet-Mission-Cat&eacute;gories</a>';								} else { 									echo '<a class="btn btn-default" href="rell1l2l3l4.php?IDrel='.$_GET['IDrel'].'&amp;IDrel2='.$_GET['IDrel2'].'&amp;IDrel3='.$_GET['IDrel3'].'"><i class="fa fa-arrow-left"></i> Retour &agrave; Client-Projet-Mission-Cat&eacute;gories</a>';								}							?>						</div>					</div>				</form>			</div>		</div>	</section>	<?php	include("footer.php");}else{	header("location:index.php");}?>