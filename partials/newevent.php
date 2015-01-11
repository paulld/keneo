<p><strong>Nouvel &eacute;v&eacute;nement :</strong></p>

<div class="form-group form-group-new-project">
	<input class="form-control form-control-auto" type="text" size="17" name="newevecode" placeholder="Code" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control form-control-auto" type="text" size="17" name="neweveplan" placeholder="Alias" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control form-control-auto" type="text" size="17" name="newevedesc" placeholder="Description" />
</div>

<div class="form-group form-group-new-project">
	<select class="form-control form-control-auto" name="neweveresp" >
		<option value=0>Responsable</option>
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
