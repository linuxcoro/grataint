<?php
$fecha=$argv[2];
$dia_c = substr($fecha,0,2);
$mes_c = substr($fecha,2,2);
$amo_c = substr($fecha,4,4);

$fec_file_cde=$amo_c.$mes_c.$dia_c;

$directorio = '/data/mediacion_dbo/carga/transformacion/int/';

# datos de conexion
$server = 'localhost';
$user = 'root';
$pass = 'Bd_ur_2006';
$base = "mediacion_dbo";

$fecha1 = date('YmdHis'); # fecha de carga
$cont_arch = 0; # contador de archivos
$arch_vac = 0; # archivos vacios
$arch_rep = 0; # archivos repetidos
$reg_alm = 0; #registros almacenados
$no_procesados = "UBICACION DE LOS ARCHIVOS NO PROCESADOS\n";

$db = mysql_pconnect("$server", "$user", "$pass");

if (!$db)
{
   echo "Error: no se ha podido conectar a la base de datos. Por favor prueba de nuevo más tarde\n";
   exit();
}
$mensaje_conexion = "Conexion OK a la BD";
echo $mensaje_conexion."\n";
mysql_select_db("$base") or die("No puede seleccionarse la BD.");

mysql_query("DELETE FROM internacional WHERE fecha_llamada='".$fec_file_cde."';");

#int_18122018.txt

foreach(glob($directorio .'{*'.$fecha.'*}', GLOB_BRACE) as $archivo)
{
  $directorio2[] = substr(strrchr($archivo, "/"), 1);
  $archivo2[] = $archivo;
}

foreach($directorio2 as $key2 => $valor ){  

    echo "cargando ..." . $archivo2[$key2]."\n";

    $fp = fopen($archivo2[$key2],"r"); # apertura de los archivos
    $cont_arch = $cont_arch + 1; # cuento los archivos
    $caracteres = 0; # caracteres en cada archivo  

    
    $insertar = 1;
    $sql = "REPETIDO\n";
    while ( ($linea = fgets($fp)) !== false)
    {
      # validacion de las lineas
      if(strlen(trim($linea))>93)
      {
        $caracteres += strlen($linea); # cuento los caracteres de los archivos
        $reg_alm++;


        # insercion de PARTES de la cadena a la base de datos
        $tipo = (trim(substr($linea,0,1)) == '') ? 'NULL' : "'".trim(substr($linea,0,1))."'";
        $cnt_envio = (trim(substr($linea,1,6)) == '') ? 'NULL' : "'".trim(substr($linea,1,6))."'";
        $t_insumo = (trim(substr($linea,7,2)) == '') ? 'NULL' : "'".trim(substr($linea,7,2))."'";
        $t_servicio = (trim(substr($linea,9,2)) == '') ? 'NULL' : "'".trim(substr($linea,9,2))."'";
        $abonano_a = (trim(substr($linea,11,18)) == '') ? 'NULL' : "'".trim(substr($linea,11,18))."'";
        $cat_a = (trim(substr($linea,29,2)) == '') ? 'NULL' : "'".trim(substr($linea,29,2))."'";
        $abonado_b = (trim(substr($linea,31,24)) == '') ? 'NULL' : "'".trim(substr($linea,31,24))."'";
        $cod_op1 =  (trim(substr($linea,55,3)) == '') ? 'NULL' : "'".trim(substr($linea,55,3))."'";
        $fecha_llam = (trim(substr($linea,58,8)) == '') ? 'NULL' : "'".trim(substr($linea,58,8))."'";
        $hor_ini = (trim(substr($linea,66,6)) == '') ? 'NULL' : "'".trim(substr($linea,66,6))."'";
        $duracion = (trim(substr($linea,72,7)) == '') ? 'NULL' : "'".trim(substr($linea,72,7))."'";
        $ind_llam = (trim(substr($linea,79,2)) == '') ? 'NULL' : "'".trim(substr($linea,79,2))."'";
        $nu_reg = (trim(substr($linea,81,2)) == '') ? 'NULL' : "'".trim(substr($linea,81,2))."'";
        $t_entrada = (trim(substr($linea,83,8)) == '') ? 'NULL' : "'".trim(substr($linea,83,8))."'";
        $t_salida = (trim(substr($linea,91,8)) == '') ? 'NULL' : "'".trim(substr($linea,91,8))."'";
        $abonado_c = (trim(substr($linea,99,24)) == '') ? 'NULL' : "'".trim(substr($linea,99,24))."'";
        $cdg_razon = (trim(substr($linea,123,3)) == '') ? 'NULL' : "'".trim(substr($linea,123,3))."'";
        $fecha_tx = (trim(substr($linea,126,8)) == '') ? 'NULL' : "'".trim(substr($linea,126,8))."'";
        $cod_op2 = (trim(substr($linea,134,5)) == '') ? 'NULL' : "'".trim(substr($linea,134,5))."'";
        $t_acc = (trim(substr($linea,139,1)) == '') ? 'NULL' : "'".trim(substr($linea,139,1))."'";
        $cod_no_comp = (trim(substr($linea,140,2)) == '') ? 'NULL' : "'".trim(substr($linea,140,2))."'";
        $mon_op = (trim(substr($linea,142,9)) == '') ? 'NULL' : "'".trim(substr($linea,142,9))."'";



        $dur_val = (trim(substr($linea,151,7)) == '') ? 'NULL' : "'".trim(substr($linea,151,7))."'";



        $n_archivo = (trim(substr($linea,158,4)) == '') ? 'NULL' : "'".trim(substr($linea,158,4))."'";
        $filler4 = (trim(substr($linea,162,8)) == '') ? 'NULL' : "'".trim(substr($linea,162,8))."'";

        $ip1 = (trim(substr($linea,170,15)) == '') ? 'NULL' : "'".trim(substr($linea,170,15))."'";
        $ip2 = (trim(substr($linea,185,15)) == '') ? 'NULL' : "'".trim(substr($linea,185,15))."'";


        $sql = "INSERT INTO internacional (TECNOLOGIA,SW_ENVIO,TP_INSUMOS,TP_SERVICIO,ABONADO_A,CATEGORIA_A,ABONADO_B,COD_OPERADOR,FECHA_LLAMADA,HORA_INICIO,DURACION,INDICADOR,NUMERO_REGISTRO,CIRCUITO_IN,CIRCUITO_OUT,ABONADO_C,CODIGO_RAZON,FECHA_TX,CODIGO_OPERADOR,TIPO_ACCESO,CODIGO_NO_COMP,MONTO_OPERADORA,DUR_VALORIZADA,NUMERO_ARCHIVO,RELLENO,CLIGW,CLDGW,FEC_CARGA) VALUES ($tipo, $cnt_envio, $t_insumo, $t_servicio, $abonano_a, $cat_a, $abonado_b,$cod_op1, $fecha_llam, $hor_ini, $duracion,$ind_llam, $nu_reg, $t_entrada, $t_salida, $abonado_c, $cdg_razon, $fecha_tx, $cod_op2, $t_acc, $cod_no_comp, $mon_op,$dur_val, $n_archivo, $filler4,$ip1,$ip2,$fecha1)";

        $insertar = mysql_query($sql);

      }      
    }
    unlink($archivo2[$key2]);
} 
mysql_query("DELETE FROM internacional where circuito_in like 'B%' and fecha_llamada='".$fec_file_cde."';");

mysql_close($db);
?>
