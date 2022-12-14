<?php

/**
 * Gestion de l'affichage des frais
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$lesVisiteurs = $pdo->getLesVisiteurs();
$idDuVisiteur = $_SESSION['idDuVisiteur'] ? $_SESSION['idDuVisiteur'] : filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$lesMois = $pdo->getLesMois($idDuVisiteur);
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$ucEtAction = "uc=valideFrais&action=selectionnerMois";
include PATH_VIEWS . 'v_listeVisiteurs.php';

switch ($action) {
    case 'selectionnerMois':
        $_SESSION['idDuVisiteur'] = $idDuVisiteur;
        $_SESSION['leMois'] = $leMois;

        if ($leMois) {
            LoadInformations($pdo, $idDuVisiteur, $leMois);
            include PATH_VIEWS . 'v_correction.php';
        }
        break;
    case 'majFraisHorsForfait':
        $idDuVisiteur = $_SESSION['idDuVisiteur'];
        $leMois = $_SESSION['leMois'];

        if ($leMois) {
            LoadInformations($pdo, $idDuVisiteur, $leMois);

            $idFraisCorrec = filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $moisCorrec = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_NUMBER_INT);
            $libelleCorrec = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $montantCorrec = filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $lesFraisList = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);

            $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, $libelleCorrec, $montantCorrec);
            if (isset($lesFraisList)) {
                $pdo->majFraisForfait($idDuVisiteur, $leMois, $lesFraisList);
            }

            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
            include PATH_VIEWS . 'v_correction.php';
        }
        break;
    case 'majRefuse':
        $idDuVisiteur = $_SESSION['idDuVisiteur'];
        $leMois = $_SESSION['leMois'];

        LoadInformations($pdo, $idDuVisiteur, $leMois);

        $idFraisCorrec = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $moisCorrec = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_NUMBER_INT);
        $libelleCorrec = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montantCorrec = filter_input(INPUT_GET, 'montant', FILTER_DEFAULT);

        $newLibelle = strlen("REFUSE " . $libelleCorrec) > 255 ? substr("REFUSE " . $libelleCorrec, 0, 254) : "REFUSE " . $libelleCorrec;
        $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, $newLibelle, $montantCorrec);

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        include PATH_VIEWS . 'v_correction.php';

        break;
    case 'majReport':
        $idDuVisiteur = $_SESSION['idDuVisiteur'];
        $leMois = $_SESSION['leMois'];

        LoadInformations($pdo, $idDuVisiteur, $leMois);

        $idFraisCorrec = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $moisCorrec = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
        $libelleCorrec = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montantCorrec = filter_input(INPUT_GET, 'montant', FILTER_DEFAULT);

        $newMonth = date('Ym', strtotime("+1 month", strtotime($numAnnee . "-" . $numMois)));
        $estPremierFraisMois = $pdo->estPremierFraisMois($idDuVisiteur, $newMonth);

        if ($estPremierFraisMois == true) {
            $pdo->creeNouvellesLignesFrais($idDuVisiteur, $newMonth);
        }
        $pdo->majFraisHorsForfaitReport($idFraisCorrec, $moisCorrec, $newMonth, $libelleCorrec, $montantCorrec);

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        include PATH_VIEWS . 'v_correction.php';

        break;
    case 'ValideFiche':
        $idDuVisiteur = $_SESSION['idDuVisiteur'];
        $leMois = $_SESSION['leMois'];

        LoadInformations($pdo, $idDuVisiteur, $leMois);
        $pdo->majEtatFicheFrais($idDuVisiteur, $leMois, "VA");

        include PATH_VIEWS . 'v_correction.php';

        break;
}


function LoadInformations(Modeles\PdoGsb $pdo, string $idDuVisiteur, string $leMois):void {
    global $lesFraisHorsForfait, $lesFraisForfait, $lesInfosFicheFrais, $numAnnee, $numMois, $libEtat, $montantValide, $nbJustificatifs, $dateModif;
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idDuVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    if ($lesInfosFicheFrais) {
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

    }
}