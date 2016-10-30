<input type="hidden" value="<?php echo site_url("act/financeiro/clientes/") ?>" id="host" />
<div class="col-lg-12 col-md-12">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12 hidden-sm">
			<div class="main-box small-graph-box green-bg">
				<span class="value"><?php echo $clientes_totais->ativos ?></span>
				<span class="headline">Ativos</span>
				<!-- <div class="progress">
					<div style="width: 80%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar">
						<span class="sr-only"></span>
					</div>
				</div>
				<span class="subinfo">
					<i class="fa fa-arrow-circle-o-up"></i> 80% Taxa de conversão
				</span> -->
				
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="main-box small-graph-box emerald-bg">
				<span class="value"><?php echo $clientes_totais->prospectados ?></span>
				<span class="headline">Prospectados</span>
				<!-- <div class="progress">
					<div style="width: 22%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="84" role="progressbar" class="progress-bar">
						<span class="sr-only"></span>
					</div>
				</div>
				<span class="subinfo">
					<i class="fa fa-arrow-circle-o-up"></i> 22% mais prospectados neste mes
				</span> -->
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="main-box small-graph-box red-bg">
				<span class="value"><?php echo $clientes_totais->inativos ?></span>
				<span class="headline">Inativos</span>
				<!-- <div class="progress">
					<div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="84" role="progressbar" class="progress-bar">
						<span class="sr-only"> </span>
					</div>
				</div>
				<span class="subinfo">
					&nbsp;
				</span> -->
			</div>
		</div>
	</div>
</div>

<div class="col-lg-12 col-md-12">
	<div class="row">
		<div class="col-lg-4">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2>Cadastrar novo cliente</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<a href="<?php echo site_url("act/financeiro/clientes/cadastro") ?>" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Adicionar</a>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2>Filtrar dados</h2>
				</header>
				<div class="main-box-body clearfix">
					<form class="form-inline form_search" method="POST" action="#" role="form">
						<div class="form-group">
							<label class="sr-only" for="client_name_">Nome empresa</label>
							<input type="text" class="form-control" id="client_name_" placeholder="Nome Cliente">
						</div>
						<button type="submit" class="btn btn-success">Pesquisar</button>
						<div class="checkbox checkbox-nice">
							<button type="button" class="btn client_type_togle btn-primary" value="1">Apenas Ativos</button>
							<button type="button" class="btn client_type_togle btn-success" value="2">Apenas Prospectados</button>
							<button type="button" class="btn client_type_togle btn-danger" value="3">Apenas Inativos</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-12 col-md-12">
	<div id="listagem_display">

	</div>
</div>






