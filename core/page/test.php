<?php
/* Towns4, www.towns.cz
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/test.php

   testování
*/

require('lib/pdf/fpdf.php');

$pdf=new FPDF();
$pdf->AddPage();


//$fontfile = root.'lib/font/Trebuchet MS.ttf';
$pdf->SetFont('Arial','B',16);

$pdf->SetXY(0,0);




$image1='ui/image/bg/rvscgostroy.jpg';
$pdf->Cell( 0, 0, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 100), 0, 0, 'L', false );



$pdf->SetXY(10,10);

$pdf->Cell(40,10,'Hello World!',1);


$pdf->Output();


?>
