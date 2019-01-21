<?php
#==========================================================================*
# grafica.php : formar el archivo pdf insertando la grafica generada de 
#                grafica.php
#              Fecha: 2019-12-21
#              Autor: Edixon Idrogo
#-------------------------------------------------------------------------- #
require("/aplicaciones/mediacion/centrales/web/colecta/fpdf/fpdf.php");
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
class PDF extends FPDF
{
    //Cabecera de página
    function Header()
    {
        $this->Image('/aplicaciones/mediacion/centrales/web/colecta/imagenes/cantv.png',10,8,33,0,0);
        $this->SetFont('Times','B',12);
        $this->Cell(40);
        $this->Cell(0,4,utf8_decode("COORDINACIÓN DE MEDIACIÓN"),0,0,'L',0);
        $this->ln();
        $this->Cell(40);
        $this->SetFont('Times','',10);
        $this->Cell(0,4,utf8_decode("COMPORTAMIENTO DIARIO. TRAFICO DDI V-M"),0,0,'L',0);
        $this->ln();
        $this->Line(10, 22, 290, 22);
    }

    //Pie de página
    function Footer()
    {
        $this->SetDrawColor(0,0,0);
        $this->SetY(-18);
        $this->Line(10, 200, 290, 200);
        $this->ln();
        $this->SetFont('Times','B',6);
        $this->Cell(0,9,utf8_decode('Supervisión: Operación y Mtto. Sistemas de Mediación'),0,0,'C');
        $this->SetFont('Times','I',10);
        $this->Cell(0,10,$this->PageNo().' / {nb}',0,0,'R');
    }

    //Tabla coloreada
    function FancyTable($dias,$days,$colectada)
    {   
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0,50,255);
        $this->ln(128);
        $this->ln();
        $this->SetFont('Arial','',6);

        $this->Cell(9);
        for ($j=0;$j<count($days);$j++)
        {
            $this->SetFillColor('0','100','0');
            $this->Cell(8.8,5,$dias[$days[$j][1]],1,0,'C',1);
        }
        $this->ln();
        $this->Cell(9);
        for ($j=0;$j<count($days);$j++)
        {
            $this->Cell(8.8,5,trim(substr($days[$j][0],6,2)) . '-' . trim(substr($days[$j][0],4,2)),1,0,'C',0);
        }

        $this->ln();
        $this->SetTextColor(0,0,0);
        $this->Cell(9,5,"Llamadas",1,0,'C',0);            
        for ($j=0;$j<count($days);$j++)
        {
            $this->Cell(8.8,5,$days[$j][3],1,0,'C',0);
        }

        $this->ln();
        $this->SetTextColor(0,0,0);
        $this->Cell(9,5,"Minutos",1,0,'C',0);
        for ($j=0;$j<count($days);$j++)
        {
            $this->Cell(8.8,5,$days[$j][2],1,0,'C',0);
        }
        $this->Image("/aplicaciones/mediacion/centrales/web/internacionales/gf_int/grafica.png",5,25,300,120,'PNG');
    }
}

#-----------------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------------
//Creación del objeto de la clase heredada
$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$dias = array('','Lun','Mar','Mier','Jue','Vie','Sab','Dom');
$pdf->FancyTable($dias,$days,$minute);
session_unset();
$pdf->Output("/aplicaciones/mediacion/centrales/web/internacionales/gf_int/int.pdf");
?>