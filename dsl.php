<?php
/*
License: Copyright (c) 2015 by DsChAeK

Permission to use, copy, modify, and/or distribute this software for any purpose
with or without fee is hereby granted, provided that the above copyright notice
and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT,
OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE,
DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS
ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
*/
  require("config.php");
    
  error_reporting(E_ALL); 

	// Time offset in seconds
  $time_offset *= 60*60;
    
  // Get data from FritzBox
  try
  {
    // load the fritzbox_api class
    require_once('fritz/fritzbox_api.class.php');

    $fritz = new fritzbox_api();

    // http://fritz.box/internet/dsl_stats_tab.lua?update=mainDiv&sid=8f3c203bd1e523c2&xhr=1&t1442752250613=nocache
    $params1 = array(
      'getpage'         => '/internet/dsl_stats_tab.lua',
      'update'          => 'mainDiv',
    );

    // http://fritz.box/internet/inetstat_monitor.lua?sid=7c02d41d7076cd6c&
    $params2 = array(
      'getpage'         => '/internet/inetstat_monitor.lua',
      'action'          => 'get_graphic',
    );

    // http://fritz.box/internet/dsl_overview.lua?sid=d24847de3de91640&useajax=1&action=get_data&xhr=1&t1442743103148=nocache
    $params3 = array(
      'getpage'         => '/internet/dsl_overview.lua',
      'useajax'         => '1',
      'action'          => 'get_data',
    );

    // Append all data to one string
    $html  = $fritz->doGetRequest($params1);
    $html .= $fritz->doGetRequest($params2);
    $html .= $fritz->doGetRequest($params3);
  }
  catch (Exception $e)
  {
   echo $e->getMessage();
  }

  // Logline
  $logline  = '';
 
  // destroy the object to log out
  $fritz = null; 

  // Data
  $datetime     = 'DateTime';
  $dslam_max_dl = 'DSLAM Max DL';
  $dslam_max_ul = 'DSLAM Max UL';
  $dslam_min_dl = 'DSLAM Min DL';
  $dslam_min_ul = 'DLSAM Min UL';
  $vst_kapa_dl  = 'V-Stelle Kapa DL';
  $vst_kapa_ul  = 'V-Stelle Kapa UL';
  $box_kapa_dl  = 'Box Kapa DL';
  $box_kapa_ul  = 'Box Kapa UL';
  $snr_marge_dl = 'SNR-Marge DL';
  $snr_marge_ul = 'SNR-Marge UL';
  $dampfung_dl  = 'Daempfung DL';
  $dampfung_ul  = 'Daempfung UL';
  $box_es       = 'Box ES';
  $box_ses      = 'Box SES';
  $box_crc_1    = 'Box CRC/min';
  $box_crc_15   = 'Box CRC/15min';
  $vst_es       = 'V-Stelle ES';
  $vst_ses      = 'V-Stelle SES';
  $vst_crc_1    = 'V-Stelle CRC/min';
  $vst_crc_15   = 'V-Stelle CRC/15min';
  $ul           = 'UL';
  $dl           = 'DL';
  $ip           = 'IP';
  $dns1         = 'DNS1';
  $dns2         = 'DNS2';
  $dslam        = 'DSLAM';

  // Build header for file
  $header = $datetime.','.         // 0
            $dslam_max_dl.','.     // 1
            $dslam_max_ul.','.     // 2
            $dslam_min_dl.','.     // 3
            $dslam_min_ul.','.     // 4
            $vst_kapa_dl.','.      // 5
            $vst_kapa_ul.','.      // 6
            $box_kapa_dl.','.      // 7
            $box_kapa_ul.','.      // 8
            $snr_marge_dl.','.     // 9
            $snr_marge_ul.','.     // 10
            $dampfung_dl.','.      // 11
            $dampfung_ul.','.      // 12
            $box_es.','.           // 13
            $box_ses.','.          // 14
            $box_crc_1.','.        // 15
            $box_crc_15.','.       // 16
            $vst_es.','.           // 17
            $vst_ses.','.          // 18
            $vst_crc_1.','.        // 19
            $vst_crc_15.','.       // 20
            $ul.','.               // 21
            $dl.','.               // 22
            $ip.','.               // 23
            $dns1.','.             // 24
            $dns2.','.             // 25
            $dslam;                // 26

