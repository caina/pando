<input type="hidden" id="ajax_url" value="<?php echo site_url("act/meeting/meet/") ?>" />
<div class="row">
	<div class="col-md-2">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
				<h2 class="pull-left">Mesas</h2>
			</header>
			
			<div class="main-box-body clearfix">
				<div class="table-responsive clearfix">
					<table class="table table-hover">
						<tbody>
							<?php foreach ($tables as $table):?>
							<tr >
								<td style="padding: 4px; ">
									<a href="#" class="table_click" data_table="<?php echo $table->id ?>"><?php echo $table->title ?></a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-2">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
				<h2 class="pull-left"><span class="agenda_table"></span> Agenda </h2>
			</header>
			<div class="main-box-body clearfix">
				<div class="table-responsive clearfix">
					<table class="table table-hover">
						<tbody>
							<?php foreach ($schedules as $schedule):?>
							<tr >
								<td style="padding: 4px; ">
									<a href="#" class="schedules_click" data_table="<?php echo $schedule->id ?>"><?php echo $schedule->title ?></a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-8">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
				<h2 class="pull-left"><span class="agenda_table"></span><span id="agenda_schedule"></span>Participantes</h2>
			</header>
			<div class="main-box-body clearfix">
				<div class="table-responsive clearfix">
					<table class="table">
						<thead>
							<tr>
								<th><a href="#"><span>Horario</span></a></th>
								<th class="text-right">Host Nome</th>
								<th class="text-center">Host Empresa</th>
								<th class="text-right">Guest Nome</th>
								<th class="text-center">Guest Empresa</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($times as $time):?>
							<tr>
								<td>
									<?php echo $time->title ?>
								</td>
								<td class="text-right">
									<input type="text" class="meet_params meet_<?php echo $time->id ?>" data-time="<?php echo $time->id ?>" class="form-control" name="host_name" placeholder="Host Nome">
								</td>
								<td class="text-center">
									<input type="text" class="meet_params meet_<?php echo $time->id ?>" data-time="<?php echo $time->id ?>" class="form-control" name="host_company" placeholder="Host Empresa">
								</td>
								<td class="text-center">
									<input type="text" class="meet_params meet_<?php echo $time->id ?>" data-time="<?php echo $time->id ?>" class="form-control" name="guest_name" placeholder="Guest Nome">
								</td>
								<td class="text-center">
									<input type="text" class="meet_params meet_<?php echo $time->id ?>" data-time="<?php echo $time->id ?>" class="form-control" name="guest_company" placeholder="Guest Empresa">
								</td>
							</tr>
							<?php endforeach; ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>