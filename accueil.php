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
	
	<div id="mainMenuLight" class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
				<div class="row">
					<?php
					$men= "SELECT * FROM rob_ssmenu WHERE actif=1 AND main != 0 AND main != 5 ORDER BY main";
			 		$menu = $bdd->query($men);
			 		while ($donnee = $menu->fetch())
			 		{
						echo '<div class="col-xs-6">';
						echo '<div class="menu-item-outer">';
						echo '<a href="'.$donnee['lien'].'"><span class="menu-link"></span></a>';
						if ($donnee['desc1'] == "Temps") {echo '<i class="fa fa-history"></i><p>Mes Temps</p>';}
						else if ($donnee['desc1'] == "Frais") {echo '<i class="fa fa-credit-card"></i><p>Mes Frais</p>';}
						else if ($donnee['desc1'] == "Journal") {echo '<i class="fa fa-file-text"></i><p>Mon Journal</p>';}
						else if ($donnee['desc1'] == "Team") {echo '<i class="fa fa-users"></i><p>L\'Équipe</p>';}
						else if ($donnee['desc1'] == "Param") {echo '<i class="fa fa-cog"></i><p>Mon Profil</p>';}
						else echo '<p class="menu-no-icon">'.$donnee['desc1'].'</p>';
						echo '</div>';
						echo '</div>';
					}
					$menu->closeCursor();
			 		?>
		 		</div>
	 		</div>
 		</div>
	</div>

<?php include("footer.php"); 
}
else
{ 
	header("location:index.php");
}
?>