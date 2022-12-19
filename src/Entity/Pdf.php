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
function Table($header, $data)
{
    // Largeurs des colonnes
    $w = array(40, 80, 40);
    // En-tête
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Données
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');    
        $this->Cell($w[2],6,$row[2],'LR'); 
        $this->Ln();
    }
    // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
}
    // Tableau amélioré
function ImprovedTable($header, $data)
{
    // Largeurs des colonnes
    $w = array(40, 35, 45, 40);
    // En-tête
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Données
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,$row[2],'LR');
        //$this->Cell($w[3],6,$row[3],'LR');
        $this->Ln();
    }
    // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
}


    function Row($data)
    {
    $this->SetX($this->TableX);
    $ci=$this->ColorIndex;
    $fill=!empty($this->RowColors[$ci]);
    if($fill)
        $this->SetFillColor($this->RowColors[$ci][0],$this->RowColors[$ci][1],$this->RowColors[$ci][2]);
    foreach($this->aCols as $col)
        $this->Cell($col['w'],5,$data[$col['f']],1,0,$col['a'],$fill);
    $this->Ln();
    $this->ColorIndex=1-$ci;
    }

    function AddCol($field=-1, $width=-1, $caption='', $align='L')
    {
    // Add a column to the table
    if($field==-1)
        $field=count($this->aCols);
    $this->aCols[]=array('f'=>$field,'c'=>$caption,'w'=>$width,'a'=>$align);
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
$pdf->Cell(-100,60,$lemois,0,0,'L');
$pdf->Ln(60);
$entete1=array('Frais Forfaitaires','Quantite','Montant unitaire', 'Total');
$entete2=array('Date','Libelle','Montant');
$pdf->ImprovedTable($entete1, $fraisforafait);
$pdf->Ln(60);
//$pdf->Table($entete2, $horsforfait);
$pdf->Output();