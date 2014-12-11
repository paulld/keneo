<?php
session_start();
include("appel_db.php");

// DATABASE: Clean data before use
function clean($value)
{
	return mysql_real_escape_string($value);
}

// FORM: Variables were posted
if (count($_POST))
{
	// Prepare form variables for database
	foreach($_POST as $column => $value)
	${$column} = $value;
	// Perform MySQL UPDATE
	$result = "UPDATE rob_temps SET datevalid='".date("Y-m-d")."', userValidID='".$_SESSION['ID']."', ".$col."='".$val."' WHERE ID='".$w_val."'";
	$bdd->query($result);
}
?>