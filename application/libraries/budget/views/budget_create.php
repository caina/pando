<input type="hidden" id="url" value="<?php echo site_url("act/budget/budget/calculate_budget") ?>">
<form role="form" method="POST" action="<?php echo site_url("act/budget/budget/save_budget") ?>" id="form_create">
	<input type="hidden" name="id" value="<?php echo $budget->id ?>">
	<div class="row">
		<div class="col-md-8">

			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Custos Fixos</h2>
				</header>
					
				<div class="main-box-body clearfix">
						

					<div class="form-group">
						<label for="pages">Identificador</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-info"></i></span>
							<input type="text" class="form-control" name="project_identifier" value="<?php echo $budget->project_identifier ?>" id="" placeholder="Cliente - Nome do projeto">
						</div>
					</div>


					<div class="form-group">
						<label></label>
						<div class="checkbox-nice">
							<input type="checkbox" value="1" id="checkbox-home_page" name="has_home_page" <?php echo $budget->has_home_page?'checked="checked"':'' ?>>
							<label for="checkbox-home_page">
								Cobrar Home page?
							</label>
						</div>
						<div class="checkbox-nice">
							<input type="checkbox" value="1" id="checkbox-template" name="use_template" <?php echo $budget->use_template?'checked="checked"':'' ?>>
							<label for="checkbox-template">
								Usará Template?
							</label>
						</div>
					</div>
					<div class="form-group" id="theme_value">
						<label for="pages">Valor Template</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-money"></i></span>
							<input type="text" class="form-control" name="theme_value" value="<?php echo $budget->theme_value ?>" id="" placeholder="Páginas no site">
						</div>
					</div>

					<div class="form-group">
						<label for="pages">Número de páginas</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
							<input type="text" class="form-control" name="number_pages" value="<?php echo $budget->number_pages ?>" id="pages" placeholder="Páginas no site">
						</div>
					</div>
					<div class="form-group">
						<label for="pages">Horas de Design</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
							<input type="text" class="form-control" name="used_design_hour" value="<?php echo $budget->used_design_hour ?>" id="pages" placeholder="Horas de Design">
						</div>
					</div>
					<div class="form-group">
						<label for="pages">Horas de Programação</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-code"></i></span>
							<input type="text" class="form-control" name="used_programing_hour" value="<?php echo $budget->used_programing_hour ?>" id="pages" placeholder="Tempo para programar">
						</div>
					</div>

					<div class="form-group">
						<label for="pages">Tempo de configuração</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-cog"></i></span>
							<input type="text" class="form-control" name="used_configuration_hour" value="<?php echo $budget->used_configuration_hour ?>" id="pages" placeholder="Tempo para configurar">
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2 class="pull-left">Orçamento numero <?php echo $budget->id ?></h2>
						
				</header>
				
				<div class="main-box-body clearfix">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th><span>Serviço</span></th>
									<th class="text-center"><span>Horas</span></th>
									<th class="text-center"><span>Valor unitário</span></th>
									<th class="text-center"><span>Total</span></th>
								</tr>
							</thead>
							<tbody id="body_detail">
							</tbody>
						</table>
					</div>
					
					<div class="invoice-box-total clearfix">
						<div class="row">
							<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
								Subtotal
							</div>
							<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
								R&dollar; <span id="subtotal"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
								Imposto
							</div>
							<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
								R&dollar; <span id="imposto"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
								Lucro
							</div>
							<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
								R&dollar; <span id="lucro"></span>
							</div>
						</div>
						<div class="row grand-total">
							<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
								Total
							</div>
							<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
								R&dollar; <span id="total"></span>
							</div>
						</div>
					</div>
					<div class="invoice-summary row">
					</div>
					<div class="clearfix">
						<a href="<?php echo site_url("act/budget/budget/index") ?>" class="btn btn-success pull-right">
							<i class="fa fa-mail-forward fa-lg"></i> Salvar e voltar
						</a>
					</div>
					
				</div>
			</div>

		</div>
		<div class="col-md-4">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Dados deste orçamento</h2>
				</header>
			</div>

			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Custos Fixos</h2>
				</header>
				
				<div class="main-box-body clearfix">
					
					<div class="form-group">
						<label for="examplePrepend">Taxa imposto</label>
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input type="text" class="form-control" value="<?php echo $budget->tax ?>" name="tax" id="examplePrepend" placeholder="">
						</div>
					</div>

					<div class="form-group">
						<label for="examplePrepend">Lucro</label>
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input type="text" class="form-control" value="<?php echo $budget->profit ?>" name="profit" id="examplePrepend" placeholder="">
						</div>
					</div>

					<div class="form-group">
						<label for="examplePrepend">Valor Hora da Configuração</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $budget->configuration_value ?>" name="configuration_value" placeholder="">
						</div>
					</div>
						
					
				</div>
			</div>

			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Design</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleAppPre">Valor hora</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $budget->design_hour ?>" name="design_hour">
						</div>
					</div>
					
				</div>
			</div>

			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Diagramação</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleAppPre">Valor Home</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $budget->home_page_value ?>" name="home_page_value">
						</div>
					</div>

					<div class="form-group">
						<label for="exampleAppPre">Valor Por página</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $budget->page_value ?>" name="page_value">
						</div>
					</div>
				</div>
			</div>

				<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Programação</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleAppPre">Valor por hora</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $budget->programing_hour ?>" name="programing_hour" id="exampleAppPre">
						</div>
					</div>
					
				</div>
			</div>


			</div>
		</div>
	</div>
</form>