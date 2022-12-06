<?php
require('../../fpdf185/fpdf.php');

class v_test extends FPDF
{
function Header()
{
    // Logo
    $this->Image('../../public/images/logo.jpg',90,20,30);
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

function Footer() {
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
    $SignatureComptable = "../../resources/Outils/signatureComptable.jpg";
    $this->Image($SignatureComptable, 150, 250, 50);

}
}
// Connexion à la base
//$link = mysqli_connect('localhost','userGsb','secret','gsb_frais');

ob_clean();
$pdf = new v_test();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Times','',12);
$pdf->Output();
