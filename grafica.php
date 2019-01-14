<?php
include ("/aplicaciones/mediacion/centrales/web/colecta/jpgraph/src/jpgraph.php");
include ("/aplicaciones/mediacion/centrales/web/colecta/jpgraph/src/jpgraph_line.php");
include_once("/aplicaciones/mediacion/centrales/web/colecta/clase_principal/class_principal.php");

// Crear Instancia
$base= new clsdata;

// Conectar con la base de datos
$conexion = $base->crear_conexion($base->server,$base->user,$base->pass);

// Ejecutar la consulta SQL
$fecha=$argv[2];
$fec_fin = $fecha;
$fec_ini = substr($fecha,0,4).substr($fecha,4,2)."01";

$fechas = $base->consulta_base_de_datos("SELECT a.fecha_llamada,count(*) as llamadas,ROUND(SUM(a.duracion)/60) AS minutos FROM internacional a  WHERE a.fecha_llamada BETWEEN '$fec_ini' AND '$fec_fin' GROUP BY a.fecha_llamada",$base->base,$conexion);

$minute = array();
$dias = array();
$calls = array();

while ($fila = $base->obtener_resultados($fechas))
{
		$minute[] = (int)$fila['minutos'];
		$calls[] = (int)$fila['llamadas'];
		$fec = $fila['fecha_llamada'];
		$dias[] = substr($fec,6,2);
		
		$days[] = array(
			$fec,
			date('N', strtotime(substr($fec,0,4).substr($fec,4,2).substr($fec,6,2))),
			(int)$fila['minutos'],
			(int)$fila['llamadas']
		);

		


}

$graph = new Graph(1100,400,"auto");
$graph->SetScale('textlin');

$graph->yscale->ticks->Set(5,1);
$graph->SetBox();
$graph->SetFrame(FALSE);
$graph->img->SetMargin(60,60,40,100);

$mes =  trim(substr($fec_fin,4,2));
$anno =  trim(substr($fec_fin,0,4));

$meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');


$graph->title->Set(utf8_decode("Comportamiento Del Tráfico DDI V-M. Fecha: ") . $meses[$mes+0] . ', ' . $anno);
$graph->title->SetFont(FF_FONT1,FS_BOLD);


$graph->xaxis->SetTickLabels($dias);

$color = array('#FFFF00','#FF8000','#FF0000','#2A0A1B','#00FF00','#8000FF',
	'#00FFFF','#0080FF',
	'#0000FF','#00FF80','#FF00FF','#FF0080','#6E6E6E','#FEBB01','#80FE01','#090909','#93F036','#613E5B','#3E5161','#F29090','#A190F2','#D8F290','#400812','#BDBDBD','#D8D8D8','#5E610B','#3B240B');

$p1 = new LinePlot($minute);

$p1->value->SetColor("darkred");
$p1->SetColor($color[8]);
$p1->SetLegend('Minutos');
$p1->SetCenter();
$p1->mark->SetType(MARK_SQUARE);
$p1->mark->SetFillColor($color[8]);
$p1->mark->SetColor($color[8]);
$p1->mark->SetSize(6);
$graph->Add($p1);

$p2 = new LinePlot($calls);
$p2->value->SetColor("darkred");
$p2->SetColor($color[2]);
$p2->SetLegend('Llamadas');
$p2->SetCenter();
$p2->mark->SetType(MARK_SQUARE);
$p2->mark->SetFillColor($color[2]);
$p2->mark->SetColor($color[2]);
$p2->mark->SetSize(6);
$graph->Add($p2);

$graph->img->SetAntiAliasing();
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.93,"center");
header('Content-Type: text/html; charset=utf-8');
$graph->Stroke('/aplicaciones/mediacion/centrales/web/internacionales/gf_int/grafica.png');
?>