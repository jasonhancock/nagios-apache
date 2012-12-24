nagios-apache
===============

A Nagios plugin to monitor apache via the mod\_status module. This plugin assumes
you have mod\_status enabled and ExtendedStatus set to On.


LICENSE: MIT
------------
Copyright (c) 2012 Jason Hancock <jsnbyh@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

GRAHS:
------

This plugin produces four graphs based on one check.

The first graph shows the number of hits per second:

![hits](https://github.com/jasonhancock/nagios-apache/raw/master/example-images/check_apache_hits.png)

The second graph shows how many bits are being served up by apache

![bandwidth](https://github.com/jasonhancock/nagios-apache/raw/master/example-images/check_apache_bandwidth.png)

The third graph shows how many worker processes exist and whether they are
busy or idle.

![workers](https://github.com/jasonhancock/nagios-apache/raw/master/example-images/check_apache_workers.png)

The last graph is a visual representation of the apache scoreboard, showing
the various states of all of the connections

![scoreboard](https://github.com/jasonhancock/nagios-apache/raw/master/example-images/check_apache_scoreboard.png)

REQUIREMENTS:
-------------
pnp4nagios >= 0.6 (check_apache.php uses the "rrd" php class introduced in 0.6.x)  
php >= 5.1  
nagios >= 2  

INSTALLATION:
-------------

Copy the plugins from this repository (plugins/) into Nagios' plugins
directory on the Nagios server (this is usually /usr/lib64/nagios/plugins on 
a 64-bit RHEL/CentOS box). 

Copy the pnp4nagios templates from this repository (pnp4nagios/templates/) into pnp4nagios'
templates directory (On EL6 using the pnp4nagios package from EPEL, this directory is 
/usr/share/nagios/html/pnp4nagios/templates).

Copy the pnp4nagios check commands configurations from this repository (pnp4nagios/check_commands\)
into pnp4nagios' check_commands directory. Using the same package from EPEL as above, this is 
/etc/pnp4nagios/check_commands. Do this BEFORE configuring the service checks in Nagios otherwise 
the RRD's will get created with the wrong data types (To fix this, just delete the .rrd files and
start over).

NAGIOS CONFIGURATION:
---------------------

```
define command{
    command_name check_apache
    command_line $USER1$/check_apache -H $HOSTADDRESS$
}

define service {
    check_command                  check_apache
    host_name                      somewebserver.example.com
    service_description            Apache Stats
    use                            some-service-definition
}
```

NAGIOS 2.x NOTES:
---------------------
For Nagios 2.x, you need to use extended host/service objects. More information is here:  
[http://docs.pnp4nagios.org/pnp-0.4/webfe](http://docs.pnp4nagios.org/pnp-0.4/webfe)
