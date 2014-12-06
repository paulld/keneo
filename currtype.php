<?php
session_start();
include("appel_db.php");

	?>
	<select name="client" onchange="showNewTyp(this.value)" id="w_input_90">
		<option value="0">Type existant</option>
		<option value="1">Ajouter un type</option>
	</select><br/>
	
	<?php
	echo ' <select name="newcomb2" id="w_input_90" >';
	echo ' <option value=0></option>';
	$req = "SELECT * FROM rob_compl2 WHERE actif=1 ORDER BY code";
	$affpro = $bdd->query($req);
	while ($optionpro = $affpro->fetch())
	{
		echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
	}
	echo '</select> ';
	$affpro->closeCursor();
	echo '<input id="w_input_90val" type="submit" Value="Ajouter" />';
?>