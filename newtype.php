<?php
session_start();
include("appel_db.php");

	?>
	<select name="client" onchange="showCurrTyp(this.value)" id="w_input_90">
		<option value="0">Type existant</option>
		<option value="1" selected>Ajouter un type</option>
	</select><br/>
	<?php
	echo '<table id="newimput"><tr><th id="newimputth" colspan="2">Param&egrave;tres du nouveau type</th></tr>';
	echo '<tr><td id="newimputtdr">Code</td><td id="newimputtd"><input id="w_input_90" type="text" size="15" name="newtypcode" /></td></tr>';
	echo '<tr><td id="newimputtdr">Alias</td><td id="newimputtd"><input id="w_input_90" type="text" size="3" name="newtypplan" /></td></tr>';
	echo '<tr><td id="newimputtdr">Description</td><td id="newimputtd"><input id="w_input_90" type="text" size="45" name="newtypdesc" /></td></tr>';
	echo '<tr><td id="newimputtdr">Responsable</td><td id="newimputtd">';
		echo '<select name="newtypresp" id="w_input_90" >';
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