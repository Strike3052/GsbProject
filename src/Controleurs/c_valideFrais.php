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
    case 'selectionnerVisiteur':
        $lesVisiteurs = $pdo->getLesVisiteurs();

        include PATH_VIEWS . 'v_listeVisiteurs.php';
        break;
     case 'selectionnerMois':
         $lesVisiteurs = $pdo->getLesVisiteurs();
         $idDuVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $lesMois = $pdo->getLesMois($idDuVisiteur);
         $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        $_SESSION['idDuVisiteur'] = $idDuVisiteur;
        $_SESSION['leMois'] = $leMois;
        
        if ($leMois){
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idDuVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include PATH_VIEWS . 'v_correction.php';
        //include PATH_VIEWS . 'v_etatFrais.php';
        
        }
        break;
    case 'majFraisHorsForfait':
         $lesVisiteurs = $pdo->getLesVisiteurs();
         $idDuVisiteur = $_SESSION['idDuVisiteur'];
         $lesMois = $pdo->getLesMois($idDuVisiteur);
         $leMois = $_SESSION['leMois'];
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        
        
        if ($leMois){
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idDuVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        
        //include PATH_VIEWS . 'v_etatFrais.php';
        
        $idFraisCorrec = filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $moisCorrec = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $libelleCorrec = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montantCorrec = filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        include PATH_VIEWS . 'v_correction.php';
        
        echo $idFraisCorrec . $moisCorrec . $libelleCorrec . $montantCorrec;
        
        $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, $libelleCorrec, $montantCorrec);
        
        }
        break;

}