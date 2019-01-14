<?php
#include ("/aplicaciones/mediacion/centrales/web/internacionales/src/jpgraph.php");
#include ("/aplicaciones/mediacion/centrales/web/internacionales/src/jpgraph_line.php");

include ("/aplicaciones/mediacion/centrales/web/colecta/jpgraph/src/jpgraph.php");
include ("/aplicaciones/mediacion/centrales/web/colecta/jpgraph/src/jpgraph_line.php");

// Some data
$ydata = array(11,3,8,12,5,1,9,13,5,7);
 
// Create the graph. These two calls are always required
$graph = new Graph(1100,500,"auto");
$graph->SetScale("textlin",0,110,0,0);


// Create the linear plot
$lineplot=new LinePlot($ydata);
$lineplot->SetColor('blue');
 
// Add the plot to the graph
$graph->Add($lineplot);
  
// Stroke image to a file and browser
 
// Default is PNG so use ".png" as suffix
#$graph->img->Stream($fileName);

// Display the graph








$graph->Stroke("gf_int/prueba.png");
$graph->Stroke();

echo "salida\n";
 

?>