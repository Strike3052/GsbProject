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
    //1ere ligne
   
    
    //2eme ligne
    $this->Cell(-190,40,'Annee',0,0,'C');
    
    
}
function entete_tableau1(){
    global $pdf;
    $this->SetFont('Arial','',11);
    $this->SetTextColor(0,0,0);
    //1ere ligne
    $this->Cell(0,10,'Visiteur',0,0,'L');
    $this->Cell(-190,10,'Id',0,0,'C');
    $this->Cell(0,10,'nom',0,0,'R');
    //2eme ligne
    $this->Cell(-190,40,'Annee',0,0,'C');
    $this->Cell(0,40,'Mois',0,0,'L');
    
//    $pdf->SetFont('Times','I',12);
//    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
//    $pdf->SetFillColor(255); // Couleur des filets RVB
//    $pdf->SetTextColor( 31,73,125); // Couleur du texte 
//    $pdf->SetY($position_entete1);
//    $pdf->Cell(50,8,'frais forfaitaires',1,0,'C',1);  // 60 >largeur colonne, 8 >hauteur colonne
//    // position de la colonne 2 (70 = 10+60)
//    $pdf->SetX(60); 
//    $pdf->Cell(50,8,'Quantite',1,0,'C',1);
//    // position de la colonne 3 (130 = 70+60)
//    $pdf->SetX(110); 
//    $pdf->Cell(50,8,'Montant unitaire',1,0,'C',1);
//    $pdf->SetX(160); 
//    $pdf->Cell(40,8,'Total',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
}
function ligne1_tableau1($position_ligne1){
    global $pdf;
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 1); // Couleur du texte 
    $pdf->SetY($position_ligne1);
    $pdf->Cell(50,8,'Nuitee',1,0,'L',1);  // 60 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,'NBQuantite',1,0,'R',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,'NBMontant unitaire',1,0,'R',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,'NBTotal',1,0,'R',1);
    $pdf->Ln(); // Retour à la ligne
}
function ligne2_tableau1($position_ligne2){
    global $pdf;
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 1); // Couleur du texte 
    $pdf->SetY($position_ligne2);
    $pdf->Cell(50,8,'Repas midi',1,0,'L',1);  // 60 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,'NBQuantite',1,0,'R',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,'NBMontant unitaire',1,0,'R',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,'NBTotal',1,0,'R',1);
    $pdf->Ln(); // Retour à la ligne
}
function ligne3_tableau1($position_ligne3){
    global $pdf;
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 1); // Couleur du texte 
    $pdf->SetY($position_ligne3);
    $pdf->Cell(50,8,'Vehicule',1,0,'L',1);  // 60 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(50,8,'NBQuantite',1,0,'R',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(110); 
    $pdf->Cell(50,8,'NBMontant unitaire',1,0,'R',1);
    $pdf->SetX(160); 
    $pdf->Cell(40,8,'NBTotal',1,0,'R',1);
    $pdf->Ln(); // Retour à la ligne
}

   function entete_tableau2($position_entete2){
    global $pdf;
    $pdf->SetFont('Times','I',12);
    $pdf->SetDrawColor( 31,73,125); // Couleur du fond RVB
    $pdf->SetFillColor(255); // Couleur des filets RVB
    $pdf->SetTextColor( 31,73,125); // Couleur du texte 
    $pdf->SetY($position_entete2);
    $pdf->Cell(190,8,'Autres Frais',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
    $pdf->Cell(50,8,'Date',1,0,'C',1);  // 50 >largeur colonne, 8 >hauteur colonne
    // position de la colonne 2 (70 = 10+60)
    $pdf->SetX(60); 
    $pdf->Cell(100,8,'Libelle',1,0,'C',1);
    // position de la colonne 3 (130 = 70+60)
    $pdf->SetX(160); 
    $pdf->Cell(40,8,'Total',1,0,'C',1);
    $pdf->Ln(); // Retour à la ligne
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
$sql ="select id,nom,prenom from visiteur where id='$id'";
$res = mysqli_query($link,$sql);
$row = mysqli_fetch_array($res);

ob_clean();
$pdf = new v_test();
$pdf->AddPage();
//$pdf->Cell(90,10,$row['id'],0,0,'C');
//$pdf->Cell(0,10,$row['prenom']." ".$row['nom'],0,0,'R');
//$pdf->Cell(0,50,'Visiteur',0,0,'L');
$pdf->Cell(0,0,$leMois,0,0,'C');
//// Nom et prénom du visiteur
//$pdf->Cell(0, 10, $row['nom'], 0, 0, 'R');
////$mois = substr($row['mois'], -2);

//$pdf->Table($link, 'select Date, libelle, montant from lignefraishorsforfait where idvisiteur="a131" ');

//$pdf->AliasNbPages();
//$pdf->entete_tableau1(110);
//$pdf->SetFont('Times','',12);
//$pdf->ligne1_tableau1(118);
//$pdf->ligne2_tableau1(126);
//$pdf->ligne3_tableau1(134);
//$pdf->entete_tableau2(142);
$pdf->Output();
