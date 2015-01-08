<?php
session_start();
include("appel_db.php");

?>
<div class="col-sm-4">
	<select onchange="showCurrCat(this.value)" class="form-control">
		<option value="0">Cat&eacute;gorie existante</option>
		<option value="1" selected>Ajouter une cat&eacute;gorie</option>
	</select>
</div>

<div class="col-sm-6">
	<!-- <p>Param&egrave;tres de la nouvelle cat&eacute;gorie</p> -->
	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Code :</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" size="15" name="newcatcode" />
		</div>
	</div>

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Alias :</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" size="3" name="newcatplan" />
		</div>
	</div>

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Desc. :</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" size="50" name="newcatdesc" />
		</div>
	</div>

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Resp. :</label>
		<div class="col-sm-8">

			<select name="newcatresp" class="form-control" >
				<option value=0></option>
				<?php
					$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
					while ($optioncoll = $affcollab->fetch()) {
						echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
					}
					$affcollab->closeCursor();
				?> 
			</select>
		</div>
	</div>
	
</div>
<!-- <input class="btn btn-primary" type="submit" Value="Ajouter" /> -->
