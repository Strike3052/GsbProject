<?php

/**
 * Description of Pdf
 *
 * @author Aymé
 */
require'../fpdf185/fpdf.php';

class Pdf extends FPDF{
    
protected $ProcessingTable=false;
protected $aCols=array();
protected $TableX;
protected $HeaderColor;
protected $RowColors;
protected $ColorIndex;
public $total;    
        function Header()
    {
        // Logo
        $this->Image('../public/images/logo.jpg',90,20,30);
        //saut de lignes
        $this->Ln(50);
        //police
        $this->SetFont('Arial','B',13.5);
        $this->SetTextColor(31,73,125);
        // Décalage à droite
        $this->Cell(5);
        //titre
        $this->Cell(180,8,'REMBOURSEMENT DE FRAIS ENGAGES',1,0,'C');

    }
    
    function tableauFraisForfait($position_ligne1, $fraisforfaitNuitee,$fraisforfaitVehicule, $fraisforfait){
    global $pdf;
    $pdf->SetFont('Times','I',12);
   $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
   $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 31,73,125); // Couleur du texte 
    $pdf->SetY($position_ligne1);
    $pdf->Cell(50,8,'frais forfaitaires',1,0,'C',1);  // 60 >largeur colonne, 8 >hauteur colonne
		// position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,utf8_decode('Quantité'),1,0,'C',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,'Montant unitaire',1,0,'C',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,'Total',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
    
    
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 1); // Couleur du texte 
    $pdf->SetY($position_ligne1+8);
    $pdf->Cell(50,8, utf8_decode('Véhicule'),1,0,'L',1);  // 60 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,$fraisforfaitVehicule[1],1,0,'R',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,$fraisforfaitVehicule[2],1,0,'R',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,$fraisforfaitVehicule[3],1,0,'R',1);
    $this->total+= $fraisforfaitVehicule[3];
    $pdf->Ln(); // Retour à la ligne
    $pdf->Cell(50,8,utf8_decode('Nuitée'),1,0,'L',1);  // 60 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,$fraisforfaitNuitee[1],1,0,'R',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,$fraisforfaitNuitee[2],1,0,'R',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,$fraisforfaitNuitee[3],1,0,'R',1);
    $this->total+=$fraisforfaitNuitee[3];
    $pdf->Ln();
    
    $pdf->Cell(50,8,'Repas Midi',1,0,'L',1);  // 60 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,$fraisforfait[1],1,0,'R',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,$fraisforfait[2],1,0,'R',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,$fraisforfait[3],1,0,'R',1);
    $this->total+=$fraisforfait[3];
    $pdf->Ln();
    }
    
    function tableau2($position2, $horsforfait){
    global $pdf;
    $pdf->SetY($position2-8);
    $pdf->Cell(0,8,'Autres Frais',0,0,'C',1);
    $pdf->SetFont('Times','I',12);
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 31,73,125); // Couleur du texte 
    $pdf->SetY($position2);
    $pdf->Cell(50,8,'Date',1,0,'C',1);  // 60 >largeur colonne, 8 >hauteur colonne		
    $pdf->SetX(60); 
    $pdf->Cell(100,8,utf8_decode('Libellé'),1,0,'C',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(160); 
    $pdf->Cell(40,8,'montant',1,0,'C',1);
   
    
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 1); // Couleur du texte  
    $ligne=0;
    while($ligne< count($horsforfait)){
    $position2+=8;
    $pdf->SetY($position2); 
    $pdf->Cell(50,8,$horsforfait[$ligne]['date'],1,0,'L',1);
    $pdf->SetX(60); 
    $pdf->Cell(100,8,utf8_decode($horsforfait[$ligne]['libelle']),1,0,'L',1);
    $pdf->SetX(160);    
    $pdf->Cell(40,8,$horsforfait[$ligne]['montant'],1,0,'R',1);
    $this->total+=$horsforfait[$ligne]['montant'];
    $pdf->Ln();
    $ligne++;
    }
    
    }
function totalPrix ($numMois,$numAnnee){
    global $pdf;
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 1); // Couleur du texte 
    $pdf->SetX(110); 
    $pdf->Cell(50,8,'TOTAL '. $numAnnee.'/'.$numMois,1,0,'L',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8, $this->total,1,0,'R',1);
}




    function Footer() 
    {
        $this->SetY(-80);
        $this->SetFont('Arial','I',10);
        $this->SetTextColor(0,0,0);
        setlocale(LC_TIME, "");
        // Date du jour
        date_default_timezone_set('Europe/Paris');
        $date_du_jour=date("j F Y");
        $this->Cell(0,10, utf8_decode("Fait à Paris, le " . $date_du_jour),0,0,"R");
        $this->Ln();
        $this->Cell(0,10, "Vu par l'agent comptable", 0,0,'R');
        // Signature
        $SignatureComptable = "../resources/Outils/signature.jpg";
        $this->Image($SignatureComptable, 150, 250, 50);

    }
}
ob_clean();
$pdf = new Pdf();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Times','',12);
$pdf->Cell(-180,30,$idVisiteur,0,0,'C');
$pdf->Cell(80,30,'Visiteur',0,0,'C');
$pdf->Cell(120,30, $infoVisiteur['prenom'].' '.$infoVisiteur['nom'],0,0,'C');
$pdf->Cell(-320,60,'Mois',0,0,'C');
$pdf->SetX(10); 
$pdf->Cell(0,60,$numMois .'/'. $numAnnee,0,0,'C');
$pdf->Ln(40);
$pdf->tableauFraisForfait(100,$fraisforfaitNuitee, $fraisforfaitVehicule,$fraisforfait);
$pdf->SetTextColor(30, 73, 125);
$pdf->SetFont('', 'B');
$pdf->tableau2(140,$horsforfait);
$pdf->totalPrix($numAnnee,$numMois);
$pdf->Output();