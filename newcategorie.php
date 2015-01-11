<p><strong>Nouvelle cat&eacute;gorie :</strong></p>

<div class="form-group form-group-new-project">
		<input class="form-control" type="text" size="15" name="newcatcode" placeholder="Code" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="3" name="newcatplan" placeholder="Alias" />
</div>

<div class="form-group form-group-new-project">
	<input class="form-control" type="text" size="50" name="newcatdesc" placeholder="Description" />
</div>

<div class="form-group form-group-new-project">
	<select name="newcatresp" class="form-control" >
		<option value="0">Responsable</option>
		<?php
			$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
			while ($optioncoll = $affcollab->fetch()) {
				echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
			}
			$affcollab->closeCursor();
		?> 
	</select>
</div>
