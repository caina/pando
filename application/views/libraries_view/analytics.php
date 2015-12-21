               
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable(
    	 <?php  echo @$analytics_json?>
    );

    var options = {
      title: 'Dados de Acesso do website',
      curveType: 'function',
    legend: { position: 'bottom' }
    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

    chart.draw(data, options);
  }
</script>
				





<?php if($analytics): ?>
	<div class="row">
		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored emerald-bg">
				<i class="fa fa-envelope"></i>
				<span class="headline">Taxa de rejeição</span>
				<span class="value"><?php echo number_format(@$analytics["summary"]->metrics->exitRate,2) ?>%</span>
			</div>
		</div>

		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored red-bg">
				<i class="fa fa-money"></i>
				<span class="headline">Tempo total no website</span>
				<span class="value"><?php echo gmdate("H:i:s",@$analytics["summary"]->metrics->timeOnPage) ?></span>
			</div>
		</div>

		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored green-bg">
				<i class="fa fa-user"></i>
				<span class="headline">Novos Usuários</span>
				<span class="value"><?php echo @$analytics["summary"]->metrics->newUsers ?></span>
			</div>
		</div>

		<div class="col-lg-3 col-sm-6 col-xs-12">
			<div class="main-box infographic-box colored purple-bg">
				<i class="fa fa-globe"></i>
				<span class="headline">Acessos</span>
				<span class="value"><?php echo @$analytics["summary"]->metrics->users ?></span>
			</div>
		</div>
	</div>
							<div class="row">
								<div class="col-md-12">
									<div class="main-box">
										<header class="main-box-header clearfix">
											<h2 class="pull-left">Relatório das datas:	<?php echo date("d/m/Y",strtotime($analytics["summary"]->startDate)) ?> - <?php echo date("d/m/Y",strtotime($analytics["summary"]->endDate)) ?></h2>
										</header>
										
										<div class="main-box-body clearfix">
											<div class="row">
												<div class="col-md-9">
													<div id="chart_div"  style="height: 240px; padding: 0px; position: relative;"></div>
												</div>
												<div class="col-md-3">
													<ul class="graph-stats">
														<li>
															<div class="clearfix">
																<div class="title pull-left">
																	Earnings
																</div>
																<div class="value pull-right" title="10% down" data-toggle="tooltip">
																	&dollar;94.382 <i class="fa fa-level-down red"></i> 
																</div>
															</div>
															<div class="progress">
																<div style="width: 69%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="69" role="progressbar" class="progress-bar">
																	<span class="sr-only">69% Complete</span>
																</div>
															</div>
														</li>
														<li>
															<div class="clearfix">
																<div class="title pull-left">
																	Orders
																</div>
																<div class="value pull-right" title="24% up" data-toggle="tooltip">
																	3.930 <i class="fa fa-level-up green"></i> 
																</div>
															</div>
															<div class="progress">
																<div style="width: 42%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="42" role="progressbar" class="progress-bar progress-bar-danger">
																	<span class="sr-only">42% Complete</span>
																</div>
															</div>
														</li>
														<li>
															<div class="clearfix">
																<div class="title pull-left">
																	New Clients
																</div>
																<div class="value pull-right" title="8% up" data-toggle="tooltip">
																	894 <i class="fa fa-level-up green"></i> 
																</div>
															</div>
															<div class="progress">
																<div style="width: 78%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="78" role="progressbar" class="progress-bar progress-bar-success">
																	<span class="sr-only">78% Complete</span>
																</div>
															</div>
														</li>
														<li>
															<div class="clearfix">
																<div class="title pull-left">
																	Visitors
																</div>
																<div class="value pull-right" title="17% down" data-toggle="tooltip">
																	824.418 <i class="fa fa-level-down red"></i> 
																</div>
															</div>
															<div class="progress">
																<div style="width: 94%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="94" role="progressbar" class="progress-bar progress-bar-warning">
																	<span class="sr-only">94% Complete</span>
																</div>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>	
								</div>
							</div>  
<?php endif; ?>
      
              
              			