<p><strong>Nouvelle mission :</strong></p>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="15" name="newmiscode" placeholder="Code" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="3" name="newmisplan" placeholder="Alias" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="50" name="newmisdesc" placeholder="Description" />
</div>

<div class="form-group form-group-new-project">
	<select class="form-control" name="newmisresp" >
		<option value="0">Responsable</option>
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
