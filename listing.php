<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	include("headerlight.php");

	if (isset($_POST['supprid']))
	{
		$idenr = $_POST['supprid'];
		$dateTransactmp = $_POST['moddateTransac'];
		if ($deadline < $dateTransactmp)
		{
			$bdd->query("DELETE FROM rob_journal WHERE ID='$idenr' LIMIT 1");
		}
		else
		{
			$deadreach = $deadreach + 1;
		}
	}
	?>
    <!-- =================== SAISIE ================= -->
	<div id="navigationMap">
		<ul><li><a class="typ" href="accueil.php">Home</a></li><li><a class="typ" href="#"><span>Journal</span></a></li></ul>
	</div>
	<div id="clearl"></div>
	<ul id="navigationTable">
 		<li><a class="typ" href="#" onclick="showListRep(1)"><span>Journal</span></a></li>
 		<li><a class="typ" href="journal.php" ><span>Nouveau</span></a></li>
 		<li><a class="typ" href="#" ><span>BDC</span></a></li>
 		<li><a class="typ" href="#" ><span>Devis</span></a></li>
 		<li><a class="typ" href="#" onclick="showListRep(2)"><span>Reporting</span></a></li>
	</ul>
	<div id="clearl"></div>

	<!-- =================== RESTITUTION: TABLEAU ================= -->
	<div id="coeur" name="coeur">
		<?php
		include("listing-coeur.php");
		?>

	</div>

	
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>