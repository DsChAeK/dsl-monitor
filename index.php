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
  
  $start_datetime = 0;
  $end_datetime = 0;
  $min_datetime = 0;
  $max_datetime = 0;
  
	$logline0 = 'var MyData0 = [';
	$logline1 = 'var MyData1 = [';
	$logline2 = 'var MyData2 = [';
	$logline3 = 'var MyData3 = [';
  $logline4 = 'var MyData4 = [';	
  $logline5 = 'var MyData5 = [';
  $logline6 = 'var MyData6 = [';	
		
	// Time offset in seconds
  $time_offset *= 60*60;
  
  // Load data from logfile
  function Load ($filename, $start, $end)
  {
	  global $logline0;
		global $logline1;
		global $logline2;
		global $logline3;
	  global $logline4;	
	  global $logline5;
	  global $logline6;	
	  
    global $min_datetime;
    global $max_datetime;
    global $start_datetime;
    global $end_datetime;
    
    global $time_offset;
    global $show_last_days;
   
	  $row = 1;		
	  		  
		// format to mikroseconds and set timeoffset
		if ($start != 0)
		{
		  $start = ($start + $time_offset) * 1000;
		}
				
		if ($end != 0)
		{		
		  $end = ($end + $time_offset) * 1000;
		}

		// Open file
		if (($handle = fopen($filename, "r")) !== FALSE) 
		{      
		  // Read header
		  fgetcsv($handle, 0, ",");
	
		  // Read data
		  while (($data = fgetcsv($handle, 0, ",")) !== FALSE) 
		  {	  	
	      if ($row == 1)
	      {	      		        	        
       	  // Select Start
	        if ($start == 0)
	        {	        		      	  
	      	  // Show last days?
	      	  if ($show_last_days == 0)
	      	  {
	      	  	// From beginning
	      	    $start_datetime = $data[0];	      	  		      	  
	      	  }
	      	  else
	      	  {
	      	  	// Last days
	      	    $start_datetime = (time() - (60*60*24*$show_last_days) + $time_offset) * 1000;
	      	  }
	      	}
	      	else
	      	{	    
	      		// User defined  		        	          
	          $start_datetime = $start;	        	        	        
	        }
	      
		      // Select End
	      	if ($end != 0)
	      	{
	      		// User defined
	      		$end_datetime = $end;
	      	}
	      	  
          // Min always the first entry
       	  $min_datetime = $data[0];       	
	      }
	      
	      // Max always the last entry
      	$max_datetime = $data[0];      	   	      	
      	
      	if ($end == 0)
      	{
      		// Set End to Max
      	  $end_datetime = $max_datetime;
      	}
      	 
      	// Row count
      	$row++; 
      	
        // Skip entries out of range
    		if ((floatval($data[0]) < floatval($start_datetime)) || 
    		    (floatval($data[0]) > floatval($end_datetime)))
    		{     
    			continue;	      			
    		}
      	     	   		      
	      // Stoerabstandsmarge (SNR)
	      $logline0 .= '['.$data[0].','.$data[9].'],';   
	      $logline1 .= '['.$data[0].','.$data[10].'],';
	      
	      // Leitungskapazitaet  	      
	      $logline2 .= '['.$data[0].','.$data[7].'],';   
	      $logline3 .= '['.$data[0].','.$data[8].'],';
	      
	      // Disconnects (-> No IP)
	      if (strlen ($data[23]) > 1)
	      {
	        $logline4 .= '['.$data[0].',1],';
	      }
	      else
	      {
	       	$logline4 .= '['.$data[0].',0],';
	      }
	      
	      // Auslastung
	      $logline5 .= '['.$data[0].','.$data[22].'],';   
				$logline6 .= '['.$data[0].','.$data[21].'],';			          				
	    }
	       
	    fclose($handle);
	
	    $logline0 .= '];';
	    $logline1 .= '];';
	    $logline2 .= '];';
	    $logline3 .= '];';
	    $logline4 .= '];';
	    $logline5 .= '];';
	    $logline6 .= '];';	    
	   }   
	 }
?>

