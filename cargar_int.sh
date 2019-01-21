#==========================================================================*
# cargar_int.sh : Si dia 01 respalda mes anterior
#                 llama int2.php (busqueda con patron)
#                 llama cargar_int.php (carga base de datos)
#              Fecha: 2019-12-21
#              Autor: Edixon Idrogo
#-------------------------------------------------------------------------- #

# Determinar fecha de trabajo 
# ....................................................................
# AAMMDD
hoy="$1"
centuria="`date "+%C"`"
if test "$hoy" = ""
then
hoy="`date "+%y%m%d"`"
ayer="`/aplicaciones/mediacion/bin/XFZDIANT $hoy`"
else
hoy="$1"
ayer="$1"
fi
ano="`echo "$ayer" | cut -c1-2`"
mes="`echo "$ayer" | cut -c3-4`"
dia="`echo "$ayer" | cut -c5-6`"

ayer2="`/aplicaciones/mediacion/bin/XFZDIANT $hoy`"
ano2="`echo "$ayer2" | cut -c1-2`"
mes2="`echo "$ayer2" | cut -c3-4`"
dias2="`echo "$ayer2" | cut -c5-6`"
hdb="`echo "$hoy" | cut -c5-6`"

if test "$hdb" = "01"
then
mysqldump -h161.196.29.227 -uroot -pBd_ur_2006 --opt mediacion_dbo  internacional --where="fecha_llamada LIKE '$centuria$ano2$mes2%'" > /data/mediacion_dbo/respaldo_BD/int_$ano2$mes2.sql;
fi

#		fecha 		->	12122018 
fecha=`echo $dia$mes$centuria$ano`
echo "fecha: "$fecha

patron=`echo "D.........................................................$centuria$ano$mes$dia.........................B.."`
echo $patron

archivo=`echo "prueba_int_$fecha"`
echo "archivo: "$archivo

usuario="prueba"

ss=5

php /aplicaciones/mediacion/centrales/web/internacionales/int2.php - $fecha $patron $archivo $usuario $ss

resultado=`ps -fumediacion | grep cargar_int.php | grep -v grep`
if test "$resultado" = ""
	then
		php /aplicaciones/mediacion/centrales/web/internacionales/cargar_int.php - $fecha $centuria$ano2$mes2
fi
