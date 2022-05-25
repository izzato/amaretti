@extends('layouts.dashboard')

@section('content')
<div class="am-content">
    <div class="main-content no-header">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <div class="row">
          <div class="col-md-4">
            <div class="widget widget-pie">
              <div class="widget-head"><span class="title">Monthly Visits</span></div>
              <div class="row chart-container">
                <div class="col-md-6">
                  <div id="widget-top-2" class="chart"></div>
                </div>
                <div class="col-md-6">
                  <div class="legend"></div>
                </div>
              </div>
              <div class="row chart-info">
                <div class="col-xs-4"><span class="title">New Visitors</span><span data-toggle="counter" data-end="0" data-suffix="%" class="number">0%</span></div>
                <div class="col-xs-4"><span class="title">Conversions</span><span data-toggle="counter" data-end="0" class="number">0</span></div>
                <div class="col-xs-4"><span class="title">Bounce Rate</span><span data-toggle="counter" data-end="0" data-suffix="%" class="number">0%</span></div>
              </div>
            </div>
          </div>
            <div class="col-md-3">
          <div class="widget widget-fullwidth widget-small">
                                  <div class="widget-head">
                                    <div class="tools"><span class="value">{{ App\Project::whereNotNull('published_at')->count() }}</span></div><span class="title">Published projects</span>
                                  </div>
                                  <div class="chart-container">
                                    <div id="linechart-mini1" style="height: 92px;"></div>
                                  </div>
                                </div>
                                <div class="widget widget-fullwidth widget-small">
                                  <div class="widget-head">
                                    <div class="tools"><span class="value">{{ App\User::count() }}</span></div><span class="title">Active users</span>
                                  </div>
                                  <div class="chart-container">
                                    <div id="barchart-mini1" style="height: 92px;"></div>
                                  </div>
                                </div>
                                </div>
          <div class="col-md-5">
            <div class="widget widget-calendar">
              <div class="cal-container">
                <div class="cal-notes"><span class="day">Thursday</span><span class="date">September 24</span><span class="title">Scheduled for publishing{{--<span class="icon s7-plus">--}}</span></span>
                  <ul>
                    @foreach(App\Proposal::whereDate('published_at', '>=', now())->get()->merge(App\Project::whereDate('published_at', '>=', now())->get()) as $item)
                    <li><a href="{{ route(str_plural( strtolower( class_basename($item) )) . '.show', $item) }}"><span class="hour">{{ $item->published_at->diffForHumans() }}</span><span class="event-name">{{ $item->title }}</span></a></li>
                    @endforeach
                  </ul>
                </div>
                <div class="cal-calendar">
                  <div class="ui-datepicker"></div>
                </div>
              </div>
            </div>
            </div>
        </div>

        </div>
    </div>
@endsection

