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
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {
    case 'selectionnerMois':
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        include PATH_VIEWS . 'v_listeMois.php';
        break;
    case 'voirEtatFrais':
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        $moisASelectionner = $leMois;
        include PATH_VIEWS . 'v_listeMois.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include PATH_VIEWS . 'v_etatFrais.php';
        break;
    case 'suiviPaiment':
        // Test de vérification que seulement un comptable est accès à cette page
        if (!$estComptable) {
            $_REQUEST['erreurs'] = ["La personne voulant accéder à cette page n'est pas un comptable"];
            include_once PATH_VIEWS . 'v_erreurs.php';
            break;
        }
        // Selection d'un visiteur
        if (filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $idDuVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lesMois = $pdo->getMoisFicheFrais($idDuVisiteur);
            $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (!inArrayArray($lesMois, $leMois)) {
                $leMois = $lesMois[0]['mois'];
            }
        }
        $lesVisiteurs = $pdo->getLesVisiteurs();

        $ucEtAction = "uc=etatFrais&action=suiviPaiment";
        include_once PATH_VIEWS . 'v_listeVisiteurs.php';

        // Selection du mois fait ?
        if (isset($leMois)) {
            // variable et contenu
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
            $etatFicheFrais = $pdo->getEtatFicheFrais($idDuVisiteur, $leMois);

            foreach ($lesFraisForfait as $unFrais) {
                if ($unFrais['idfrais'] == 'KM') {
                    $quantite = $unFrais['quantite'];
                    $prixUni = $pdo->getPrixUniKilometrique($idDuVisiteur, $leMois);
                    $Total = $quantite * $prixUni['montant'];
                } else {
                    $Total = $unFrais['quantite'] * $unFrais['prixuni'];
                }
                $TotalFraisForfait[$unFrais['idfrais']] = $Total;
            }

            // Affichage du contenu
            include_once PATH_VIEWS . 'v_suiviPaiementFrais.php';
            break;
        }
}

function inArrayArray(array $array, $isIn) {
    foreach ($array as $newarray) {
        if (in_array($isIn, $newarray)) {
            return true;
        }
    }
    return false;
}
