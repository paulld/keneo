<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	date_default_timezone_set('Europe/Paris');
	if (isset($_POST['datejourdeb']) AND $_POST['datejourfin'])
	{
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		/** Include PHPExcel */
		include_once('Classes/PHPExcel.php');


		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Barthe R. [Arimor C.]")
									 ->setLastModifiedBy("Barthe R. [Arimor C.]")
									 ->setTitle("Export des tickets restaurants")
									 ->setSubject("Export des tickets restaurants")
									 ->setDescription("Extraction des tickets restaurants de l'intranet sous Excel")
									 ->setKeywords("Tickets restaurant web")
									 ->setCategory("Tickets restaurant web");
		$startdate = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourdeb'],3,2),substr($_POST['datejourdeb'],0,2),substr($_POST['datejourdeb'],6,4)));
		$enddate = date('Y-m-d',mktime(0,0,0,substr($_POST['datejourfin'],3,2),substr($_POST['datejourfin'],0,2),substr($_POST['datejourfin'],6,4)));

		// Header
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Collaborateur')
					->setCellValue('B1', 'Nom')
					->setCellValue('C1', 'Prénom')
					->setCellValue('D1', 'Date')
					->setCellValue('E1', 'Remboursé');

		if (isset($_POST['remb']))
		{
			$req = "SELECT DISTINCT T2.matricule, T2.nom, T2.prenom, T1.datejour, T1.ticketValid FROM rob_temps T1 
				INNER JOIN rob_user T2 ON T2.ID = T1.userID
				WHERE T1.datejour >= '$startdate' AND T1.datejour < '$enddate' AND T1.ticket = 1
				ORDER BY T2.matricule, T1.datejour";
		} else {
			$req = "SELECT DISTINCT T2.matricule, T2.nom, T2.prenom, T1.datejour, T1.ticketValid FROM rob_temps T1 
				INNER JOIN rob_user T2 ON T2.ID = T1.userID
				WHERE T1.datejour >= '$startdate' AND T1.datejour < '$enddate' AND T1.ticket = 1 AND T1.ticketValid = 0
				ORDER BY T2.matricule, T1.datejour";
		}
		$reponsea = $bdd->query($req);
		$checkrep=$reponsea->rowCount();
		if ($checkrep != 0)
		{
			$row = 2;
			while ($donnee = $reponsea->fetch())
			{
				for ($i=0; $i < 7; $i++)
				{
					$col = '';
					if ($i == 0) { $col = 'A'; $datatmp = $donnee[$i]; }
					if ($i == 1) { $col = 'B'; $datatmp = $donnee[$i]; }
					if ($i == 2) { $col = 'C'; $datatmp = $donnee[$i]; }
					if ($i == 3) { $col = 'D'; $datatmp = date ("d/m/Y", strtotime($donnee[$i])); }
					if ($i == 4) { $col = 'E'; if ($donnee[$i] == 1) { $datatmp = 'oui'; } else { $datatmp = 'non'; } }
					
					// Add some data
					if ($col != '')
					{
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue($col.$row, utf8_encode($datatmp));
					}
				}
				$row = $row + 1;
			}
		}
		$reponsea->closeCursor();

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('TicketResto');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="ExportTicketResto.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
}
else
{
	include("index.php");
}