//################ RegEx ##############################################################################################

  // DSL Stats  
  /*
          1.      [291-297]       `109344`  DSLAM Max DL
          2.      [317-322]       `42000`   DSLAM Max UL
          3.      [417-420]       `720`     DSLAM Min DL
          4.      [440-443]       `368`     DLSAM Min UL
          5.      [535-541]       `107126`  V-Stelle Leitungskapazitaet DL
          6.      [561-566]       `28894`   V-Stelle Leitungskapazitaet UL
          7.      [659-665]       `107126`  Box Leitungskapazitaet DL
          8.      [685-690]       `28894`   Box Leitungskapazitaet UL
  */

  if (preg_match ('/.*?<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>.*?<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>.*?<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>.*?<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>/', $html, $hits))
  {
     $logline .= $hits[1].','.$hits[2].','.$hits[3].','.$hits[4].','.$hits[5].','.$hits[6].','.$hits[7].','.$hits[8].',';
  }
  else
  {
     $logline .= '0,0,0,0,0,0,0,0,';
  }

  // Stoerabstandsmarge + Leitungsdaempfung  
  /*
                1.      [1402-1403]     `4`     Stoerabstandsmarge DL
                2.      [1423-1424]     `4`     Stoerabstandsmarge UL
                3.      [1626-1627]     `9`     Leitungsdaempfung DL
                4.      [1647-1649]     `10`    Leitungsdaempfung UL
  */

  if (preg_match ('/.*?abstandsmarge.*?<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>.*Leitungsd.*?<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>.*/', $html, $hits))
  {
     $logline .= $hits[1].','.$hits[2].','.$hits[3].','.$hits[4].',';
  }
  else
  {
         $logline .= '0,0,0,0,';
  }

  // DSL Fehler  
  /*
                1.      [2623-2625]     `11`    Box ES
                2.      [2646-2647]     `0`     Box SES
                3.      [2668-2672]     `0.63`  Box CRC/min
                4.      [2693-2695]     `18`    Box CRC/15min
                5.      [2765-2766]     `7`     V-Stelle ES
                6.      [2787-2788]     `0`     V-Stelle SES
                7.      [2808-2812]     `0.21`  V-Stelle CRC/min
                8.      [2833-2834]     `2`     V-Stelle CRC/15min
  */

  if (preg_match ('/.*?<td class="c1">FRITZ!Box.*?\n.*?<td class="c2">(.*?)<\/td>\n<td class="c3">(.*?)<\/td>\n<td class="c4">(.*?)<\/td>\n<td class="c5">(.*?)<\/td>.*?\n.*?\n.*?<td class="c1">Vermittlungsstelle.*?\n.*?<td class="c2">(.*?)<\/td>\n<td class="c3">(.*?)<\/td>.*?<td class="c4">(.*?)<\/td>.*?\n<td class="c5">(.*?)<\/td>/', $html, $hits))
  {
     $logline .= $hits[1].','.$hits[2].','.$hits[3].','.$hits[4].','.$hits[5].','.$hits[6].','.$hits[7].','.$hits[8].',';
  }
  else
  {
     $logline .= '0,0,0,0,0,0,0,0,';
  }

  // Upload + Download
/* 

          1.      [359-364]       `30104` Upload
          2.      [503-508]       `52098` Download
  */

  if (preg_match ('/.*?prio_default_bps": "(.*?),.*?\n.*?"ds_current_bps": "(.*?),/', $html, $hits))
  {
     $logline .= (floatval($hits[1])/1000).','.(floatval($hits[2])/1000).',';
  }
  else
  {
         $logline .= '0,0,';
  }

 // IPs
/* 
          1.    [19101-19115]   `79.209.212.237`  IP
          2.    [19242-19257]   `217.237.151.142` DNS1
          3.    [19300-19315]   `217.237.150.188` DNS2
  */

  if (preg_match ('/.*?IP-Adresse: (.*?)<\/span>.*?\n.*?\n.*?\n<td class="tdinfo">(.*?) .*?<br>(.*?) /', $html, $hits))
  {
     $logline .= $hits[1].','.$hits[2].','.$hits[3].',';
  }
  else
  {
     $logline .= '0,0,0,';
  }

  // DSLAM
/* 
					1.	[43-51]	`Broadcom`
					2.	[55-61]	`164.97`
					3.	[65-77]	`B5004244434D`
					4.	[81-89]	`7631302E`
					5.	[93-95]	`00`
  */

  if (preg_match ('/.*?"dslam": "(.*?)<br>(.*?)<br>(.*?)<br>(.*?)<br>(.*?)", /', $html, $hits))
  {
     $logline .= $hits[1].' '.$hits[2].' '.$hits[3].' '.$hits[4].' '.$hits[5];
  }
  else
  {
     $logline .= '0';
  }

//#####################################################################################################################

// Write file
if (file_exists($filename))
{
    $file = fopen($filename,"a");
}
else
{
    $file = fopen($filename,"w");
    fwrite($file, $header."\n");
}

$time = (time()+$time_offset)*1000;

fwrite($file, $time.','.$logline."\n");

fclose($file);

?>