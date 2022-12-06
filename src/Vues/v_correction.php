<?php

/**
 * Vue Liste des frais au forfait
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
<div class="row">    
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=valideFrais&action=majFraisHorsForfait" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>

<div class="panel panel-info-comptable">
    <div class="panel-heading">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <table class="table table-bordered-comptable table-responsive">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>            
            <th class='corriger'></th> 
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = implode('-', array_reverse(explode('/',$unFraisHorsForfait['date'])));
            
            if (isset($libelleCorrec) && $unFraisHorsForfait['id'] == $idFraisCorrec && $unFraisHorsForfait['libelle'] != $libelleCorrec) {
                $libelle = $libelleCorrec;
            } else {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            }
            if (isset($idFraisCorrec) && $unFraisHorsForfait['id'] == $idFraisCorrec && $unFraisHorsForfait['montant'] != $montantCorrec) {
                $montant = $montantCorrec;
            } else {
                $montant = $unFraisHorsForfait['montant']; 
            }
            $idFrais = $unFraisHorsForfait['id'];
            ?>
            
        <form method="post" 
              action="index.php?uc=valideFrais&action=majFraisHorsForfait" 
              role="form">
            <fieldset>   
            <tr>
                <td style='display:none;'><input type="text" id='idFrais' name='idFrais' value="<?php echo $idFrais ?>"></input></td>
                <td><input type="date" id="date" name="date" value="<?php echo $date ?>"></input></td>
                <td><input type="text" id="libelle" name="libelle" value="<?php echo $libelle ?>"></input></td>
                <td><input type="text" id="montant" name="montant" value="<?php echo $montant ?>"></input></td>
                <td>
                    <button class="btn btn-success" type="submit">Corriger</button>
                    <button class="btn btn-danger" type="reset">Réinitialiser</button>
                </td>
            </tr>
            </fieldset>
        </form>
            <?php
        }
        ?>
    </table>
</div>