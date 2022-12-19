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
$lemois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$numAnnee = substr($lemois, 0, 4);
$numMois = substr($lemois, 4, 2);
$infoVisiteur = $pdo->getNomPrenomVisiteur($idVisiteur, $lemois);
$fraisforfaitVehicule = $pdo->getFicheForfaitDetailsVehicule($idVisiteur, $lemois);
$fraisforfaitNuitee = $pdo->getFicheForfaitDetailsNuitee($idVisiteur, $lemois);
$fraisforfait = $pdo->getFicheForfaitDetails($idVisiteur, $lemois);
$horsforfait = $pdo->getLesFraisHorsForfaitDetails($idVisiteur, $lemois);

include'../src/Entity/Pdf.php';

