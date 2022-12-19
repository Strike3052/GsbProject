<?php

/**
 * Affichage du PDF
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
use Outils\Utilitaires;
use App\Entity\Pdf;

$idVisiteur = $_SESSION['idVisiteur'];
$lemois = filter_input(INPUT_POST, 'btnDll', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$numAnnee = substr($lemois, 0, 4);
$numMois = substr($lemois, 4, 2);
$infoVisiteur = $pdo->getNomPrenomVisiteur($idVisiteur, $lemois);
$fraisforfaitVehicule = $pdo->getFicheForfaitDetailsVehicule($idVisiteur, $lemois);
$fraisforfaitNuitee = $pdo->getFicheForfaitDetailsNuitee($idVisiteur, $lemois);
$fraisforfait = $pdo->getFicheForfaitDetails($idVisiteur, $lemois);
$horsforfait = $pdo->getLesFraisHorsForfaitDetails($idVisiteur, $lemois);

ob_clean();
$pdf = new Pdf();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 12);
$pdf->Cell(-180, 30, $idVisiteur, 0, 0, 'C');
$pdf->Cell(80, 30, 'Visiteur', 0, 0, 'C');
$pdf->Cell(120, 30, $infoVisiteur['prenom'] . ' ' . $infoVisiteur['nom'], 0, 0, 'C');
$pdf->Cell(-320, 60, 'Mois', 0, 0, 'C');
$pdf->SetX(10);
$pdf->Cell(0, 60, $numMois . '/' . $numAnnee, 0, 0, 'C');
$pdf->Ln(40);
$pdf->tableauFraisForfait(100, $fraisforfaitNuitee, $fraisforfaitVehicule, $fraisforfait);
$pdf->SetTextColor(30, 73, 125);
$pdf->SetFont('', 'B');
$pdf->tableau2(140, $horsforfait);
$pdf->totalPrix($numAnnee, $numMois);
$pdf->Output();

