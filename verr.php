<?phpsession_start();include("appel_db.php");if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']){	include("headerlight.php");		//Mise à jour Infos	$majinfo ='';	if (isset($_POST['theme']))	{		$theme = $_POST['theme'];		$deadline = date('Y-m-d',mktime(0,0,0,substr($_POST['deadline'],3,2),substr($_POST['deadline'],0,2),substr($_POST['deadline'],6,4)));		//Actualisation deadline		$req = "UPDATE rob_verrouille SET 			deadline = '".$deadline."' 			WHERE ID = '".$theme."'";		$bdd->query($req);		//Verrouillage temps		$req = "UPDATE rob_temps SET 			validation = '1' 			WHERE datejour <= '".$deadline."' AND validation = '0'";		$bdd->query($req);		//Deverrouillage temps		$req = "UPDATE rob_temps SET 			validation = '0' 			WHERE datejour > '".$deadline."' AND validation = '1'";		$bdd->query($req);		$majinfo =' - <strong>maj!</strong>';	}	// ====================== MISE A JOUR ======================	?>		<div id="navigationMap">		<ul><li><a class="typ" href="accueil.php">Home</a></li>		<li><a class="typ" href="menu_adm.php">Administration</a></li>		<li><a class="typ" href="#"><span>Deadlines</span></a></li></ul>	</div>	<div id="clearl"></div>	<div id="haut">Deadlines</div>		<div id="coeur">	V&eacute;rrouiller les donn&eacute;es ant&eacute;rieures aux dates d&eacute;finies ci-dessous:		<table id="tablerestit" class="table">			<tr>				<td id="t-containertit">Th&egrave;me</td>				<td id="t-containertit">Jusqu'au (inclu)</td>			</tr>			<?php			$req = "SELECT * FROM rob_verrouille";			$reponse = $bdd->query($req );			$checkrep = $reponse->rowCount();			$i=1;			while ($donnee = $reponse->fetch() )			{			?>				<form action="verr.php" method="post">				<tr>					<td id="t-container<?php echo $i;?>"><input type="hidden" name="theme" value="<?php echo $donnee['ID'];?>" /><?php echo $donnee['theme'];?></td>					<td id="t-container<?php echo $i;?>"><input type="text" size="15" id="deadline<?php echo $i;?>" name="deadline" value="<?php echo date("d/m/Y",strtotime($donnee['deadline']));?>" />					<input id="w_input_90val" type="submit" Value="Mettre &agrave; jour" /><?php echo $majinfo;?></td>				</tr>				</form>			<?php				$i=$i+1;			}			$reponse->closeCursor();		?>		</table>	</div>	<?php	include("footer.php");}else{	header("location:index.php");}?>