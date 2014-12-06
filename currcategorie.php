<?php
session_start();
include("appel_db.php");

	?>
	<select onchange="showNewMis(this.value)" id="w_input_90">
		<option value="0">Cat&eacute;gorie existante</option>
		<option value="1">Ajouter une cat&eacute;gorie</option>
	</select>
	
	<?php
	echo ' <select name="newcomb4" id="w_input_90" >';
	echo ' <option value=0></option>';
	$req = "SELECT * FROM rob_imputl4 WHERE actif=1 ORDER BY code";
	$affpro = $bdd->query($req);
	while ($optionpro = $affpro->fetch())
	{
		echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
	}
	echo '</select> ';
	$affpro->closeCursor();
	?>
	<input id="w_input_90val" type="submit" Value="Ajouter" />
