<?php
#==========================================================================*
# mail_int.sh : Envia mail con el pdf adjunto
#               Reporte Comportamiento del Tráfico V-M:
#              Fecha: 2019-12-21
#              Autor: Edixon Idrogo
# ..........................................................................
$fecha=$argv[2];
$bar = strtotime($fecha); 
$candy = strtotime('+1 days',$bar); 
$date = date('d/m/Y',$candy);

// , jguerr@cantv.com.ve
$to          = "eidrog01@cantv.com.ve"; // addresses to email pdf to
$from        = "monitoreo@cantv.com.ve"; // address message is sent from
$subject     = "Reporte Comportamiento del Tráfico V-M $date"; // email subject
$body        = "<p>El PDF esta adjunto.</p>"; // email body
$pdfLocation = "/aplicaciones/mediacion/centrales/web/internacionales/gf_int/int.pdf"; // file location
$pdfName     = "int.pdf"; // pdf file name recipient will get
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