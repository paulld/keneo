<?php
//session_start();
//include("appel_db.php");

	?>
	<!-- <div class="col-sm-3">
		<select class="form-control" name="client" onchange="showCurrProj(this.value)">
			<option value="0">Projet existant</option>
			<option value="1" selected>Ajouter un projet</option>
		</select>
	</div>

	<div class="col-sm-8"> -->
		<!-- <p>Param&egrave;tres du nouveau projet</p> -->

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Code</label>
			<div class="col-sm-8">
				<input class="form-control" type="text" size="15" name="newprjcode" />
			</div>
		</div>

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Alias</label>
			<div class="col-sm-8">
				<input class="form-control" type="text" size="3" name="newprjplan" />
			</div>
		</div>

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Description</label>
			<div class="col-sm-8">
				<input class="form-control" type="text" size="45" name="newprjdesc" />
			</div>
		</div>

		<div class="form-group form-group-new-project">
			<label for="modcode" class="col-xs-4 control-label">Responsable</label>
			<div class="col-sm-8">
				<select class="form-control" name="newprjresp" >
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
	<!-- </div> -->