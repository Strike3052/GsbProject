<?php

/**
 * Description of Pdf
 *
 * @author Aymé
 */
namespace App\Entity;

use FPDF\FPDF;

class Pdf extends FPDF {

    public $total;

    public function __construct(){
        parent::__construct();
    }
    
    public function Header() {
        // Logo
        $this->Image('../public/images/logo.jpg', 90, 20, 30);
        //saut de lignes
        $this->Ln(50);
        //police
        $this->SetFont('Arial', 'B', 13.5);
        $this->SetTextColor(31, 73, 125);
        // Décalage à droite
        $this->Cell(5);
        //titre
        $this->Cell(180, 8, 'REMBOURSEMENT DE FRAIS ENGAGES', 1, 0, 'C');
    }

    public function tableauFraisForfait($position_ligne1, $fraisforfaitNuitee, $fraisforfaitVehicule, $fraisforfait) {
        $this->SetFont('Times', 'I', 12);
        $this->SetDrawColor(31, 73, 125); // Couleur du fond RVB
        $this->SetFillColor(255); // Couleur des filets RVB
        $this->SetTextColor(31, 73, 125); // Couleur du texte 
        $this->SetY($position_ligne1);
        $this->Cell(50, 8, 'frais forfaitaires', 1, 0, 'C', 1);  // 60 >largeur colonne, 8 >hauteur colonne
        // position de la colonne 2 (70 = 10+60)
        $this->SetX(60);
        $this->Cell(50, 8, utf8_decode('Quantité'), 1, 0, 'C', 1);
        // position de la colonne 3 (130 = 70+60)
        $this->SetX(110);
        $this->Cell(50, 8, 'Montant unitaire', 1, 0, 'C', 1);
        $this->SetX(160);
        $this->Cell(40, 8, 'Total', 1, 0, 'C', 1);
        $this->Ln(); // Retour à la ligne


        $this->SetDrawColor(31, 73, 125); // Couleur du fond RVB
        $this->SetFillColor(255); // Couleur des filets RVB
        $this->SetTextColor(1); // Couleur du texte 
        $this->SetY($position_ligne1 + 8);
        $this->Cell(50, 8, utf8_decode('Véhicule'), 1, 0, 'L', 1);  // 60 >largeur colonne, 8 >hauteur colonne
        // position de la colonne 2 (70 = 10+60)
        $this->SetX(60);
        $this->Cell(50, 8, $fraisforfaitVehicule[1], 1, 0, 'R', 1);
        // position de la colonne 3 (130 = 70+60)
        $this->SetX(110);
        $this->Cell(50, 8, $fraisforfaitVehicule[2], 1, 0, 'R', 1);
        $this->SetX(160);
        $this->Cell(40, 8, $fraisforfaitVehicule[3], 1, 0, 'R', 1);
        $this->total += $fraisforfaitVehicule[3];
        $this->Ln(); // Retour à la ligne
        $this->Cell(50, 8, utf8_decode('Nuitée'), 1, 0, 'L', 1);  // 60 >largeur colonne, 8 >hauteur colonne
        // position de la colonne 2 (70 = 10+60)
        $this->SetX(60);
        $this->Cell(50, 8, $fraisforfaitNuitee[1], 1, 0, 'R', 1);
        // position de la colonne 3 (130 = 70+60)
        $this->SetX(110);
        $this->Cell(50, 8, $fraisforfaitNuitee[2], 1, 0, 'R', 1);
        $this->SetX(160);
        $this->Cell(40, 8, $fraisforfaitNuitee[3], 1, 0, 'R', 1);
        $this->total += $fraisforfaitNuitee[3];
        $this->Ln();

        $this->Cell(50, 8, 'Repas Midi', 1, 0, 'L', 1);  // 60 >largeur colonne, 8 >hauteur colonne
        // position de la colonne 2 (70 = 10+60)
        $this->SetX(60);
        $this->Cell(50, 8, $fraisforfait[1], 1, 0, 'R', 1);
        // position de la colonne 3 (130 = 70+60)
        $this->SetX(110);
        $this->Cell(50, 8, $fraisforfait[2], 1, 0, 'R', 1);
        $this->SetX(160);
        $this->Cell(40, 8, $fraisforfait[3], 1, 0, 'R', 1);
        $this->total += $fraisforfait[3];
        $this->Ln();
    }

    public function tableau2($position2, $horsforfait) {
        $this->SetY($position2 - 8);
        $this->Cell(0, 8, 'Autres Frais', 0, 0, 'C', 1);
        $this->SetFont('Times', 'I', 12);
        $this->SetDrawColor(31, 73, 125); // Couleur du fond RVB
        $this->SetFillColor(255); // Couleur des filets RVB
        $this->SetTextColor(31, 73, 125); // Couleur du texte 
        $this->SetY($position2);
        $this->Cell(50, 8, 'Date', 1, 0, 'C', 1);  // 60 >largeur colonne, 8 >hauteur colonne		
        $this->SetX(60);
        $this->Cell(100, 8, utf8_decode('Libellé'), 1, 0, 'C', 1);
        // position de la colonne 3 (130 = 70+60)
        $this->SetX(160);
        $this->Cell(40, 8, 'montant', 1, 0, 'C', 1);

        $this->SetDrawColor(31, 73, 125); // Couleur du fond RVB
        $this->SetFillColor(255); // Couleur des filets RVB
        $this->SetTextColor(1); // Couleur du texte  
        $ligne = 0;
        while ($ligne < count($horsforfait)) {
            $position2 += 8;
            $this->SetY($position2);
            $this->Cell(50, 8, $horsforfait[$ligne]['date'], 1, 0, 'L', 1);
            $this->SetX(60);
            $this->Cell(100, 8, utf8_decode($horsforfait[$ligne]['libelle']), 1, 0, 'L', 1);
            $this->SetX(160);
            $this->Cell(40, 8, $horsforfait[$ligne]['montant'], 1, 0, 'R', 1);
            $this->total += $horsforfait[$ligne]['montant'];
            $this->Ln();
            $ligne++;
        }
    }

    public function totalPrix($numMois, $numAnnee) {
        $this->SetDrawColor(31, 73, 125); // Couleur du fond RVB
        $this->SetFillColor(255); // Couleur des filets RVB
        $this->SetTextColor(1); // Couleur du texte 
        $this->SetX(110);
        $this->Cell(50, 8, 'TOTAL ' . $numAnnee . '/' . $numMois, 1, 0, 'L', 1);
        $this->SetX(160);
        $this->Cell(40, 8, $this->total, 1, 0, 'R', 1);
    }

    public function Footer() {
        $this->SetY(-80);
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(0, 0, 0);
        setlocale(LC_TIME, "");
        // Date du jour
        date_default_timezone_set('Europe/Paris');
        $date_du_jour = date("j F Y");
        $this->Cell(0, 10, utf8_decode("Fait à Paris, le " . $date_du_jour), 0, 0, "R");
        $this->Ln();
        $this->Cell(0, 10, "Vu par l'agent comptable", 0, 0, 'R');
        // Signature
        $SignatureComptable = "../resources/Outils/signature.jpg";
        $this->Image($SignatureComptable, 150, 250, 50);
    }

}