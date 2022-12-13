<?php
/**
 * Vue Liste des mois
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
?>

<h2><?php
    if ($action == 'suiviPaiment') {
        echo 'Suivi fiche de frais';
    } else {
        echo 'Valider fiche de frais';
    }
    ?></h2>

<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner un visiteur : </h3>
    </div>
    <div class="col-md-4">
        <?php
        if (isset($idDuVisiteur)) {
            ?>
            <form id="lstVisiteurs" action="index.php?<?php echo $ucEtAction; ?>" method="post" role="form">
                <?php
            } else {
                ?>
                <form action="index.php?<?php echo $ucEtAction; ?>" method="post" role="form">
                    <?php
                }
                ?>
                <div class="form-group">
                    <label for="idVisiteur" accesskey="n">Visiteurs : </label>
                    <select id="idVisiteur" name="idVisiteur" class="form-control" onchange="this.form.submit()">
                        <option selected value="choisir">
                            Choisir... </option>
                        <?php
                        foreach ($lesVisiteurs as $unVisiteur) {
                            $nom = $unVisiteur['nom'];
                            $prenom = $unVisiteur['prenom'];
                            $id = $unVisiteur['id'];

                            if ($id == $idDuVisiteur) {
                                ?>
                                <option selected value="<?php echo $id ?>">
                                    <?php echo $nom . ' ' . $prenom ?> </option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $id ?>">
                                    <?php echo $nom . ' ' . $prenom ?> </option>
                                <?php
                            }
                        }
                        ?>    
                    </select>

                    <label for="lstMois" accesskey="n">Mois : </label>
                    <select id="lstMois" name="lstMois" class="form-control" onchange="this.form.submit()">
                        <?php
                        foreach ($lesMois as $unMois) {
                            $mois = $unMois['mois'];
                            if ($mois == $leMois) {
                                ?>
                                <option selected value="<?php echo $mois ?>">
                                    <?php echo $mois ?> </option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $mois ?>">
                                    <?php echo $mois ?> </option>
                                <?php
                            }
                        }
                        ?>     

                    </select>
                </div>

            </form>
    </div>
</div>
