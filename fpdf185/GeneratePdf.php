<?php

require('fpdf.php');

class GeneratePdf extends FPDF{
    
    
    Function Header(){
        //logo
        $this->Image('../public/images/logo.png',30,10,0);
    }
}