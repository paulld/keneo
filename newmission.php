<?php
session_start();
include("appel_db.php");

	?>
	<div class="col-sm-3">
		<select class="form-control" onchange="showCurrMis(this.value)">
			<option value="0">Mission existante</option>
			<option value="1" selected>Ajouter une mission</option>
		</select>
	</div>

	<div class="col-sm-7">
	
		<!-- <p>Param&egrave;tres de la nouvelle mission</p> -->

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Code :</label>
			<div class="col-sm-8">
				<input class="form-control" type="text" size="15" name="newmiscode" />
			</div>
		</div>

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Alias :</label>
			<div class="col-sm-8">
				<input class="form-control" type="text" size="3" name="newmisplan" />
			</div>
		</div>

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Desc. :</label>
			<div class="col-sm-8">
				<input class="form-control" type="text" size="50" name="newmisdesc" />
			</div>
		</div>

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Resp. :</label>
				<div class="col-sm-8">
				<select class="form-control" name="newmisresp" >
					<option value=0></option>
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
	<input class="btn btn-primary" type="submit" Value="Ajouter" />
