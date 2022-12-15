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
$ucEtAction = "uc=valideFrais&action=selectionnerMois";

// Si un visiteur ou un mois est sélectionner cela prend la valeur, sinon on récupère la valeur de session enregistré
$idDuVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ? filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : (isset($_SESSION['idDuVisiteur']) ? $_SESSION['idDuVisiteur'] : filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ? filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : (isset($_SESSION['leMois']) ? $_SESSION['leMois'] : filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

// Réactualisation de la valeur de session au cas ou.
$_SESSION['idDuVisiteur'] = $idDuVisiteur;
$_SESSION['leMois'] = $leMois;

// récupération de tous la liste de tous les visiteurs, et récupération des mois pour lesquel le visiteur a une fiche de frais
$lesVisiteurs = $pdo->getLesVisiteurs();
$lesMois = $pdo->getLesMois($idDuVisiteur);

// Re-afficher a chaque fois les deux listes déroulantes (selection visiteur et mois)
include PATH_VIEWS . 'v_listeVisiteurs.php';

// Si un mois est séléctionner, alors on peut récupérer toutes les informations sur la fiche de frais du visiteur concerné et aussi récupérer les informations envoyée par le formulaire.
if ($leMois)
{
    LoadInformations($pdo, $idDuVisiteur, $leMois);
    $idFraisCorrec = filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ? filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $moisCorrec = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_NUMBER_INT) ? filter_input(INPUT_POST, 'date', FILTER_SANITIZE_NUMBER_INT) : filter_input(INPUT_GET, 'date', FILTER_SANITIZE_NUMBER_INT);
    $libelleCorrec = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ? filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $montantCorrec = filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ? filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : filter_input(INPUT_GET, 'montant', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

switch ($action) {
    case 'majFrais':
        if ($leMois) {
            $lesFraisList = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);

            $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, $libelleCorrec, $montantCorrec);
            if (isset($lesFraisList)) {
                $pdo->majFraisForfait($idDuVisiteur, $leMois, $lesFraisList);
            }
        }
        break;
    case 'majRefuse':
        $newLibelle = strlen("REFUSE " . $libelleCorrec) > 255 ? substr("REFUSE " . $libelleCorrec, 0, 254) : "REFUSE " . $libelleCorrec;
        $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, $newLibelle, $montantCorrec);

        break;
    case 'majReport':
        $newMonth = date('Ym', strtotime("+1 month", strtotime($numAnnee . "-" . $numMois)));

        if ($pdo->estPremierFraisMois($idDuVisiteur, $newMonth)) {
            $pdo->creeNouvellesLignesFrais($idDuVisiteur, $newMonth);
        }
        $pdo->majFraisHorsForfaitReport($idFraisCorrec, $moisCorrec, $newMonth, $libelleCorrec, $montantCorrec);

        break;
    case 'ValideFiche':
        $pdo->majEtatFicheFrais($idDuVisiteur, $leMois, "VA");
        
        break;
}

if ($leMois) 
{
    $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
    include PATH_VIEWS . 'v_correction.php';
}


/*
 * Fonction permettant de récupérer toutes les informations d'une fiche de frais d'un visiteur avec l'id de celui-ci ainsi que le mois concerné
 */
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