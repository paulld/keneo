<?php
session_start();
include("appel_db.php");

	?>

	<div class="col-sm-3">
		<select class="form-control" onchange="showNewMis(this.value)">
			<option value="0">Mission existante</option>
			<option value="1">Ajouter une mission</option>
		</select>
	</div>
	
	<div class="col-sm-7">
		<select class="form-control" name="newcomb3" >
			<option value=0></option>
			<?php
				$req = "SELECT * FROM rob_imputl3 WHERE actif=1 ORDER BY code";
				$affmis = $bdd->query($req);
				while ($optionmis = $affmis->fetch())
				{
					echo '<option value='.$optionmis['ID'].'>'.$optionmis['code'].' | '.$optionmis['description'].'</option>';
				}
				$affmis->closeCursor();
			?>
		</select>
	</div>
	<input class="btn btn-primary" type="submit" Value="Ajouter" />
