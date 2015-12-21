<input type="hidden" value="<?php echo site_url("act/budget/budget") ?>" id="host" />

<div class="col-lg-12 col-md-12">
	<div class="row">

		<div class="col-lg-4">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2>Cadastrar novo Orçamento</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<a href="<?php echo site_url("act/budget/budget/create") ?>" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> Adicionar</a>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="main-box small-graph-box gray-bg">
				<span class="value"></span>
				<a href="<?php echo site_url("act/budget/budget/configure") ?>" type="button" class="btn btn-primary btn-lg">
					<span class="fa fa-cog fa-spin"></span> Configurar taxas padrão
					
				</a>
			</div>
		</div>

	</div>
</div>




<div class="col-lg-12">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2>Table with JS pagination, search, ordering, export to pdf and lots more.</h2>
		</header>
		
		<div class="main-box-body clearfix">
			<div class="table-responsive">
				<table id="data-table" class="table table-hover  dataTable" role="grid">
					<thead>
						<tr>
							<th>Editar</th>
							<th>Identificador</th>
							<th>Páginas</th>
							<th>Horas programação</th>
							<th>Deletar</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($budgets as $budget): ?>
							<tr>
								<td><a href="<?php echo site_url("act/budget/budget/edit/".$budget->id) ?>"><i class="fa fa-pencil"></i></a></td>
								<td><?php echo $budget->project_identifier ?></td>
								<td><?php echo $budget->number_pages ?></td>
								<td><?php echo $budget->programing_hour ?></td>
								<td><a href="<?php echo site_url("act/budget/budget/remove/".$budget->id) ?>"><i class="fa fa-times"></i></a></td>


							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



