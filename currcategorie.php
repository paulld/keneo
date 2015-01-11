<select name="newcomb4" class="form-control">
	<option value=0></option>
	<?php
		$req = "SELECT * FROM rob_imputl4 WHERE actif=1 ORDER BY code";
		$affpro = $bdd->query($req);
		while ($optionpro = $affpro->fetch())
		{
			echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
		}
		$affpro->closeCursor();
	?>
</select>
