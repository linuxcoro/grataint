<?php
#==========================================================================*
# mail_int.php : Envia mail con el pdf adjunto
#               Reporte Comportamiento del Tráfico V-M:
#              Fecha: 2019-12-21
#              Autor: Edixon Idrogo
# ..........................................................................
$fecha=$argv[2];
$bar = strtotime($fecha); 
$date = date('d/m/Y',$bar);
$date2 = date('d-m-y',$bar);

#$to          = "eidrog01@cantv.com.ve"; // addresses to email pdf to
$to          = "eidrog01@cantv.com.ve,jguerr@cantv.com.ve,jcasan@cantv.com.ve,gbulot01@cantv.com.ve"; // addresses to email pdf to
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$mes = date('m',$bar);
$from        = "monitoreo@cantv.com.ve"; // address message is sent from
$subject     = utf8_decode("Reporte Comportamiento del Tráfico V-M Correspondiente al Mes de: ".$meses[$mes-1]." ".substr($date, -4)." - Data al: ".$date);
$body        = utf8_decode("Buenos días,<br>
	<p>Se incluyen gráficas del comportamiento de llamadas del mes de ".$meses[$mes-1]." en base a la cantidad de llamadas y duración de las mismas.</p>
<pre>
Informa:                                           
                                                        
	Aplicacion SGCE del Servidor VMDLIND01   
	
	Sistema de Gestion y Control Estadistico
	Operacion y Mantenimiento de Sistemas de Mediacion
	Coordinacion de Mediacion
</pre>
"); // email body
$pdfLocation = "/aplicaciones/mediacion/centrales/web/internacionales/gf_int/int.pdf"; // file location
$pdfName     = "int_$date2.pdf"; // pdf file name recipient will get
$filetype    = "application/pdf"; // type

// creates headers and mime boundary
$eol = PHP_EOL;
$semi_rand     = md5(time());
$mime_boundary = "==Multipart_Boundary_$semi_rand";
$headers = "From: coomed@cantv.com.ve\n"; 
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed;$eol boundary=\"$mime_boundary\"";

// add html message body
$message = "--$mime_boundary$eol" .
    "Content-Type: text/html; charset=\"iso-8859-1\"$eol" .
    "Content-Transfer-Encoding: 7bit$eol$eol$body$eol";

// fetches pdf
$file = fopen($pdfLocation, 'rb');
$data = fread($file, filesize($pdfLocation));
fclose($file);
$pdf = chunk_split(base64_encode($data));

// attaches pdf to email
$message .= "--$mime_boundary$eol" .
    "Content-Type: $filetype;$eol name=\"$pdfName\"$eol" .
    "Content-Disposition: attachment;$eol filename=\"$pdfName\"$eol" .
    "Content-Transfer-Encoding: base64$eol$eol$pdf$eol--$mime_boundary--";

// Sends the email
if(mail($to, $subject, $message, $headers)) {
    echo "The email was sent.";
}
else {
    echo "There was an error sending the mail.";
}