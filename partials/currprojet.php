<select class="form-control" name="newcomb2">
	<option value="0"></option>
	<?php
		$req = "SELECT * FROM rob_imputl2 WHERE actif=1 ORDER BY code";
		$affpro = $bdd->query($req);
		while ($optionpro = $affpro->fetch()) {
			if (substr($optionpro['code'],0,3) != 'ABS') {
				echo '<option value='.$optionpro['ID'].'>'.$optionpro['code'].' | '.$optionpro['description'].'</option>';
			}
		}
	$affpro->closeCursor();
	?>
</select>
