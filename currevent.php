<?php
session_start();
include("appel_db.php");

	?>
	<select onchange="showNewEve(this.value)" id="w_input_90">
		<option value="0">&Eacute;v&eacute;nement existant</option>
		<option value="1">Ajouter un &Eacute;v&eacute;nement</option>
	</select>
	
	<?php
	echo ' <select name="newcomb3" id="w_input_90" >';
	echo ' <option value=0></option>';
	$req = "SELECT * FROM rob_compl3 WHERE actif=1 ORDER BY code";
	$affmis = $bdd->query($req);
	while ($optionmis = $affmis->fetch())
	{
		echo '<option value='.$optionmis['ID'].'>'.$optionmis['code'].' | '.$optionmis['description'].'</option>';
	}
	echo '</select> ';
	$affmis->closeCursor();
	?>
	<input id="w_input_90val" type="submit" Value="Ajouter" />