@section('body.bottom')
<script src="/lib/countup/countUp.min.js" type="text/javascript"></script>
<script src="/lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/lib/jquery-flot/jquery.flot.js" type="text/javascript"></script>
<script src="/lib/jquery-flot/jquery.flot.pie.js" type="text/javascript"></script>
<script src="/lib/jquery-flot/jquery.flot.resize.js" type="text/javascript"></script>
<script>
    //Counter
    // function counter(){

    //   $('[data-toggle="counter"]').each(function(i, e){
    //     var _el       = $(this);
    //     var prefix    = '';
    //     var suffix    = '';
    //     var start     = 0;
    //     var end       = 0;
    //     var decimals  = 0;
    //     var duration  = 2.5;

    //     if( _el.data('prefix') ){ prefix = _el.data('prefix'); }

    //     if( _el.data('suffix') ){ suffix = _el.data('suffix'); }

    //     if( _el.data('start') ){ start = _el.data('start'); }

    //     if( _el.data('end') ){ end = _el.data('end'); }

    //     if( _el.data('decimals') ){ decimals = _el.data('decimals'); }

    //     if( _el.data('duration') ){ duration = _el.data('duration'); }

    //     var count = new CountUp(_el.get(0), start, end, decimals, duration, { 
    //       suffix: suffix,
    //       prefix: prefix,
    //     });

    //     count.start();
    //   });
    // }

    //Top pie widget 2
    function widget_top_2(){
	    
	    var data = [
	    	{ label: "Direct Access", data: 20 },
	    	{ label: "Advertisment", data: 15 },
	    	{ label: "Facebook", data: 15 },
	    	{ label: "Twitter", data: 30 },
	    	{ label: "Google Plus", data: 20 }
	    ];

      var color1 = App.color.alt2;
      var color2 = App.color.alt4;
      var color3 = App.color.alt3;
      var color4 = App.color.alt1;
      var color5 = tinycolor( App.color.primary ).lighten( 5 ).toString();

	    var legendContainer = $("#widget-top-2").parent().next().find(".legend");

	    $.plot('#widget-top-2', data, {
		    series: {
	        pie: {
	          innerRadius: 0.5,
	          show: true,
	          highlight: {
							opacity: 0.1
						}
		      }
		    },
		    grid:{
		    	hoverable: true
		    },
		    legend:{
		    	container: legendContainer
		    },
		    colors: [color1, color2, color3, color4, color5]
			});
    }

    // Calendar Widget
    function calendar_widget(){
    	var widget = $(".widget-calendar");
    	var calNotes = $(".cal-notes", widget);
    	var calNotesDay = $(".day", calNotes);
    	var calNotesDate = $(".date", calNotes);

    	//Calculate the weekday name
    	var d = new Date();
			var weekday = new Array(7);
			weekday[0]=  "Sunday";
			weekday[1] = "Monday";
			weekday[2] = "Tuesday";
			weekday[3] = "Wednesday";
			weekday[4] = "Thursday";
			weekday[5] = "Friday";
			weekday[6] = "Saturday";

			var weekdayName = weekday[d.getDay()];

			calNotesDay.html( weekdayName );

			//Calculate the month name
			var month = new Array();
			month[0] = "January";
			month[1] = "February";
			month[2] = "March";
			month[3] = "April";
			month[4] = "May";
			month[5] = "June";
			month[6] = "July";
			month[7] = "August";
			month[8] = "September";
			month[9] = "October";
			month[10] = "November";
			month[11] = "December";

			var monthName = month[d.getMonth()];
			var monthDay = d.getDate();

			calNotesDate.html( monthName + " " + monthDay);

      if (typeof jQuery.ui != 'undefined') {
        $( ".ui-datepicker" ).datepicker({
        	onSelect: function(s, o){
        		var sd = new Date(s);
        		var weekdayName = weekday[sd.getDay()];
        		var monthName = month[sd.getMonth()];
						var monthDay = sd.getDate();

						calNotesDay.html( weekdayName );
						calNotesDate.html( monthName + " " + monthDay);
        	}
        });
      }
    }

    //Mini widget 1
    function line_chart2(){

      var data = [
        [1, 20],
        [2, 60],
        [3, 35],
        [4, 70],
        [5, 45]
      ];

      var data2 = [
        [1, 60],
        [2, 20],
        [3, 65],
        [4, 35],
        [5, 65]
      ];

      var color = App.color.alt2;

      var plot_statistics = $.plot("#linechart-mini1", 
        [
        {
          data: data, 
          canvasRender: true
        },
        {
          data: data2, 
          canvasRender: true
        }
        ], {
        series: {
          lines: {
            show: true,
            lineWidth: 0, 
            fill: true,
            fillColor: { colors: [{ opacity: 0.7 }, { opacity: 0.7}] }
          },
          fillColor: "rgba(0, 0, 0, 1)",
          shadowSize: 0,
          curvedLines: {
            apply: true,
            active: true,
            monotonicFit: true
          }
        },
        legend:{
          show: false
        },
        grid: {
          show:false,
          hoverable: true,
          clickable: true
        },
        colors: [color, color],
        xaxis: {
          autoscaleMargin: 0,
          ticks: 11,
          tickDecimals: 0
        },
        yaxis: {
          autoscaleMargin: 0.5,
          ticks: 5,
          tickDecimals: 0
        }
      });
    }

    //Mini widget 2
    function bar_chart(){

      var color1 = tinycolor( App.color.primary ).lighten( 23 ).toString();
      var color2 = tinycolor( App.color.primary ).brighten( 5 ).toString();

      var plot_statistics = $.plot($("#barchart-mini1"), [
        {
          data: [
            [0, 15], [1, 15], [2, 19], [3, 28], [4, 30], [5, 37], [6, 35], [7, 38], [8, 48], [9, 43], [10, 38], [11, 32], [12, 38]
          ],
          label: "Page Views"
        },
        {
          data: [
            [0, 7], [1, 10], [2, 15], [3, 23], [4, 24], [5, 29], [6, 25], [7, 33], [8, 35], [9, 38], [10, 32], [11, 27], [12, 32]
          ],
          label: "Unique Visitor"
        }
      ], {
        series: {
          bars: {
            align: 'center',
            show: true,
            lineWidth: 1, 
            barWidth: 0.8, 
            fill: true,
            fillColor: {
              colors: [{
                opacity: 1
              }, {
                opacity: 1
              }
              ]
            } 
          },
          shadowSize: 0
        },
        legend:{
          show: false
        },
        grid: {
          margin: 0,
          show: false,
          labelMargin: 10,
          axisMargin: 500,
          hoverable: true,
          clickable: true,
          tickColor: "rgba(0,0,0,0.15)",
          borderWidth: 0
        },
        colors: [color1, color2],
        xaxis: {
          ticks: 11,
          tickDecimals: 0
        },
        yaxis: {
          autoscaleMargin: 0.5,
          ticks: 4,
          tickDecimals: 0
        }
      });
    }

    //CounterUp Init
    // counter();

    //Row 1
	    // widget_top_1();
	    widget_top_2();
	    // widget_top_3();

	    //Sparklines
    //   var spk1_color = App.color.alt2;
    //   var spk2_color = tinycolor( App.color.primary ).lighten( 5 ).toString();
	//     $("#spk1").sparkline([2,4,3,6,7,5,8,9,4,2,10,], { type: 'bar', width: '80px', height: '30px', barColor: spk1_color});
	//     $("#spk2").sparkline([5,3,5,6,5,7,4,8,6,9,8,], { type: 'bar', width: '80px', height: '30px', barColor: spk2_color});

	//   //Row 2
	  	calendar_widget();
	  	// line_chart1();

	//   //Row 3
      line_chart2();
      bar_chart();
    //   world_map();
    //   radar_chart();

</script>
@endsection