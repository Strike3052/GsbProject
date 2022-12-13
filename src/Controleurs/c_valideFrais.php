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
        if ($lesInfosFicheFrais) {
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        
            $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
            include PATH_VIEWS . 'v_correction.php';
            //include PATH_VIEWS . 'v_etatFrais.php';
        }
        }
        break;
    case 'majFraisHorsForfait':
        $idTest = filter_input(INPUT_GET, 'idTest', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
        $moisCorrec = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_NUMBER_INT);
        $libelleCorrec = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montantCorrec = filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $lesFraisList = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        
         
        
        echo $idFraisCorrec . $moisCorrec . $libelleCorrec . $montantCorrec;
        
        $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, $libelleCorrec, $montantCorrec);
        if (isset($lesFraisList)) {
        $pdo->majFraisForfait($idDuVisiteur, $leMois, $lesFraisList);
        }
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
        include PATH_VIEWS . 'v_correction.php';
        ?>
        
        <?php
        }
        break;
    case 'majRefuse':
        $idTest = filter_input(INPUT_GET, 'idTest', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $lesVisiteurs = $pdo->getLesVisiteurs();
         $idDuVisiteur = $_SESSION['idDuVisiteur'];
         $lesMois = $pdo->getLesMois($idDuVisiteur);
         $leMois = $_SESSION['leMois'];
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        
    
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idDuVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        
        $idFraisCorrec = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $moisCorrec = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_NUMBER_INT);
        $libelleCorrec = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montantCorrec = filter_input(INPUT_GET, 'montant', FILTER_DEFAULT);
        
        $pdo->majFraisHorsForfait($idFraisCorrec, $moisCorrec, "REFUSE ".$libelleCorrec, $montantCorrec);
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        include PATH_VIEWS . 'v_correction.php';
        
        break;
    case 'majReport':
        $idTest = filter_input(INPUT_GET, 'idTest', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $lesVisiteurs = $pdo->getLesVisiteurs();
         $idDuVisiteur = $_SESSION['idDuVisiteur'];
         $lesMois = $pdo->getLesMois($idDuVisiteur);
         $leMois = $_SESSION['leMois'];
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        
    
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idDuVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idDuVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        
        $idFraisCorrec = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $moisCorrec = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_NUMBER_INT);
        $libelleCorrec = filter_input(INPUT_GET, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montantCorrec = filter_input(INPUT_GET, 'montant', FILTER_DEFAULT);
      
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idDuVisiteur, $leMois);
        include PATH_VIEWS . 'v_correction.php';
        
        break;
}