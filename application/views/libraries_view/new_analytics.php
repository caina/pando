<div class="col-md-12">
	<div class="row">
		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored emerald-bg">
				<i class="fa fa-plus"></i>
				<span class="headline">Novos visitantes</span>
				<span class="value"><?php echo round($report->new_users_percentage,2) ?>%</span>
			</div>
		</div>

		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored green-bg">
				<i class="fa fa-clock-o"></i>
				<span class="headline">Tempo total no site</span>
				<span class="value"><?php echo $report->time_on_page ?></span>
			</div>
		</div>

		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored red-bg">
				<i class="fa fa-user"></i>
				<span class="headline">Taxa de rejeição</span>
				<span class="value"><?php echo round($report->exit_rate,2) ?>%</span>
			</div>
		</div>

		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored purple-bg">
				<i class="fa fa-globe"></i>
				<span class="headline">Total Acessos</span>
				<span class="value"><?php echo $report->total_access ?></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2 class="pull-left">Acessos por dia de: <?php echo date('d/m/Y', strtotime('-31 days')) ?> até: <?php echo date('d/m/Y')?></h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="row">
						<div class="col-md-12">
							<div id="graph-bar" style="height: 240px; padding: 0px; position: relative;"></div>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		
	    //CHARTS
	    function gd(year, day, month) {
			return new Date(year, month - 1, day).getTime();
		}
		
		if ($('#graph-bar').length) {
			
			var data1 = [];
			var data2 = [];

			<?php foreach($report->rows as $r): ?>
				data1.push([gd(<?php echo $r["year"].",".$r["day"].",".@$r["month"] ?>),<?php echo $r[1] ?>]);
				data2.push([gd(<?php echo $r["year"].",".$r["day"].",".@$r["month"] ?>),<?php echo $r[2] ?>]);
			<?php endforeach; ?>
			
			
			var series = new Array();

			series.push({
				data: data1,
				bars: {
					show : true,
					barWidth: 24 * 60 * 60 * 660,
					lineWidth: 1,
					fill: 1,
					align: 'center'
				},
				label: 'Acessos'
			});
			series.push({
				data: data2,
				color: '#e84e40',
				lines: {
					show : true,
					lineWidth: 3,
				},
				points: { 
					fillColor: "#e84e40", 
					fillColor: '#ffffff', 
					pointWidth: 1,
					show: true 
				},
				label: 'Novos visitantes'
			});

			$.plot("#graph-bar", series, {
				colors: ['#03a9f4', '#f1c40f', '#2ecc71', '#3498db', '#9b59b6', '#95a5a6'],
				grid: {
					tickColor: "#f2f2f2",
					borderWidth: 0,
					hoverable: true,
					clickable: true
				},
				legend: {
					noColumns: 1,
					labelBoxBorderColor: "#000000",
					position: "ne"       
				},
				shadowSize: 0,
				xaxis: {
					mode: "time",
					tickSize: [1, "day"],
					tickLength: 0,
					// axisLabel: "Date",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 9,
					axisLabelFontFamily: 'Open Sans, sans-serif',
					axisLabelPadding: 10
				}
			});

			
		}
	    
		


	});
	</script>