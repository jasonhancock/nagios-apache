<?php

// Set this to true if you want to see the open slots on the graph. I have it
// disabled by default because my web servers aren't heavily trafficed, so it
// dwarfs all of the other data
$include_open_slot = false;
$alpha = 'AA';
$color = '#006699' . $alpha;

$scoreboard = array(
    'Waiting for connection',
    'Starting up',
    'Reading request',
    'Sending reply',
    'Keepalive',
    'DNS lookup',
    'Closing connection',
    'Logging',
    'Gracefully finishing',
    'Idle cleanup',
    'Open slot'
);

$ds_name[1] = 'Hits';
$opt[1]  = "-T 55 -l 0 --vertical-label 'hits/s' --title \"$hostname / Apache Hits Per Second\"";
$def[1]  = rrd::def('var0', $rrdfile, $DS[1], 'AVERAGE');
$def[1] .= rrd::area('var0', rrd::color(2), 'Hits');
$def[1] .= rrd::gprint('var0', array('LAST','MAX','AVERAGE'), '%4.1lf %s');

$ds_name[2] = 'Network Traffic';
$opt[2]  = "-T 55 -l 0 --vertical-label 'bit/s' --title \"$hostname / Apache Outbound Bandwidth\"";
$def[2]  = rrd::def('var0', $rrdfile, $DS[2], 'AVERAGE');
$def[2] .= rrd::cdef('var0_bits', 'var0,8,*' );
$def[2] .= rrd::area('var0_bits', rrd::color(3), 'Network');
$def[2] .= rrd::gprint('var0_bits', array('LAST','MAX','AVERAGE'), '%4.1lf %s');

$ds_name[3] = 'Workers';
$opt[3]  = "-T 55 -l 0 --vertical-label 'Workers' --title \"$hostname / Apache Workers\"";
$def[3]  = rrd::def('var0', $rrdfile, $DS[3], 'AVERAGE');
$def[3] .= rrd::area('var0', rrd::color(3), 'Workers Busy');
$def[3] .= rrd::gprint('var0', array('LAST','MAX','AVERAGE'), '%4.0lf %s');

$def[3] .= rrd::def('var1', $rrdfile, $DS[4], 'AVERAGE');
$def[3] .= rrd::area('var1', rrd::color(4), 'Workers Idle', 'STACK');
$def[3] .= rrd::gprint('var1', array('LAST','MAX','AVERAGE'), '%4.0lf %s');

$ds_name[4] = 'Scoreboard';
$opt[4]  = "-T 55 -l 0 --vertical-label 'Workers' --title \"$hostname / Apache Scoreboard\"";
$def[4]  = '';
for($i=0; $i<count($scoreboard) - ($include_open_slot ? 0 : 1); $i++) {
    $def[4] .= rrd::def("var$i", $rrdfile, $DS[$i+5], 'AVERAGE');
    if ($i == '1')
        $def[4] .= rrd::area ("var$i", rrd::color($i), rrd::cut($scoreboard[$i], 20));
    else
        $def[4] .= rrd::area ("var$i", rrd::color($i), rrd::cut($scoreboard[$i], 20), 'STACK');

    $def[4] .= rrd::gprint("var$i", array('LAST','MAX','AVERAGE'), "%4.0lf %s");
}
