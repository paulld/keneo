<?php
session_start();
include("appel_db.php");

?>
<div class="col-sm-4">
		<select onchange="showNewCat(this.value)" class="form-control">
			<option value="0">Cat&eacute;gorie existante</option>
			<option value="1">Ajouter une cat&eacute;gorie</option>
		</select>
</div>
	
<div class="col-sm-6">
	<select name="newcomb4" class="form-control" >
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
</div>
<!-- <input id="w_input_90val" type="submit" Value="Ajouter" /> -->
