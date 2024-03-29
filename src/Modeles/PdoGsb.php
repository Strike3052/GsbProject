<?php

/**
 * Classe d'accès aux données.
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */
/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $connexion de type PDO
 * $instance qui contiendra l'unique instance de la classe
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   Release: 1.0
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

namespace Modeles;

use PDO;
use Outils\Utilitaires;

require '../config/bdd.php';

class PdoGsb {

    protected $connexion;
    private static $instance = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
        $this->connexion = new PDO(DB_DSN, DB_USER, DB_PWD);
        $this->connexion->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct() {
        $this->connexion = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb(): PdoGsb {
        if (self::$instance == null) {
            self::$instance = new PdoGsb();
        }
        return self::$instance;
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return l'id, le nom, le prénom et le mail sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login)
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom, visiteur.email as email '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin '
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);      
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne les informations d'un comptable
     *
     * @param String $login Login du comptable
     * @param String $mdp   Mot de passe du comptable
     *
     * @return l'id, le nom, le prénom et le mail sous la forme d'un tableau associatif
     */
    public function getInfosComptables($login)
    {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.id AS id, comptable.nom AS nom, '
            . 'comptable.prenom AS prenom, comptable.email as email '
            . 'FROM comptable '
            . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);      
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    public function getMdpVisiteur($login) {
        $requetePrepare = $this->connexion->prepare(
                'SELECT mdp '
                . 'FROM visiteur '
                . 'WHERE visiteur.login = :unlogin'
        );
        $requetePrepare->bindParam(':unlogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }

    public function getMdpComptable($login) {
        $requetePrepare = $this->connexion->prepare(
                'SELECT mdp '
                . 'FROM comptable '
                . 'WHERE comptable.login = :unlogin'
        );
        $requetePrepare->bindParam(':unlogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(PDO::FETCH_OBJ)->mdp;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT * FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = Utilitaires::dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois): int {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return l'id, le libelle et la quantité sous la forme d'un tableau
     * associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fraisforfait.id as idfrais, '
                . 'fraisforfait.libelle as libelle, '
                . 'lignefraisforfait.quantite as quantite, '
                . 'fraisforfait.montant as prixuni '
                . 'FROM lignefraisforfait '
                . 'INNER JOIN fraisforfait '
                . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais(): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fraisforfait.id as idfrais '
                . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais): void {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = $this->connexion->prepare(
                    'UPDATE lignefraisforfait '
                    . 'SET lignefraisforfait.quantite = :uneQte '
                    . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                    . 'AND lignefraisforfait.mois = :unMois '
                    . 'AND lignefraisforfait.idfraisforfait = :idFrais'
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Met à jour la ligne pour spécifier le type de véhicule utilisé lors de la 1er majFraisForfait
     * @param string $idVisiteur
     * @param string $mois
     * @param array $typeVehicule
     */
    public function majFraisKilometrique(string $idVisiteur, string $mois, array $typeVehicule) {
        $idFraisKilo = $this->deffIdTypeVehicule($typeVehicule);
        $requete = $this->connexion->prepare(
                'update ligneforfaitkilometrique'
                . ' set idfraiskilometrique = :idFraisKilo'
                . ' where idVisiteur = :idVisiteur'
                . ' and mois = :mois'
        );
        $requete->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR_CHAR);
        $requete->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);
        $requete->bindParam(':idFraisKilo', $idFraisKilo, PDO::PARAM_STR_CHAR);
        $requete->execute();
    }

    /**
     * Retourne l'id exacte d'un type de vehicule de part son nombre de chevaux et son type de Carburant
     * @param array $typeVehicule
     * @return string
     */
    public function deffIdTypeVehicule(array $typeVehicule) {
        $lesCles = array_keys($typeVehicule);
        if ($typeVehicule[$lesCles[1]] < 5) {
            $id = substr($typeVehicule[$lesCles[0]], 0, 3) . '4M';
        } else {
            $id = substr($typeVehicule[$lesCles[0]], 0, 3) . '5P';
        }
        return $id;
    }

    /**
     * Retourne le prix du forfait kilometrique d'un type de vehicule
     * @param array $typeVehicule
     * @return decimal
     */
    public function getPrixUniKilometrique($idVisiteur, $mois) {
        $idTypeVehicule = $this->getIdTypeVehicule($idVisiteur, $mois);
        if ($idTypeVehicule == null){
        return ['montant' => 0.62];
        }else{
          $requete = $this->connexion->prepare(
                'select montant from fraiskilometrique '
                . 'where id = :id'
        );
        $requete->bindParam('id', $idTypeVehicule['idFraisKilometrique'], PDO::PARAM_STR);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_ASSOC);  
        }  
    }

    /**
     * Retourne l'id du vehicule de la personne
     * @param type $idVisiteur
     * @param type $mois
     * @return type
     */
    public function getIdTypeVehicule($idVisiteur, $mois) {
        $requete = $this->connexion->prepare(
                'Select idFraisKilometrique from ligneforfaitkilometrique where '
                . 'idVisiteur = :idVisiteur and mois = :mois'
        );
        $requete->bindParam('idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requete->bindParam('mois', $mois, PDO::PARAM_STR);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour la table ligneFraisHorsForfait
     * Met à jour la table ligneFraisHorsForfait pour un frais hors forfait et
     * un mois donné en enregistrant les nouveaux paramètres
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisHorsForfait($idFrais, $mois, $libelle, $montant): void {
        $requetePrepare = $this->connexion->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.date = :mois, '
                . 'lignefraishorsforfait.libelle = :libelle, lignefraishorsforfait.montant = :montant '
                . 'WHERE lignefraishorsforfait.id = :idFrais '
        );
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':montant', $montant, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    
    /**
     * Met à jour la table ligneFraisHorsForfait
     * Met à jour la table ligneFraisHorsForfait pour un frais hors forfait et
     * un mois donné en reportant d'un mois le fraisHorsForfait
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisHorsForfaitReport($idFrais, $date,$mois, $libelle, $montant): void
    {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE lignefraishorsforfait '
            . 'SET lignefraishorsforfait.date = :date, '
            . 'lignefraishorsforfait.libelle = :libelle, lignefraishorsforfait.montant = :montant, lignefraishorsforfait.mois=:mois '
            . 'WHERE lignefraishorsforfait.id = :idFrais '
        );
        $requetePrepare->bindParam(':date', $date, PDO::PARAM_STR);
        $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':libelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':montant', $montant, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
 

    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs): void {
        $requetePrepare = $this->connexion->prepare(
                'UPDATE fichefrais '
                . 'SET nbjustificatifs = :unNbJustificatifs '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
                ':unNbJustificatifs',
                $nbJustificatifs,
                PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois): bool {
        $boolReturn = false;
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.mois FROM fichefrais '
                . 'WHERE fichefrais.mois = :unMois '
                . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur): string {
        $requetePrepare = $this->connexion->prepare(
                'SELECT MAX(mois) as dernierMois '
                . 'FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois): void {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        // Ajout de la fiche de frais avec toutes les valeurs mise à 0
        $requetePrepare = $this->connexion->prepare(
                'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
                . 'montantvalide,datemodif,idetat) '
                . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            // ajout d'une ligne mise à 0 pour chaque frais de la table fraisforfait
            $requetePrepare = $this->connexion->prepare(
                    'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                    . 'idfraisforfait,quantite) '
                    . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais['idfrais'], PDO::PARAM_STR);
            $requetePrepare->execute();
        }
        // Ajout d'une ligne avec aucun fraiskilometrique spécifier
        $requete = $this->connexion->prepare(
                'insert into ligneforfaitkilometrique (idVisiteur,mois,idfraiskilometrique) values'
                . ' (:idVisiteur, :mois, null)'
        );
        $requete->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR_CHAR);
        $requete->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);
        $requete->execute();
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant): void {
        $dateFr = Utilitaires::dateFrancaisVersAnglais($date);
        $requetePrepare = $this->connexion->prepare(
                'INSERT INTO lignefraishorsforfait '
                . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
                . ':unMontant) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais): void {
        $requetePrepare = $this->connexion->prepare(
                'DELETE FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.mois AS mois FROM fichefrais '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesVisiteurs(): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT id, nom, prenom FROM visiteur;'
        );
        $requetePrepare->execute();

        return $requetePrepare->fetchAll();
    }

    public function getLesMois($idVisiteur): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT idvisiteur, mois FROM fichefrais where idvisiteur = :unIdVisiteur;'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();

        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois): array|bool {
        $requetePrepare = $this->connexion->prepare(
                'SELECT fichefrais.idetat as idEtat, '
                . 'fichefrais.datemodif as dateModif,'
                . 'fichefrais.nbjustificatifs as nbJustificatifs, '
                . 'fichefrais.montantvalide as montantValide, '
                . 'etat.libelle as libEtat '
                . 'FROM fichefrais '
                . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat): void {
        $requetePrepare = $this->connexion->prepare(
                'UPDATE ficheFrais '
                . 'SET idetat = :unEtat, datemodif = now() '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    public function getMoisFicheFrais($idVisiteur){
        $requete = $this->connexion->prepare(
                "Select mois from fichefrais "
                . "where (idetat = 'CR' OR idetat = 'VA') "
                . "and idvisiteur = :idVisiteur "
        );
        $requete->bindParam('idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEtatFicheFrais($idVisiteur, $unmois){
        $requete = $this->connexion->prepare(
                'Select libelle from etat where id = '
                . '(Select idetat from fichefrais '
                . 'where idvisiteur = :idVisiteur and mois = :unmois)'
        );
        $requete->bindParam('idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requete->bindParam('unmois', $unmois, PDO::PARAM_STR);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_NUM)[0];
    }

    public function modifEtatFicheFrais($idVisiteur, $mois, $idEtat): void{
        $requete = $this->connexion->prepare(
                'Update fichefrais set idetat = :idetat '
                . 'where idvisiteur = :idvisiteur and mois = :mois'
        );
        $requete->bindParam('idvisiteur', $idVisiteur, PDO::PARAM_STR);
        $requete->bindParam('mois', $mois, PDO::PARAM_STR);
        $requete->bindParam('idetat', $idEtat, PDO::PARAM_STR);
        $requete->execute();
    }
    
    public function getNomPrenomVisiteur($id) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom '
            . 'FROM visiteur '
            . 'WHERE visiteur.id = :unId '
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);      
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    
    public function getFicheForfaitDetailsVehicule($id, $date) {
        $requetePrepare = $this->connexion->prepare(
            'select fraisforfait.libelle, lignefraisforfait.quantite, fraisforfait.montant, (lignefraisforfait.quantite * fraisforfait.montant) as total from fraisforfait inner join lignefraisforfait on fraisforfait.id = lignefraisforfait.idfraisforfait where lignefraisforfait.mois = :uneDate and lignefraisforfait.idvisiteur=:unId group by fraisforfait.libelle, lignefraisforfait.quantite, fraisforfait.montant, total LIMIT 4 OFFSET 1;'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);  
        $requetePrepare->bindParam(':uneDate', $date, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    public function getFicheForfaitDetailsNuitee($id, $date) {
        $requetePrepare = $this->connexion->prepare(
            'select fraisforfait.libelle, lignefraisforfait.quantite, fraisforfait.montant, (lignefraisforfait.quantite * fraisforfait.montant) as total from fraisforfait inner join lignefraisforfait on fraisforfait.id = lignefraisforfait.idfraisforfait where lignefraisforfait.mois = :uneDate and lignefraisforfait.idvisiteur=:unId group by fraisforfait.libelle, lignefraisforfait.quantite, fraisforfait.montant, total LIMIT 4 OFFSET 2;'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);  
        $requetePrepare->bindParam(':uneDate', $date, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    public function getFicheForfaitDetails($id, $date) {
        $requetePrepare = $this->connexion->prepare(
            'select fraisforfait.libelle, lignefraisforfait.quantite, fraisforfait.montant, (lignefraisforfait.quantite * fraisforfait.montant) as total from fraisforfait inner join lignefraisforfait on fraisforfait.id = lignefraisforfait.idfraisforfait where lignefraisforfait.mois = :uneDate and lignefraisforfait.idvisiteur=:unId group by fraisforfait.libelle, lignefraisforfait.quantite, fraisforfait.montant, total LIMIT 4 OFFSET 3;'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);  
        $requetePrepare->bindParam(':uneDate', $date, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    public function getLesFraisHorsForfaitDetails($idVisiteur, $mois): array {
        $requetePrepare = $this->connexion->prepare(
                'SELECT date, libelle, montant FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = Utilitaires::dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }
    
    public function setCodeA2f($id, $code) {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE visiteur '
            . 'SET codea2f = :unCode '
            . 'WHERE visiteur.id = :unIdVisiteur '
        );
        $requetePrepare->bindParam(':unCode', $code, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    public function getCodeVisiteur($id) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT visiteur.codea2f AS codea2f '
            . 'FROM visiteur '
            . 'WHERE visiteur.id = :unId'
        );
        $requetePrepare->bindParam(':unId', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch()['codea2f'];
    }
    
    public function setCodeA2fComptable($id, $code) {
        $requetePrepare = $this->connexion->prepare(
            'UPDATE comptable '
            . 'SET codea2f = :unCode '
            . 'WHERE comptable.id = :unIdComptable '
        );
        $requetePrepare->bindParam(':unCode', $code, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdComptable', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    public function getCodeComptable($id) {
        $requetePrepare = $this->connexion->prepare(
            'SELECT comptable.codea2f AS codea2f '
            . 'FROM comptable '
            . 'WHERE comptable.id = :unIdComptable'
        );
        $requetePrepare->bindParam(':unIdComptable', $id, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch()['codea2f'];
    }
}
