<?php
session_start();
include("appel_db.php");
include('mobnavi.php');

?>
<html xmlns="http://www.w3.org/1999/xhtml">

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
			echo '<br/><img src="images/PNGADM/trav.png" title="Under maintenance"/>';
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

		<div id="navigationMap">
			<ul><li><a class="typ" href="index.php"><span>Retour</span></a></li></ul>
		</div>
		<div id="clearl"></div>
		
		<div id="haut">Keneo Internal Reporting - Password recovery</div>
		<div id="coeur">
				<form action="passwd_recov.php" method="post">
					Mail:<input type="text" size="50" name="mail"  id="loginInputRecov"/><input id="buttonval" type="submit" Value="Envoyer" name="Envoyer" />
				</form>
		</div>

	<?php
	}
	include("bas.php");
	?>
	</body>
</html>
