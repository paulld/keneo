<?phpdate_default_timezone_set('Europe/Paris');if (isset($_SESSION['pass'])){?>	<nav class="nav-fixed-top" role="navigation">	  <div class="container">			<div class="row">				<div class="col-xs-4 nav-left">					<div class="nav-left-top">			    	<?php						//Interface d'administration							$req = "SELECT T1.desc1, T1.code FROM rob_menus T1								INNER JOIN rob_menu_rights T2 ON T2.menu_id = T1.ID								WHERE T1.actif = 1									AND T2.ssmenu_id = 0									AND T2.menu = ".$_SESSION['id_lev_menu'];							$reponse = $bdd->query($req);							while ($donnee = $reponse->fetch() )							{								echo '<a href="'.$donnee[1].'.php" >';								if ($donnee[0] == "Administration") {echo '<i class="fa fa-key"></i>';}								if ($donnee[0] == "DB Management") {echo '<i class="fa fa-database"></i>';}								echo $donnee[0].'</a>';							}							$reponse->closeCursor();						?>					</div>					<div class="nav-left-bottom">						<a href="accueil.php">							<i class="fa fa-home"></i>Home						</a>					</div>		    </div>		    <div class="col-xs-4 nav-logo">		      |<br>		      <a href="accueil.php" alt="Home">			      <img src="assets/images/keneo-logo.png">			    </a>		      <br>|				</div>		    <div class="col-xs-4 nav-right">					<div class="nav-right-top">						<div class="nav-picture">							<a href="passwd.php" alt="Mon profil">								<?php									$filename = "assets/avatars/".$_SESSION['matricule'].".jpg";									if (file_exists($filename)) {										echo '<img src="'.$filename.'">';									} else {										echo '<img src="assets/images/profile-picture.jpg">';									}								?>							</a>						</div>						<div class="nav-buttons">							<a href="passwd.php" alt="Mon profil">								Mon Profil<i class="fa fa-cog"></i>							</a>							<a href="index.php" alt="Se déconnecter">								D&eacute;connexion<i class="fa fa-sign-out"></i>							</a>						</div>					</div>					<div class="nav-name">						<a href="passwd.php" alt="Mon profil">				    	<?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?>				    </a>					</div>		    </div>			</div>	  </div>	</nav><?php}?>