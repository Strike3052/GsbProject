<?php
require'../resources/Outils/PDF_MySQL_Table.php';
class c_PDF extends PDF_MySQL_Table
{
    
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
    $this->Ln(20);
    
    $this->SetFont('Arial','',11);
    $this->SetTextColor(0,0,0);  
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
    $SignatureComptable = "../resources/Outils/signature.jpg";
    $this->Image($SignatureComptable, 150, 250, 50);

}

}
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id=$_SESSION['idVisiteur'];
// Connexion à la base

$link = mysqli_connect('localhost','userGsb','secret','gsb_frais');
$sql ="select id, nom, prenom from visiteur where id='$id'";
$res = mysqli_query($link,$sql);
$row = mysqli_fetch_array($res);

ob_clean();
$pdf = new c_PDF();
$pdf->AddPage();
$pdf->Cell(0,10,'Visiteur',0,0,'L');
$pdf->Cell(-190,10,$row['id'],0,0,'C');
$pdf->Cell(0,10,$row['prenom']." ".$row['nom'],0,0,'R');
$pdf->Cell(0,30,'Mois',0,0,'L');
//$pdf->Cell(0,50,$leMois,0,0,'C');
$pdf->Output();
