<?php
$DBHOST = "db489343072.db.1and1.com";$DBNAME = "db489343072";$DBUSER = "dbo489343072";$DBPASSWD = "R0ck&R0ll";$DBMAILBOX = "info@arimor-consulting.de";$DBREPTUTO = "TUTO";try{	$bdd = new PDO('mysql:host='.$DBHOST.';dbname='.$DBNAME, $DBUSER, $DBPASSWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));}catch (Exception $e){	die ('Erreur : '.$e->getMessage());}?>