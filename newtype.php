<div class="row">
	<!-- <p>Param&egrave;tres du nouveau type</p> -->

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Code</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" size="15" name="newtypcode" />
		</div>
	</div>

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Alias</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" size="3" name="newtypplan" />
		</div>
	</div>

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Description</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" size="45" name="newtypdesc" />
		</div>
	</div>

	<div class="form-group form-group-new-project">
		<label for="modcode" class="col-xs-4 control-label">Responsable</label>
		<div class="col-sm-8">
			<select class="form-control" name="newtypresp" >';
				<option value=0></option>';
				<?php
					$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
					while ($optioncoll = $affcollab->fetch())
					{
						echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
					}
					$affcollab->closeCursor();
				?>
			</select>
		</div>
	</div>

</div>