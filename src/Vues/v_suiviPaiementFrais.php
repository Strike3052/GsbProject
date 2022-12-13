<div>
    <h3 class="titregras">Etat actuel de la demande</h3>
    <?php
    echo "<p>La fiche de frais sélectionnée est <strong>'" . $etatFicheFrais . "'</strong></p>";

    switch ($etatFicheFrais) {
        case 'Fiche créée, saisie en cours':
            $message = 'Valider et mettre en paiement';
            $nextStep = 'VA';
            break;
        case 'Validée et mise en paiement' :
            $message = 'Rembourser les frais';
            $nextStep = 'RB';
            break;
    }
    ?>
    <div class="Btn-modifetat">
        <label>Changer l'état de la fiche :</label>
        <button form="lstVisiteurs" class="btn btn-success" type="submit" name="nextStep" value="<?php echo $nextStep ?>">
            <?php echo $message ?>
        </button>
    </div>
</div>

<div>
    <h3 class="titregras">Frais Forfaitisés</h3>

    <table>
        <thead>
            <tr>
                <th>Forfait</th>
                <th>Montant total</th>
            </tr>
        </thead>
        <?php
        foreach ($lesFraisForfait as $unFrais) {
            ?>
            <tr>
                <td><?php echo $unFrais['libelle'] ?></td>
                <td><?php echo $TotalFraisForfait[$unFrais['idfrais']] . ' €' ?></td>        
            </tr>
            <?php
        }
        ?>
    </table>
</div>

<?php
if (!empty($lesFraisHorsForfait)) {
    ?>
    <div>
        <h3 class="titregras">Frais Hors Forfait</h3>
        <table>
            <thead>
                <tr>
                    <th>Libelle</th>
                    <th>Date</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                ?>
                <tr>
                    <td><?php echo $unFraisHorsForfait['libelle'] ?></td>
                    <td><?php echo $unFraisHorsForfait['date'] ?></td>
                    <td><?php echo $unFraisHorsForfait['montant'] . ' €' ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
} else {
    echo "<h3>Aucun frais hors forfait signalé</h3>";
}