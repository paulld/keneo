<select class="form-control" name="newcomb3">
	<option value="0"></option>
	<?php
		$req = "SELECT * FROM rob_imputl3 WHERE actif=1 ORDER BY code";
		$affpro = $bdd->query($req);
		while ($optionpro = $affpro->fetch())
		{
			echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
		}
		$affpro->closeCursor();
	?>
</select>
