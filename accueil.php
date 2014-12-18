<?php
session_start();
include("appel_db.php");

if (isset($_POST['mot_de_passe']) AND $_POST['mot_de_passe'] != "")
{
 	$pseudo = $_POST['matricule'];
	$pass = $_POST['mot_de_passe'];
	$testpass='';
	$req = "SELECT T1.ID, T1.nom, T1.prenom, T2.id_lev_menu, T2.id_hier, T2.id_pole, T2.id_lev_tms, T2.id_lev_exp, T2.resp_abs, T2.id_lev_jrl, T3.mail, T1.password
			FROM rob_user T1 
			INNER JOIN rob_user_rights T2 ON T1.ID = T2.ID
			INNER JOIN rob_user_info T3 ON T1.ID = T3.ID
			WHERE T1.matricule='$pseudo' and T1.password='$pass' and T1.actif=1";
 	$reponse = $bdd->query($req);
	$checkdata = $reponse->rowCount();
	if ($checkdata != 0)
	{
		//Enregistrement des variables de session
		while ($donnee = $reponse->fetch())
		{
			$_SESSION['ID'] = $donnee[0];
			$_SESSION['nom'] = $donnee[1];
			$_SESSION['prenom'] = $donnee[2];
			$_SESSION['id_lev_menu'] = $donnee[3];
			$_SESSION['id_hier'] = $donnee[4];
			$_SESSION['id_pole'] = $donnee[5];
			$_SESSION['id_lev_tms'] = $donnee[6];
			$_SESSION['id_lev_exp'] = $donnee[7];
			$_SESSION['resp_abs'] = $donnee[8];
			$_SESSION['id_lev_jrl'] = $donnee[9];
			$_SESSION['mail'] = $donnee[10];
			$_SESSION['pass'] = $donnee[11];
		}
		$_SESSION['mot_de_passe'] = $pass;
		$_SESSION['matricule'] = $pseudo;
	}
	$reponse->closeCursor();
}

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass']) 
{
	//Ecriture de la page
	include("headerlight.php");
	?>
	<!-- Background Image Specific to each page -->
	<div class="background-accueil background-image"></div>
	<div class="overlay"></div>

	<ul id="mainMenuLight">
		<?php
		$men= "SELECT * FROM rob_ssmenu WHERE actif=1 AND main != 0 ORDER BY main";
 		$menu = $bdd->query($men);
 		while ($donnee = $menu->fetch())
 		{
			echo '<li><a class="typ" href="'.$donnee['lien'].'"><span>'.$donnee['desc1'].'</span></a></li>';
		}
		$menu->closeCursor();
 		?>
	</ul>

	<footer id="footer" class="footer sticky-footer">
		<?php include("bas.php"); ?>
	</footer>
	</body>
</html>

<?php
}
else
{ 
	header("location:index.php");
}
?>