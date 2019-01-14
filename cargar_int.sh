#==========================================================================*
# enviar_rpt_int.sh : Envia por correo el reporte Internacional.
#==========================================================================*
#
#              Fecha: 2018-12-xx
#              Autor: Edixon Idrogo
#              
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
		php /aplicaciones/mediacion/centrales/web/internacionales/cargar_int.php  - $fecha
fi
