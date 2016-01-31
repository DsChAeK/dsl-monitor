# DSL Monitor
This is a simple website + PHP script to monitor your FRITZ!Box DSL info online.
You can select a date range and you can zoom in over multiple charts.

I'm using a FRITZ!Box 7362 SL (UI) with FRITZ!OS 06.20 and german webinterface.
You may need to modify the sources for other languages or OS versions.
It's running on my Odroid C1 with Ubuntu 14.04.

Here you can see my DSL line before and after my troubles.
![Alt text](https://github.com/DsChAeK/dsl-monitor/blob/master/screenshot.jpg "screenshot")

## Info
* Author:        DsChAeK

* Version:       v2.0

* License:     Copyright (c) 2015 by DsChAeK

        Permission to use, copy, modify, and/or distribute this software for any purpose
        with or without fee is hereby granted, provided that the above copyright notice
        and this permission notice appear in all copies.
                
        THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
        REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND
        FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT,
        OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE,
        DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS
        ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

## Changes
v2.1, 31.01.2016
* fixed start_index if start_date wasn't found
* added DSLAM info to data log
* added regex data example
* updated dsl_mon_example.log

v2.0, 30.01.2016
* Optimized for short time ranges with big data

v1.0, 04.10.2015
* First release

## Contribution
I used and modified code from some hard workers:

http://www.highcharts.com
-> Amazing charts for websites

http://jsfiddle.net/Gv7Tg/27/
-> Zoom multiple charts at once

http://www.ip-phone-forum.de/showthread.php?t=196309
-> API to get DSL info from FRITZ!Box

http://www.daterangepicker.com/
-> Nice selection of a date range


## Files/Folders
* index.php:  Main website, loads and displays DSL info from a logfile
* dsl.php:  Skript to get current DSL info and append it to a logfile
* config.php:  All configurations here
* /fritz/:  API to get the DSL info
* /js/chart.js:  Charts
* /log/dsl_mon.log:  The logfile for DSL info
* .htaccess:  File for basic authentification  


## Usage example (Ubuntu)
You should have some basic knowledge of linux and stuff!

1. Put all files to your webserver directory

     /var/www/html/dsl_monitor

2. Modify .htaccess for your .htpasswd file

     AuthUserFile /var/.htpasswd

3. Modify config.php for your needs

  // Logfile
  $filename = '/var/www/html/dsl_monitor/log/dsl_mon.log';
  
  // Timezone offset (hours)
  $time_offset = 2;
  
  // Password of your FritzBox
  $password = 'your_password_of_fritzbox_web_login';
  
  // Display last 2 days only (0 = ALL)
  $show_last_days = 2;

4. Make logfile accessible (at least writable for your user)
     
     sudo chmod 777 /var/www/html/dsl_monitor/log/

5. Test dsl.php

     php /var/www/html/dsl_monitor/dsl.php

6. Check if dsl_monitor.log was created in /log/
     
     cd /var/www/html/dsl_monitor/log/
     ls -la
     
7. Install a cronjob for dsl.php skript
   
     sudo crontab -e
       */1 * * * * php /var/www/html/dsl_monitor/dsl.php > /dev/null 2>&1

   -> The script is called every minute, you can change the interval to generate less data

8. There you go watching your DSL info online :)
     
     http://your_ip/dsl_monitor/


## Thoughts of improvement
  * Earn more web developer skills and do better code :)
  * Add interval to backup current logfile and create a new one
  * Load data over multiple logfiles
  * One zoom reset button for all charts and better performance of that
  * Display more DSL info or/and make charts more user configurable
