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
    <!-- Background Image Specific to each page -->
		<div class="background-journal background-image"></div>
		<div class="overlay"></div>

	<!-- <div id="navigationMap">
		<ul>
			<li><a class="typ" href="accueil.php">Home</a></li>
			<li><a class="typ" href="#"><span>Journal</span></a></li>
		</ul>
	</div> -->

	<ul id="navigationTable" class="list-inline">
 		<li><a class="typ" href="#" onclick="showListRep(1)"><span>Journal</span></a></li>
 		<li><a class="typ" href="journal.php" ><span>Nouveau</span></a></li>
 		<li><a class="typ" href="#" ><span>BDC</span></a></li>
 		<li><a class="typ" href="#" ><span>Devis</span></a></li>
 		<li><a class="typ" href="#" onclick="showListRep(2)"><span>Reporting</span></a></li>
	</ul>
	<div id="clearl"></div>

	<!-- =================== RESTITUTION: TABLEAU ================= -->
	<section class="container section-container" id="historique-journal">
		<div class="section-title">
			<h1>Historique de mon Journal</h1>
		</div>
		<?php
		include("listing-coeur.php");
		?>
	</section>
	
<?php
	include("footer.php");
}
else
{
	header("location:index.php");
}
?>