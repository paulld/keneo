<?php
session_start();
include("appel_db.php");

	?>
	<select onchange="showCurrMis(this.value)" id="w_input_90">
		<option value="0">Mission existante</option>
		<option value="1" selected>Ajouter une mission</option>
	</select><br/>
	<?php
	echo '<table id="newimput"><tr><th id="newimputth" colspan="2">Param&egrave;tres de la nouvelle mission</th></tr>';
	echo '<tr><td id="newimputtd">Code :</td><td id="newimputtd"><input id="w_input_90" type="text" size="15" name="newmiscode" /></td></tr>';
	echo '<tr><td id="newimputtd">Alias :</td><td id="newimputtd"><input id="w_input_90" type="text" size="3" name="newmisplan" /></td></tr>';
	echo '<tr><td id="newimputtd">Desc. :</td><td id="newimputtd"><input id="w_input_90" type="text" size="50" name="newmisdesc" /></td></tr>';
	echo '<tr><td id="newimputtd">Resp. :</td><td id="newimputtd">';
		echo '<select name="newmisresp" id="w_input_90" >';
			echo '<option value=0></option>';
			$affcollab = $bdd->query("SELECT * FROM rob_user WHERE actif='1' ORDER BY nom");
			while ($optioncoll = $affcollab->fetch())
			{
				echo '<option value='.$optioncoll['ID'].'>'.substr ($optioncoll['prenom'],0,1).'. '.$optioncoll['nom'].'</option>';
			}
			$affcollab->closeCursor();
		echo '</select></td></tr></table>';
	echo '<input id="w_input_90val" type="submit" Value="Ajouter" />';

?> 