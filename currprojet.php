<?php
session_start();
include("appel_db.php");

	?>
	<select name="client" onchange="showNewProj(this.value)" id="w_input_90">
		<option value="0">Projet existant</option>
		<option value="1">Ajouter un projet</option>
	</select>
	
	<?php
	echo ' <select name="newcomb2" id="w_input_90" >';
	echo ' <option value=0></option>';
	$req = "SELECT * FROM rob_imputl2 WHERE actif=1 ORDER BY code";
	$affpro = $bdd->query($req);
	while ($optionpro = $affpro->fetch())
	{
		if (substr($optionpro['code'],0,3) != 'ABS')
		{
			echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
		}
	}
	$affpro->closeCursor();
	echo '</select> ';
	echo '<input id="w_input_90val" type="submit" Value="Ajouter" />';
?>