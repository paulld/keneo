<?php
session_start();
include("appel_db.php");
include('mobnavi.php');

?>
<!DOCTYPE html>
<html>

<?php
	include("headlight.php");
?>

<body>
	<?php
	if (isset($_POST['mail']))
	{
		// ENVOI MAIL
		$pass='';
		// Déclaration de l'adresse de destination.
		$mail=$_POST['mail'];
		// Récupération d'éventuelle donnée
		$req = "SELECT T2.password, T2.matricule FROM rob_user T2 INNER JOIN rob_user_info T1 ON T1.ID=T2.ID WHERE T1.mail='$mail' AND T2.actif=1";
		$reponse = $bdd->query($req);
		$repcheck = $reponse->rowCount();
		if ($repcheck != 0)
		{
			while ($donnee = $reponse->fetch())
			{
				$pass = $donnee['password'];
				$user = $donnee['matricule'];
			}
		}
		$reponse->closeCursor();
		// Ecriture du message
		if ($pass != '')
		{
			$message = 'Votre mot de passe pour l\'intranet Keneo est : '.$pass.'. Votre user est : '.$user;
		}
		else
		{
			$message = 'Vous n\'êtes pas reconnu(e) ou activé(e) sur l\'intranet Keneo';
		}
		// Paramètre retour à la ligne
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}
		$from_name = 'Administrator';
		$from_mail = $DBMAILBOX;
		$replyto = $_POST['mail'];
		$uid = md5(uniqid(time())); 
		$subject = 'PASSWD RECOVERY | INTRANET KENEO';

		$header = "From: ".$from_name." <".$from_mail.">".$passage_ligne;
		$header .= "Reply-To: ".$replyto."".$passage_ligne;
		$header .= "MIME-Version: 1.0".$passage_ligne;
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"".$passage_ligne.$passage_ligne;
		$header .= "This is a multi-part message in MIME format.".$passage_ligne;
		$header .= "--".$uid."".$passage_ligne;
		$header .= "Content-type:text/plain; charset=iso-8859-1".$passage_ligne;
		$header .= "Content-Transfer-Encoding: 7bit".$passage_ligne.$passage_ligne;
		$header .= $message."".$passage_ligne.$passage_ligne;
		$header .= "--".$uid."--";
		mail($mail, $subject, $message, $header);

		if (isset($_POST['mail']))
		{
			echo '<br/>Mail envoy&eacute; &agrave; '.$mail;
		}

		//Travaux
		$req = $bdd->query("SELECT actif FROM rob_param WHERE param='travaux' LIMIT 1") or die();
		$trav = $req->fetch();
		if ($trav['actif'] == 1)
		{
			include("maintenance.php");
		}
		else
		{
			include("coeur.php");
		}
		$trav->closeCursor();
	}
	else
	{
		?>

		<!-- Background Image Specific to each page -->
		<div class="background-login background-image"></div>
		<div class="overlay"></div>

		<!-- <nav class="nav-fixed-top" role="navigation">
		  <div class="container">
		    <div class="logo-container text-center">
		      |<br><img src="assets/images/keneo-logo.svg"><br>|
		    </div>
		  </div>
		</nav> -->

		<div class="container" id="coeur">
			<div class="row">
		    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
				  <div class="signin-outer">
						
						<h1>R&eacute;cup&eacute;rer son mot de passe:</h1>
						<div>
							<form class="form-recovery" action="passwd_recov class="form-signin".php" method="post">
								<input class="form-control" type="text" size="50" name="mail"  id="loginInputRecov" placeholder="Email" />
								<input class="btn btn-lg btn-primary btn-block" id="buttonval" type="submit" Value="Envoyer" name="Envoyer" />
							</form>
						</div>
						<div class="signin-recover-link">
							<a class="typ" href="index.php">
								<span>Retour</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php 
			}
			include("footer.php"); 
		?>