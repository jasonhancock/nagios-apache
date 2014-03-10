<?php
// Set this to true if you want to see the open slots on the graph. I have it
// disabled by default because my web servers aren't heavily trafficed, so it
// dwarfs all of the other data
$include_open_slot = false;
$alpha = 'AA';
$color = '#006699' . $alpha;

// Could make this cleaner as an array of hashes
$scoreboard = array(
    'Waiting for connection',
    'Starting up           ',
    'Reading request       ',
    'Sending reply         ',
    'Keepalive             ',
    'DNS lookup            ',
    'Closing connection    ',
    'Logging               ',
    'Gracefully finishing  ',
    'Idle cleanup          ',
    'Open slot             '
);
$scoreboard_color = array(
    '3333FF',
    'FFFF00',
    '00CC66',
    'FF00FF',
    '00FFFF',
    'FF0000',
    '993300',
    '66FFFF',
    'CC0099',
    '00FF00',
    'C2C2D6'
);

// Hits per second
$ds_name[1] = 'Hits';
$opt[1]  = "-T 55 -l 0 --vertical-label '$LABEL[1]/s' --title \"$hostname / Apache Hits Per Second\"";
$def[1]  = "DEF:var0=$rrdfile:$DS[1]:AVERAGE";
$def[1] .= " AREA:var0#66FF33:$NAME[1]";
$def[1] .= " GPRINT:var0:LAST:\"%4.1lf %s$LABEL[1] LAST\"";
$def[1] .= " GPRINT:var0:MAX:\"%4.1lf %s$LABEL[1] MAX\"";
$def[1] .= " GPRINT:var0:AVERAGE:\"%4.1lf %s$LABEL[1] AVG\"";

$ds_name[2] = 'Network Traffic';
$opt[2]  = "-T 55 -l 0 --vertical-label 'bit/s' --title \"$hostname / Apache Outbound Bandwidth\"";
$def[2]  = "DEF:var0=$rrdfile:$DS[2]:AVERAGE";
$def[2] .= " CDEF:var0_bits=var0,8,*";
$def[2] .= " AREA:var0_bits#FF00FF:Network";
$def[2] .= " GPRINT:var0_bits:LAST:\"%4.1lf %s$UNIT[2] LAST\"";
$def[2] .= " GPRINT:var0_bits:MAX:\"%4.1lf %s$UNIT[2] MAX\"";
$def[2] .= " GPRINT:var0_bits:AVERAGE:\"%4.1lf %s$UNIT[2] AVG\"";

$ds_name[3] = 'Workers';
$opt[3]  = "-T 55 -l 0 --vertical-label 'Workers' --title \"$hostname / Apache Workers\"";
// Busy
$def[3]  = "DEF:var0=$rrdfile:$DS[3]:AVERAGE";
$def[3] .= sprintf(" AREA:var0#FF00FF:'%-20s'",$LABEL[3]);
$def[3] .= " GPRINT:var0:LAST:\"%4.1lf LAST\"";
$def[3] .= " GPRINT:var0:MAX:\"%4.1lf MAX\"";
$def[3] .= " GPRINT:var0:AVERAGE:\"%4.1lf AVG\t\t\t\"";
// idle
$def[3] .= " DEF:var1=$rrdfile:$DS[4]:AVERAGE";
$def[3] .= sprintf(" AREA:var1#00FFFF:'%-18s':STACK",$LABEL[4]);
$def[3] .= " GPRINT:var1:LAST:\"%4.1lf LAST\"";
$def[3] .= " GPRINT:var1:MAX:\"%4.1lf MAX\"";
$def[3] .= " GPRINT:var1:AVERAGE:\"%4.1lf AVG\"";

$ds_name[4] = 'Scoreboard';
$opt[4]  = "-T 55 -l 0 --vertical-label 'Workers' --title \"$hostname / Apache Scoreboard\"";
$def[4]  = '';
for($i=0; $i<count($scoreboard) - ($include_open_slot ? 0 : 1); $i++) {
    $ds_idx=$i+5;
    $def[4] .= sprintf(" DEF:var%d=%s:%d:AVERAGE",$i,$rrdfile,$DS[$ds_idx]);
    $def[4] .= sprintf(" AREA:var%d#%s:'%s'",$i,$scoreboard_color[$i],substr($scoreboard[$i],0,20));
    if ($i >= 1)
        $def[4] .= ":STACK";
    $def[4] .= " GPRINT:var{$i}:LAST:\"%8.1lf LAST\"";
    $def[4] .= " GPRINT:var{$i}:MAX:\"%8.1lf MAX\"";
    $def[4] .= " GPRINT:var{$i}:AVERAGE:\"%8.1lf AVG\t\t\"";
    error_log("Apache Scoreboard: $def[4]",0);
}

?>