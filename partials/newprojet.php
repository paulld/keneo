<p><strong>Nouveau projet :</strong></p>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="15" name="newprjcode" placeholder="Code" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="3" name="newprjplan" placeholder="Alias" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="45" name="newprjdesc" placeholder="Description" />
</div>

<div class="form-group form-group-new-project">
	<select class="form-control" name="newprjresp" >
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