<div class="row">
	<div class="col-lg-12">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
					
				<div class="filter-block pull-right">
					
					<a href="<?php echo site_url("act/amostras/amostra/index") ?>" class="btn btn-primary pull-right">
						Voltar
					</a>
				</div>
			</header>
			
			<div class="main-box-body clearfix">
				<div id="invoice-companies" class="row">
					<div class="col-sm-4 invoice-box">
						<div class="invoice-icon hidden-sm">
							<i class="fa fa-home"></i> Enviado de
						</div>
						<div class="invoice-company">
							<h4><?php echo @$sample->name ?></h4>
						</div>
					</div>
					<div class="col-sm-4 invoice-box">
						<div class="invoice-icon hidden-sm">
							<i class="fa fa-truck"></i> Para cliente
						</div>
						<div class="invoice-company">
							<h4><?php echo $sample->client_name ?></h4>
							<p>
								<?php echo $sample->sample_type ?>
							</p>
						</div>
					</div>
					<div class="col-sm-4 invoice-box invoice-box-dates">
						<div class="invoice-dates">
							<div class="invoice-number clearfix">
								<strong>Código Amostra</strong>
								<span class="pull-right"><?php echo $sample->code ?></span>
							</div>
							<div class="invoice-date clearfix">
								<strong>Enviado dia:</strong>
								<span class="pull-right"><?php echo date("d/m/Y",strtotime($sample->date)) ?></span>
							</div>
						</div>
					</div>
				</div>
				
				
				
				<div class="invoice-box-total clearfix">
					<div class="row">
						<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
							Cliente
						</div>
						<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
							<?php echo $sample->client_name ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
							Email
						</div>
						<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
							<?php echo $sample->client_email ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
							Telefone
						</div>
						<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
							<?php echo $sample->client_phone ?>
						</div>
					</div>
					
				</div>
				
				
				
			</div>
		</div>
	</div>
</div>