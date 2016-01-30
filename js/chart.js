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

$(function () 
{
	  var chart1;
    var chart2;
    var chart3;
    var chart4;
    
    var defaultTickInterval = 5;
    var currentTickInterval = defaultTickInterval;

    $(document).ready(function() 
    {    				
       function unzoom() 
       {
             chart1.options.chart.isZoomed = false;
             chart2.options.chart.isZoomed = false;
             chart3.options.chart.isZoomed = false;
             chart4.options.chart.isZoomed = false;
            
            chart1.xAxis[0].setExtremes(null, null);
            chart2.xAxis[0].setExtremes(null, null);
            chart3.xAxis[0].setExtremes(null, null);
            chart4.xAxis[0].setExtremes(null, null);
        }

        //catch mousemove event and have all 3 charts' crosshairs move along indicated values on x axis
        function syncronizeCrossHairs(chart) 
        {
            var container = $(chart.container),
                offset = container.offset(),
                x, y, isInside, report;

            container.mousemove(function(evt) 
            {

                x = evt.clientX - chart.plotLeft - offset.left;
                y = evt.clientY - chart.plotTop - offset.top;
                //var xAxis = chart.xAxis[0];
                //remove old plot line and draw new plot line (crosshair) for this chart
                var xAxis1 = chart1.xAxis[0];
                xAxis1.removePlotLine("myPlotLineId");
                xAxis1.addPlotLine({
                    value: chart.xAxis[0].translate(x, true),
                    width: 1,
                    color: 'red',
                    marker: false,
                    shadow : false,
                    //dashStyle: 'dash',                   
                    id: "myPlotLineId"
                });
                
                //remove old crosshair and draw new crosshair on chart2
                var xAxis2 = chart2.xAxis[0];
                xAxis2.removePlotLine("myPlotLineId");
                xAxis2.addPlotLine({
                    value: chart.xAxis[0].translate(x, true),
                    width: 1,
                    color: 'red',
                    marker: false,
                    shadow : false,
                    //dashStyle: 'dash',                   
                    id: "myPlotLineId"
                });

                var xAxis3 = chart3.xAxis[0];
                xAxis3.removePlotLine("myPlotLineId");
                xAxis3.addPlotLine({
                    value: chart.xAxis[0].translate(x, true),
                    width: 1,
                    color: 'red',
                    marker: false,
                    shadow : false,
                    //dashStyle: 'dash',                   
                    id: "myPlotLineId"
                });
                
               var xAxis4 = chart4.xAxis[0];
                xAxis4.removePlotLine("myPlotLineId");
                xAxis4.addPlotLine({
                    value: chart.xAxis[0].translate(x, true),
                    width: 1,
                    color: 'red',
                    marker: false,
                    shadow : false,
                    //dashStyle: 'dash',                   
                    id: "myPlotLineId"
                });                

                //if you have other charts that need to be syncronized - update their crosshair (plot line) in the same way in this function.                   
            });
        }

    //compute a reasonable tick interval given the zoom range -
    //have to compute this since we set the tickIntervals in order
    //to get predictable synchronization between multiple charts with
    //different data.
    function computeTickInterval(xMin, xMax) 
    {
        var zoomRange = xMax - xMin;
        
        if (zoomRange <= 2)
            currentTickInterval = 0.5;
        if (zoomRange < 20)
            currentTickInterval = 1;
        else if (zoomRange < 100)
            currentTickInterval = 5;
    }

    //explicitly set the tickInterval for the charts - based on
    //selected range
    function setTickInterval(event) 
    {
        var xMin = event.xAxis[0].min;
        var xMax = event.xAxis[0].max;
        computeTickInterval(xMin, xMax);

        chart1.xAxis[0].options.tickInterval = currentTickInterval;
        chart1.xAxis[0].isDirty = true;
        chart2.xAxis[0].options.tickInterval = currentTickInterval;
        chart2.xAxis[0].isDirty = true;
        chart3.xAxis[0].options.tickInterval = currentTickInterval;
        chart3.xAxis[0].isDirty = true;
        chart4.xAxis[0].options.tickInterval = currentTickInterval;
        chart4.xAxis[0].isDirty = true;        
    }

    //reset the extremes and the tickInterval to default values
    function unzoom() 
    {
        chart1.xAxis[0].options.tickInterval = defaultTickInterval;
        chart1.xAxis[0].isDirty = true;
        chart2.xAxis[0].options.tickInterval = defaultTickInterval;
        chart2.xAxis[0].isDirty = true;
        chart3.xAxis[0].options.tickInterval = defaultTickInterval;
        chart3.xAxis[0].isDirty = true;
        chart4.xAxis[0].options.tickInterval = defaultTickInterval;
        chart4.xAxis[0].isDirty = true;
            
        chart1.xAxis[0].setExtremes(null, null);
        chart2.xAxis[0].setExtremes(null, null);
        chart3.xAxis[0].setExtremes(null, null);
        chart4.xAxis[0].setExtremes(null, null);
    }
    
    $(document).ready(function() 
    {
    	  $('#btn').click(function()
    	  {
           unzoom();
        });
                
        var myPlotLineId = "myPlotLine";
                
        // ### Chart1
        chart1 = new Highcharts.Chart({
                    chart: {
                        renderTo: 'dsl1',
                        type: 'line',
                        zoomType: 'x',
                        marker: false,
                        shadow : false,
                        //x axis only
                        isZoomed:false,           
                    },
        title: {
            text: 'DSL Monitor v2.0'
        },
        scrollbar: {
                liveRedraw: false
            },
        subtitle: {
            text: 'Click and drag in the plot area to zoom in'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Time'
            },
            //tickInterval:5,
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            events: {
                afterSetExtremes: function() {
                    if (!this.chart.options.chart.isZoomed) 
                    {
                        var xMin = this.chart.xAxis[0].min;
                        var xMax = this.chart.xAxis[0].max;
                        var zmRange = computeTickInterval(xMin, xMax);
                        chart1.xAxis[0].options.tickInterval =zmRange;
                        chart1.xAxis[0].isDirty = true;
                        chart2.xAxis[0].options.tickInterval = zmRange;
                        chart2.xAxis[0].isDirty = true;
                        chart3.xAxis[0].options.tickInterval = zmRange;
                        chart3.xAxis[0].isDirty = true;
                        chart4.xAxis[0].options.tickInterval = zmRange;
                        chart4.xAxis[0].isDirty = true;                        
                        
                       chart2.options.chart.isZoomed = true;
                       chart3.options.chart.isZoomed = true;
                       chart4.options.chart.isZoomed = true;
                       chart2.xAxis[0].setExtremes(xMin, xMax, true);                    
                       chart3.xAxis[0].setExtremes(xMin, xMax, true);
                       chart4.xAxis[0].setExtremes(xMin, xMax, true);
                       chart2.options.chart.isZoomed = false;
                       chart3.options.chart.isZoomed = false;
                       chart4.options.chart.isZoomed = false;
                    
                    }
                }
            }            
        },
        yAxis: {
            title: {
                text: 'dB'
            },
            min: 0
        },
        navigator: {
            enabled: false
        },
        rangeSelector: {
            selected: 4,
            inputEnabled: false,
            buttonTheme: {
                visibility: 'hidden'
            },
            labelStyle: {
                visibility: 'hidden'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e.%b %H:%M}, {point.y:.1f} dB'
        },

        plotOptions: {        	  
        	  column: {
        	  	animation: false
        	  },
            line: {
                marker: {
                    enabled: false
                }
            }
        },

        series: [{            
						name: "Stoerabstandsmarge DL",
            // Define the data points. All series have a dummy year
            // of 1970/71 in order to be compared on the same x axis. Note
            // that in JavaScript, months start at 0 for January, 1 for February etc.
            data: MyData0
        }, {
            name: "Stoerabstandsmarge UL",
            data: MyData1
        }]},function(chart) { //add this function to the chart definition to get synchronized crosshairs
                    //this function needs to be added to each syncronized chart 
                    syncronizeCrossHairs(chart);
        });   

        // ### Chart2
        chart2 = new Highcharts.Chart({
                    chart: {
                        renderTo: 'dsl2',
                        type: 'line',
                        zoomType: 'x',
                        marker: false,
                        shadow : false,
                        //x axis only
                        isZoomed:false
                    },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Time'
            },
//            tickInterval:5,
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            events: {
                afterSetExtremes: function() {
                    if (!this.chart.options.chart.isZoomed) 
                    {
                       var xMin = this.chart.xAxis[0].min;
                       var xMax = this.chart.xAxis[0].max;
                       var zmRange = computeTickInterval(xMin, xMax);
                       chart1.xAxis[0].options.tickInterval =zmRange;
                       chart1.xAxis[0].isDirty = true;
                       chart2.xAxis[0].options.tickInterval = zmRange;
                       chart2.xAxis[0].isDirty = true;
                       chart3.xAxis[0].options.tickInterval = zmRange;
                       chart3.xAxis[0].isDirty = true;
                       chart4.xAxis[0].options.tickInterval = zmRange;
                       chart4.xAxis[0].isDirty = true; 
                        
                       chart1.options.chart.isZoomed = true;
                       chart3.options.chart.isZoomed = true;
                       chart4.options.chart.isZoomed = true;
                       chart1.xAxis[0].setExtremes(xMin, xMax, true);                    
                       chart3.xAxis[0].setExtremes(xMin, xMax, true);
                       chart4.xAxis[0].setExtremes(xMin, xMax, true);
                       chart1.options.chart.isZoomed = false;
                       chart3.options.chart.isZoomed = false;
                       chart4.options.chart.isZoomed = false;                    
                    }
                }
            }             
        },
        yAxis: {
            title: {
                text: 'kbit/s'
            },
            min: 0
        },
        navigator: {
            enabled: false
        },
        rangeSelector: {
            selected: 4,
            inputEnabled: false,
            buttonTheme: {
                visibility: 'hidden'
            },
            labelStyle: {
                visibility: 'hidden'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e.%b %H:%M}, {point.y:.1f} kbit/s'
        },

        plotOptions: {
        	  column: {
        	  	animation: false
        	  },
            line: {
                marker: {
                    enabled: false
                }
            }
        },

        series: [{            
						name: "Leitungskapazitaet DL",
            // Define the data points. All series have a dummy year
            // of 1970/71 in order to be compared on the same x axis. Note
            // that in JavaScript, months start at 0 for January, 1 for February etc.
            data: MyData2
        }, {
            name: "Leitungskapazitaet UL",
            data: MyData3
        }]
        },function(chart) { //add this function to the chart definition to get synchronized crosshairs
                    //this function needs to be added to each syncronized chart 
                    syncronizeCrossHairs(chart);
        });
   
        // ### Chart3
        chart3 = new Highcharts.Chart({
                    chart: {
                        renderTo: 'dsl3',
                        type: 'line',
                        zoomType: 'x',
                        marker: false,
                        shadow : false,
                        //x axis only
                        isZoomed:false
                    },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Time'
            },            
//            tickInterval:5,
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            events: {
                afterSetExtremes: function() {
                    if (!this.chart.options.chart.isZoomed) 
                    {
                        var xMin = this.chart.xAxis[0].min;
                        var xMax = this.chart.xAxis[0].max;
                        var zmRange = computeTickInterval(xMin, xMax);
                        chart1.xAxis[0].options.tickInterval =zmRange;
                        chart1.xAxis[0].isDirty = true;
                        chart2.xAxis[0].options.tickInterval = zmRange;
                        chart2.xAxis[0].isDirty = true;
                        chart3.xAxis[0].options.tickInterval = zmRange;
                        chart3.xAxis[0].isDirty = true;
                        chart4.xAxis[0].options.tickInterval = zmRange;
                        chart4.xAxis[0].isDirty = true;
                        
                        
                        chart1.options.chart.isZoomed = true;
                        chart2.options.chart.isZoomed = true;
                        chart4.options.chart.isZoomed = true;
                        chart1.xAxis[0].setExtremes(xMin, xMax, true);                    
                        chart2.xAxis[0].setExtremes(xMin, xMax, true);
                        chart4.xAxis[0].setExtremes(xMin, xMax, true);
                        chart1.options.chart.isZoomed = false;
                        chart2.options.chart.isZoomed = false;
                        chart4.options.chart.isZoomed = false;                    
                    }
                }
            }             
        },
        yAxis: {
            title: {
                text: 'Status'
            },
            min: 0
        },
        navigator: {
            enabled: false
        },
        rangeSelector: {
            selected: 4,
            inputEnabled: false,
            buttonTheme: {
                visibility: 'hidden'
            },
            labelStyle: {
                visibility: 'hidden'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e.%b %H:%M}, {point.y:.1f}'
        },

        plotOptions: {
        	  column: {
        	  	animation: false
        	  },
            line: {
                marker: {
                    enabled: false
                }
            }
        },

        series: [{            
						name: "Disconnects",
            data: MyData4
        }]
        },function(chart) { //add this function to the chart definition to get synchronized crosshairs
                    //this function needs to be added to each syncronized chart 
                    syncronizeCrossHairs(chart);
        });   
    
        // ### Chart4
        chart4 = new Highcharts.Chart({
                    chart: {
                        renderTo: 'dsl4',
                        type: 'line',
                        zoomType: 'x',
                        marker: false,
                        shadow : false,
                        isZoomed:false
                    },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Time'
            },
            //tickInterval:5,
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            events: {
                afterSetExtremes: function() {
                    if (!this.chart.options.chart.isZoomed) 
                    {
                        var xMin = this.chart.xAxis[0].min;
                        var xMax = this.chart.xAxis[0].max;
                        var zmRange = computeTickInterval(xMin, xMax);
                        chart1.xAxis[0].options.tickInterval =zmRange;
                        chart1.xAxis[0].isDirty = true;
                        chart2.xAxis[0].options.tickInterval = zmRange;
                        chart2.xAxis[0].isDirty = true;
                        chart3.xAxis[0].options.tickInterval = zmRange;
                        chart3.xAxis[0].isDirty = true;
                        chart4.xAxis[0].options.tickInterval = zmRange;
                        chart4.xAxis[0].isDirty = true;
                        
                        chart1.options.chart.isZoomed = true;
                        chart2.options.chart.isZoomed = true;
                        chart3.options.chart.isZoomed = true;
                        chart1.xAxis[0].setExtremes(xMin, xMax, true);                    
                        chart2.xAxis[0].setExtremes(xMin, xMax, true);
                        chart3.xAxis[0].setExtremes(xMin, xMax, true);
                        chart1.options.chart.isZoomed = false;
                        chart2.options.chart.isZoomed = false;
                        chart3.options.chart.isZoomed = false;                    
                    }
                }
            }            
        },
        yAxis: {
            title: {
                text: 'kb/s'
            },
            min: 0
        },
        navigator: {
            enabled: false
        },
        rangeSelector: {
            selected: 4,
            inputEnabled: false,
            buttonTheme: {
                visibility: 'hidden'
            },
            labelStyle: {
                visibility: 'hidden'
            }
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e.%b %H:%M}, {point.y:.1f} kb/s'
        },

        plotOptions: {
        	  column: {
        	  	animation: false
        	  },
            line: {
                marker: {
                    enabled: false
                }
            }
        },

        series: [{            
						name: "Auslastung DL",
            // Define the data points. All series have a dummy year
            // of 1970/71 in order to be compared on the same x axis. Note
            // that in JavaScript, months start at 0 for January, 1 for February etc.
            data: MyData5
        }, {
            name: "Auslastung UL",
            data: MyData6
        }]
      },function(chart) { //add this function to the chart definition to get synchronized crosshairs
                    //this function needs to be added to each syncronized chart 
                    syncronizeCrossHairs(chart);
        });        
     });        
  });    
});
