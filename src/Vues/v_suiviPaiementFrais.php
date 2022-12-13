<h3>Etat actuel de la demande</h3>
<?php
echo "<p>La fiche de frais sélectionnée est '" . $etatFicheFrais ."'</p>";

?>
TO DO : Ajouter un bouton pour changer l'etat de la demande

<div>
    <h3>Frais Forfaitisés</h3>

    <table>
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
        <h3>Frais Hors Forfait</h3>
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
}else{
    echo "<h3>Aucun frais hors forfait signalé</h3>";
}