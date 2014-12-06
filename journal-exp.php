<?php
session_start();
include("appel_db.php");

if (isset($_SESSION['mot_de_passe']) AND $_SESSION['mot_de_passe'] == $_SESSION['pass'])
{
	date_default_timezone_set('Europe/Paris');
	if (isset($_POST['datejourdeb']) AND $_POST['datejourfin'] AND $_POST['phaseID'])
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
									 ->setTitle("Export du journal")
									 ->setSubject("Export du journal")
									 ->setDescription("Extraction du journal de l'intranet sous Excel")
									 ->setKeywords("Journal web")
									 ->setCategory("Journal web");
		$startdate = $_POST['datejourdeb'];
		$enddate = $_POST['datejourfin'];
		$phaseID = $_POST['phaseID'];

		// Header
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Responsable')
					->setCellValue('B1', 'Pole')
					->setCellValue('C1', 'Date de commande')
					->setCellValue('D1', 'Client')
					->setCellValue('E1', 'Projet')
					->setCellValue('F1', 'Mission')
					->setCellValue('G1', 'Categorie')
					->setCellValue('H1', 'Affectation budgétaire')
					->setCellValue('I1', 'Analytique 1')
					->setCellValue('J1', 'Analytique 2')
					->setCellValue('K1', 'Type de prestation')
					->setCellValue('L1', 'Profil')
					->setCellValue('M1', 'Collaboratteur')
					->setCellValue('N1', 'Bénéficiaire')
					->setCellValue('O1', 'Compétition')
					->setCellValue('P1', 'Type de compétition')
					->setCellValue('Q1', 'Evénement')
					->setCellValue('R1', 'Lieu exécution')
					->setCellValue('S1', 'date exécution')
					->setCellValue('T1', 'Descriptif')
					->setCellValue('U1', 'Frs coût unitaire HT')
					->setCellValue('V1', 'Frs quantité')
					->setCellValue('W1', 'Frs coût total HT')
					->setCellValue('X1', 'Type de fournisseur')
					->setCellValue('Y1', 'Fournisseur')
					->setCellValue('Z1', 'Frs devis')
					->setCellValue('AA1', 'Frs BDC')
					->setCellValue('AB1', 'Frs date de facturation')
					->setCellValue('AC1', 'Frs numéro de facture')
					->setCellValue('AD1', 'Frs Paiement')
					->setCellValue('AE1', 'Clt Facture totale')
					->setCellValue('AF1', 'Clt Marge')
					->setCellValue('AG1', 'Clt devis')
					->setCellValue('AH1', 'Clt BDC')
					->setCellValue('AI1', 'Clt date de facturation')
					->setCellValue('AJ1', 'Clt numéro de facturation')
					->setCellValue('AK1', 'Clt paiement')
					->setCellValue('AL1', 'Prix budgété')
					->setCellValue('AM1', 'Quantité busgété')
					->setCellValue('AN1', 'Total budget')
					->setCellValue('AO1', 'Phase');

		$req = "SELECT T2.matricule, T3.desc1, T1.dateCommande, T4.description, T5.description, T6.description, T7.description, 
				T8.Description, T9.desc1, T10.desc1, T11.Description, T12.Description, T13.matricule, T1.beneficiaire, 
				T14.description, T15.description, T16.description, T1.compLieu, T1.compDate, T1.descriptif, 
				T1.frsCoutUnit, T1.frsQuantite, T1.frsCoutTotHT, T17.categorie, T18.Description, T1.frsDevis, T1.frsBDC, T1.frsDateFact, T1.frsNumFact, T1.frsPaiement, 
				T1.cltFactTot, T1.cltMarge, T1.cltDevis, T1.cltBDC, T1.cltDateFact, T1.cltNumFact, T1.cltPaiement, 
				T1.bPrix, T1.bQte, T1.bTot, T19.Description FROM rob_journal T1 
			INNER JOIN rob_user T2 ON T2.ID = T1.userID 
			INNER JOIN rob_pole T3 ON T3.ID = T1.poleID
			INNER JOIN rob_imputl1 T4 ON T4.ID = T1.imputID1 
			INNER JOIN rob_imputl2 T5 ON T5.ID = T1.imputID2 
			INNER JOIN rob_imputl3 T6 ON T6.ID = T1.imputID3 
			INNER JOIN rob_imputl4 T7 ON T7.ID = T1.imputID4 
			LEFT JOIN rob_affbud T8 ON T8.ID = T1.affectBudID 
			LEFT JOIN rob_ana1 T9 ON T9.ID = T1.ana1ID 
			LEFT JOIN rob_ana2 T10 ON T10.ID = T1.ana2ID 
			LEFT JOIN rob_presta T11 ON T11.ID = T1.typePrestaID 
			LEFT JOIN rob_profil T12 ON T12.ID = T1.profilID 
			LEFT JOIN rob_user T13 ON T13.ID = T1.collaborateurID 
			INNER JOIN rob_compl1 T14 ON T14.ID = T1.compID1 
			INNER JOIN rob_compl2 T15 ON T15.ID = T1.compID2 
			INNER JOIN rob_compl3 T16 ON T16.ID = T1.compID3 
			LEFT JOIN rob_catfrs T17 ON T17.ID = T1.frsTypeID 
			LEFT JOIN rob_fournisseur T18 ON T18.ID = T1.frsID 
			INNER JOIN rob_phase T19 ON T19.ID = T1.Phase 
			WHERE T1.dateCommande >= '$startdate' AND T1.dateCommande <= '$enddate' AND T1.Phase >= '$phaseID'
			ORDER BY T1.dateCommande";
		$reponsea = $bdd->query($req);
		$checkrep=$reponsea->rowCount();
		if ($checkrep != 0)
		{
			$row = 2;
			while ($donnee = $reponsea->fetch())
			{
				for ($i=0; $i < 41; $i++)
				{
					$col = '';
					if ($i == 0) { $col = 'A'; $datatmp = $donnee[$i]; }
					if ($i == 1) { $col = 'B'; $datatmp = $donnee[$i]; }
					if ($i == 2) { $col = 'C'; $datatmp = date ("d/m/Y", strtotime($donnee[$i])); }
					if ($i == 3) { $col = 'D'; $datatmp = $donnee[$i]; }
					if ($i == 4) { $col = 'E'; $datatmp = $donnee[$i]; }
					if ($i == 5) { $col = 'F'; $datatmp = $donnee[$i]; }
					if ($i == 6) { $col = 'G'; $datatmp = $donnee[$i]; }
					if ($i == 7) { $col = 'H'; $datatmp = $donnee[$i]; }
					if ($i == 8) { $col = 'I'; $datatmp = $donnee[$i]; }
					if ($i == 9) { $col = 'J'; $datatmp = $donnee[$i]; }
					if ($i == 10) { $col = 'K'; $datatmp = $donnee[$i]; }
					if ($i == 11) { $col = 'L'; $datatmp = $donnee[$i]; }
					if ($i == 12) { $col = 'M'; $datatmp = $donnee[$i]; }
					if ($i == 13) { $col = 'N'; $datatmp = $donnee[$i]; }
					if ($i == 14) { $col = 'O'; $datatmp = $donnee[$i]; }
					if ($i == 15) { $col = 'P'; $datatmp = $donnee[$i]; }
					if ($i == 16) { $col = 'Q'; $datatmp = $donnee[$i]; }
					if ($i == 17) { $col = 'R'; $datatmp = $donnee[$i]; }
					if ($i == 18) { $col = 'S'; $datatmp = date ("d/m/Y", strtotime($donnee[$i])); }
					if ($i == 19) { $col = 'T'; $datatmp = $donnee[$i]; }
					if ($i == 20) { $col = 'U'; $datatmp = $donnee[$i]; }
					if ($i == 21) { $col = 'V'; $datatmp = $donnee[$i]; }
					if ($i == 22) { $col = 'W'; $datatmp = $donnee[$i]; }
					if ($i == 23) { $col = 'X'; $datatmp = $donnee[$i]; }
					if ($i == 24) { $col = 'Y'; $datatmp = $donnee[$i]; }
					if ($i == 25) { $col = 'Z'; $datatmp = $donnee[$i]; }
					if ($i == 26) { $col = 'AA'; $datatmp = $donnee[$i]; }
					if ($i == 27) { $col = 'AB'; $datatmp = date ("d/m/Y", strtotime($donnee[$i])); }
					if ($i == 28) { $col = 'AC'; $datatmp = $donnee[$i]; }
					if ($i == 29) { $col = 'AD'; $datatmp = $donnee[$i]; }
					if ($i == 30) { $col = 'AE'; $datatmp = $donnee[$i]; }
					if ($i == 31) { $col = 'AF'; $datatmp = $donnee[$i]; }
					if ($i == 32) { $col = 'AG'; $datatmp = $donnee[$i]; }
					if ($i == 33) { $col = 'AH'; $datatmp = $donnee[$i]; }
					if ($i == 34) { $col = 'AI'; $datatmp = date ("d/m/Y", strtotime($donnee[$i])); }
					if ($i == 35) { $col = 'AJ'; $datatmp = $donnee[$i]; }
					if ($i == 36) { $col = 'AK'; $datatmp = $donnee[$i]; }
					if ($i == 37) { $col = 'AL'; $datatmp = $donnee[$i]; }
					if ($i == 38) { $col = 'AM'; $datatmp = $donnee[$i]; }
					if ($i == 39) { $col = 'AN'; $datatmp = $donnee[$i]; }
					if ($i == 40) { $col = 'AO'; $datatmp = $donnee[$i]; }
					
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
		$objPHPExcel->getActiveSheet()->setTitle('Export');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="ExportJournal'.$phaseID.'.xlsx"');
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