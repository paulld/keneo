<?php
//session_start();
include("appel_db.php");
?>

<!-- ================= VARIABLES =============== -->
<?php
$total=0;

// ================= REQUETE1 =============== 
$reqB = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_tabbord AS (SELECT 
	userID, datejour,
	CASE WHEN recup <> 0 THEN recup ELSE 0 END recuprest, 
	CASE WHEN TI2.code IN ('ABS_RTT') AND YEAR(datejour) = ".date("Y")." THEN valeur ELSE 0 END rttpris, 
	CASE WHEN 
		TI2.code IN ('ABS_CPA') 
		AND (("
			.date("m")." > 5 
			AND (YEAR(datejour) = ".date("Y")." AND MONTH(datejour) > 5 OR YEAR(datejour) = ".(date("Y")+1)." AND MONTH(datejour) < 6)
			) OR ("
			.date("m")." < 6 
			AND (YEAR(datejour) = ".date("Y")." AND MONTH(datejour) < 6 OR YEAR(datejour) = ".(date("Y")-1)." AND MONTH(datejour) > 5)
			))
	THEN valeur ELSE 0 END cppris
	FROM rob_temps TMP1
	INNER JOIN rob_imputl2 TI2 ON TMP1.imputIDl2 = TI2.ID
	WHERE TI2.code IN ('ABS_CPA','ABS_RTT') or recup <> 0)";
$bdd->query($reqB);
	
$req1 = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_tabbord2 AS (SELECT 
	userID userID, sum(recuprest) recuprest, sum(rttpris) rttpris, sum(cppris) cppris FROM tmp_tabbord
	GROUP BY userID)";
$bdd->query($req1);
	
$req2 = "DROP TEMPORARY TABLE tmp_tabbord";
$bdd->query($req2);
	
$req = "SELECT nom nom, prenom prenom, recuprest recuprest, rttpris rttpris, cppris cppris, cp cprest, rtt rttrest 
	FROM rob_user_abs T3 
	INNER JOIN rob_user T2 ON T2.ID = T3.ID
	LEFT JOIN tmp_tabbord2 T1 ON T3.ID = T1.userID
	INNER JOIN rob_user_rights T4 ON T4.ID = T3.ID
	WHERE extstd = 2
	ORDER BY nom, prenom";
$result = $bdd->query($req);

$req3 = "DROP TEMPORARY TABLE tmp_tabbord2";
$bdd->query($req3);
?>

<!-- ================= RESTITUTION =============== -->
<h2>R&eacute;cup&eacute;rations et cong&eacute;s</h2>
<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<td rowspan="2">Collaborateurs</td>
			<td align="right">R&eacute;cup&eacute;ration</td>
			<td align="center" colspan="2">RTT [01/01 > 31/12]</td>
			<td align="center" colspan="2">CP [01/06 > 31/05]</td>
		</tr>
		<tr>
			<td align="right">&agrave; prendre</td>
			<td align="right">pris</td>
			<td align="right">restants</td>
			<td align="right">pris</td>
			<td align="right">restants</td>
		</tr>
	</thead>
	<tbody>
		<?php
		
		while ($donnee = $result->fetch()) {
			?>
			<tr>
				<td><?php echo $donnee['nom'].' '.$donnee['prenom'];?></td>
				<td align="right"><?php echo $donnee['recuprest'];?></td>
				<td align="right"><?php echo $donnee['rttpris'];?></td>
				<td align="right"><?php echo $donnee['rttrest'];?></td>
				<td align="right"><?php echo $donnee['cppris'];?></td>
				<td align="right"><?php echo $donnee['cprest'];?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
</div>
<?php
$result->closeCursor();
?>
