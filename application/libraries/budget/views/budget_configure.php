<form role="form" method="POST" action="<?php echo site_url("act/budget/budget/save_configuration") ?>" id="form_configure">
	<div class="row">
		<div class="col-md-3">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Custos Fixos</h2>
				</header>
				
				<div class="main-box-body clearfix">
					
					<div class="form-group">
						<label for="examplePrepend">Taxa imposto</label>
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input type="text" class="form-control" value="<?php echo $configure->tax ?>" name="tax" id="examplePrepend" placeholder="">
						</div>
					</div>

					<div class="form-group">
						<label for="examplePrepend">Lucro</label>
						<div class="input-group">
							<span class="input-group-addon">%</span>
							<input type="text" class="form-control" value="<?php echo $configure->profit ?>" name="profit" id="examplePrepend" placeholder="">
						</div>
					</div>

					<div class="form-group">
						<label for="examplePrepend">Valor Hora da Configuração</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $configure->configuration_value ?>" name="configuration_value" placeholder="">
						</div>
					</div>
						
					
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Design</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleAppPre">Valor hora</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $configure->design_hour ?>" name="design_hour">
							<span class="input-group-addon">.00</span>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Diagramação</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleAppPre">Valor Home</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $configure->home_page_value ?>" name="home_page_value">
							<span class="input-group-addon">.00</span>
						</div>
					</div>

					<div class="form-group">
						<label for="exampleAppPre">Valor Por página</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $configure->page_value ?>" name="page_value">
							<span class="input-group-addon">.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-md-3">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Programação</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="exampleAppPre">Valor por hora</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control" value="<?php echo $configure->programing_hour ?>" name="programing_hour" id="exampleAppPre">
							<span class="input-group-addon">.00</span>
						</div>
					</div>
					
				</div>
			</div>
		</div>	
	</div>
</form>