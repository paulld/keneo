<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");	if (isset($_POST['IDmodif']))	{		$id = $_POST['IDmodif'];		$temp_nom = $bdd->query("SELECT * FROM rob_nature1 WHERE ID='$id'");		while ($cur_nom = $temp_nom->fetch())		{			$modID = $cur_nom['ID'];			$code = $cur_nom['code'];			$desc = $cur_nom['Description'];		}		$temp_nom->closeCursor();	}	?>		    <!-- =================== SAISIE ================= -->	<div id="navigationMap">		<ul><li><a class="typ" href="accueil.php">Home</a></li>		<li><a class="typ" href="menu_setup.php"><span>DB Management</span></a></li>		<li><a class="typ" href="table.php"><span>Tables</span></a></li>		<li><a class="typ" href="nature1.php"><span>Nature 1</span></a></li>		<li><a class="typ" href="#"><span>Modification</span></a></li></ul>	</div>	<div id="clearl"></div>	<div id="haut">Modification</div>	<div id="coeur">		<form action="nature1.php" method="post">			<div class="small">Code (10 charact&egrave;res)* : <input type="hidden" value="<?php echo $modID;?>" name="modID" /><input id="w_input_75" type="text" size="12" value="<?php echo $code;?>" name="modcode" /></div>			<div class="small">Description  : <input id="w_input_75" type="text" size="50" value="<?php echo $desc;?>" name="moddesc" /></div>			<div class="small"><?php echo '<input id="w_input_90val" type="submit" Value="Enregistrer" name="Valider" />'; ?></div>		</form>		<div class="small">* Champ obligatoire</div>	</div>	<?php	include("footer.php");}else{	header("location:index.php");}?>