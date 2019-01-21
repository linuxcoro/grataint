#==========================================================================*
# enviar_rpt_int.sh : Genera y envia el pdf por email
#                     Reporte Comportamiento del Tráfico V-M
#              Fecha: 2019-12-21
#              Autor: Edixon Idrogo
# ..........................................................................
# Determinar fecha de trabajo 
# ....................................................................
  hoy="$1"


  centuria="`date "+%C"`"
  raya="-"
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
  dias="`echo "$ayer" | cut -c5-6`"

dia="$centuria$ano$mes$dias"
diap="$centuria$ano-$mes-$dias"


resultado=`ps -fumediacion | grep generar_int.php | grep -v grep`
if test "$resultado" = ""
  then
    php /aplicaciones/mediacion/centrales/web/internacionales/generar_int.php $raya $dia
    php /aplicaciones/mediacion/centrales/web/internacionales/mail_int.php $raya $diap
    mv /aplicaciones/mediacion/centrales/web/internacionales/gf_int/int.pdf /aplicaciones/mediacion/centrales/web/internacionales/gf_int/int_$dia.pdf
fi
exit 0