<?php 

  Load($filename, strtotime(htmlspecialchars($_POST['start'])), strtotime(htmlspecialchars($_POST['end'])));

/* 
  // Debug Only
	echo "file: ".$filename.'<br>';
	echo "start post: ".strtotime(htmlspecialchars($_POST['start'])).'<br>';
	echo "end post: ".strtotime(htmlspecialchars($_POST['end'])).'<br>';
  echo "start: ".$start_datetime.'<br>';
	echo "end: ".$end_datetime.'<br>';    
	echo "min: ".$min_datetime.'<br>';
	echo "max: ".$max_datetime.'<br>';      
	echo "time_offset: ".$time_offset.'<br>';      
*/
?> 
	
<html>	
	<head>
		<link rel="shortcut icon" href="favicon.ico" />
	  <meta http-equiv="content-type" content="text/html; charset=UTF-8">

	  <title>DSL Monitor</title> 
	    
	  <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>	  
	  <script type="text/javascript" src="js/chart.js"></script>
	  
	  <script type="text/javascript">  
     <?php	
      echo $logline0;
      echo $logline1;  
	   ?>	   
	  </script>
	  
    <script type="text/javascript">
     <?php				
      echo $logline2;
      echo $logline3;
	   ?>	   
	  </script>
	  
    <script type="text/javascript">
     <?php
			 echo $logline4;
	   ?>	   
	  </script>	  
	  	  
    <script type="text/javascript">
     <?php
      echo $logline5;
      echo $logline6;
	   ?>		   
	  </script>	  	  
	  
		<!-- Charts -->
		<script src="http://code.highcharts.com/highcharts.js"></script>		
	  <!--<script src="http://code.highcharts.com/modules/exporting.js"></script> -->
    
		<!-- Include Required Prerequisites -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />
		 
		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
		
		<!-- Individual Style -->	
		<style type="text/css">.daterangepicker .calendar{
			                       max-width: inherit;
			                     }
			                     
			                     input[name="daterange"]{
			                     	 cursor: pointer;
			                     }
			                     
			                     #form{
			                     	position: absolute;
			                     	top: 20px;
			                     	z-index: 1;
			                     }			                     
	  </style>
	</head>

	<body>
		<br>
		<form id="form" action="" method="post">			
			<input type="hidden" name="start" value="0" />
			<input type="hidden" name="end" value="0" />
			<input type="text" name="daterange" style="width: 250px; margin-left: 40px" />			
		</form> 		
				
		<script type="text/javascript">
			$(function() 
			{
			  $('input[name="daterange"]').daterangepicker({
			  	 "timePicker": true,
			  	 "locale": {"format": 'DD.MM.YYYY HH:mm'},
	         "timePicker24Hour": true,
	         "startDate": <?php echo '"'.gmdate("d.m.Y G:i", $start_datetime/1000).'"' ?>,
	         "endDate": <?php echo '"'.gmdate("d.m.Y G:i", $end_datetime/1000).'"' ?>,
	         "minDate": <?php echo '"'.gmdate("d.m.Y G:i", $min_datetime/1000).'"' ?>,
	         "maxDate": <?php echo '"'.gmdate("d.m.Y G:i", $max_datetime/1000).'"' ?>},
	         function(start, end, label)
	         {
						 //alert("A new date range was chosen: " + start.format('DD.MM.YYYY HH:mm') + ' to ' + end.format('DD.MM.YYYY HH:mm'));
						 $('input[name="start"]').val(start);
						 $('input[name="end"]').val(end);
				     				     
						 document.getElementById("form").submit();					 						 
					 });			 
			});
		</script>
	  
		<div id="dsl1" style="min-width: 310px; height: 270px; margin: 0 auto; margin-top:-17"></div>
		<div id="dsl2" style="min-width: 310px; height: 210px; margin: 0 auto"></div>
	  <div id="dsl3" style="min-width: 310px; height: 210px; margin: 0 auto"></div>
	  <div id="dsl4" style="min-width: 310px; height: 210px; margin: 0 auto"></div>
	  
	</body>

</html>