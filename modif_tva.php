<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");	if (isset($_POST['IDmodif']))	{		$id = $_POST['IDmodif'];		$temp_nom = $bdd->query("SELECT * FROM rob_tva WHERE ID='$id'");		while ($cur_nom = $temp_nom->fetch())		{			$modID = $cur_nom['ID'];			$code = $cur_nom['type'];			$desc = $cur_nom['taux'];		}		$temp_nom->closeCursor();	}	?>		  <!-- =================== SAISIE ================= -->	<div class="background-tables background-image"></div>	<div class="overlay"></div>	<section class="container section-container section-toggle">		<div class="section-title">			<h1>Modification</h1>		</div>		<form class="form-horizontal" action="tva.php" method="post">			<input type="hidden" value="<?php echo $modID;?>" name="modID" />						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Type* :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="80" value="<?php echo $code;?>" name="modcode" />				</div>			</div>						<div class="form-group">				<label for="modcode" class="col-xs-4 control-label">Taux* :</label>				<div class="col-sm-6 col-xs-8">					<input class="form-control" type="text" size="10" value="<?php echo $desc;?>" name="moddesc" />				</div>			</div>			<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<input class="btn btn-primary" type="submit" Value="Enregistrer" name="Valider" />					<span class="small">* Champ obligatoire</span>				</div>			</div>			<div class="form-group">				<div class="col-xs-offset-4 col-xs-8">					<a class="btn btn-default" type="button" href="tva.php"><i class="fa fa-arrow-left"></i> Retour &agrave; Taux de TVA</a>				</div>			</div>		</form>	</div>	<?php	include("footer.php");}else{	header("location:index.php");}?>