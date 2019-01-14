<?php
	$d=$argv[2];
	$p=$argv[3];
	$nom=$argv[4];
	$proceso = $d;
	$userId=$argv[5]."_".$proceso;
	$ss=$argv[6];
	shell_exec("ssh ncr4400@DCS 'sh /dcs_datos/busquedas/bin/INT_SE.sh $d $p ,$proceso $ss $userId'");
	shell_exec("scp ncr4400@DCS:/dcs_datos/busquedas/Resultados/int_".$proceso.".txt /data/mediacion_dbo/carga/transformacion/int/tmp.txt");
	shell_exec("cat /data/mediacion_dbo/carga/transformacion/int/tmp.txt | sort | uniq > /data/mediacion_dbo/carga/transformacion/int/int_".$proceso.".txt");
	shell_exec("rm /data/mediacion_dbo/carga/transformacion/int/tmp.txt")
?>