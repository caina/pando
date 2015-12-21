<div class="row">
	<div class="col-lg-12">
		<div class="main-box clearfix">

			<h3><span>Clientes</span></h3>
			<div class="panel-group accordion" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
								<i class="fa fa-user-plus"></i> Clique aqui para abrir o formulário
							</a>
						</h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="main-box-body clearfix">
								<form role="form" enctype="multipart/form-data" method="POST" action="<?php echo site_url("lib_generic/method/company_adm/clients/register") ?>">
									<div class="form-group">
										<label for="txt_name">Razão social</label>
										<input type="text" name="company_name" class="form-control" id="txt_name" data-toggle="tooltip" data-placement="bottom" title="Digite o nome do cliente">
									</div>
									<div class="form-group">
										<label for="txt_fone">Felefone</label>
										<input type="text" name="phone_number" class="form-control" id="txt_fone" data-toggle="tooltip" data-placement="bottom" title="Telefone da empresa">
									</div>
									<div class="form-group">
										<label for="txt_email">Email</label>
										<input type="email" name="email" class="form-control" id="txt_email" data-toggle="tooltip" data-placement="bottom" title="Email da empresa">
									</div>
									<div class="form-group">
										<label for="txt_cnpj">CNPJ</label>
										<input type="text" name="cnpj" class="form-control" id="txt_cnpj" data-toggle="tooltip" data-placement="bottom" title="CNPJ">
									</div>
									<div class="form-group">
										<label>Selecione o estado</label>
										<select class="form-control" id="state_select" data_path="<?php echo site_url("lib_generic/method/company_adm/clients/list_cities") ?>">
											<option>Selecione</option>
											<?php foreach ($avaliable_states as $state):?>
												<option value="<?php echo $state->id ?>"><?php echo $state->nome ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<div class="form-group">
										<label>Selecione a cidade</label>
										<select class="form-control" id="city_select" name="admin_city_id">
											<option value="0">Selecione um estado</option>
										</select>
									</div>

									<div class="form-group">
										<label for="txt_address">Endereço</label>
										<input type="text" name="address" class="form-control" id="txt_address" data-toggle="tooltip" data-placement="bottom" title="Endereço da empresa">
									</div>
									<div class="form-group">
										<label for="txt_name_contact">Nome do Contato</label>
										<input type="text" name="contact_name" class="form-control" id="txt_name_contact" data-toggle="tooltip" data-placement="bottom" title="Pessoa encarregada dos contatos">
									</div>
									<div class="form-group">
										<label for="txt_phone_contact">Telefone do Contato</label>
										<input type="text" name="contact_phone" class="form-control" id="txt_phone_contact" data-toggle="tooltip" data-placement="bottom" title="Telefone da pessoa encarregada dos contatos">
									</div>

									<div class="form-group">
										<label>Logo</label>
										<input type="file" name="logo">
									</div>

									<div class="form-group">
										<div class=" col-lg-12">
											<button type="submit" class="btn btn-success">Salvar</button>
										</div>
									</div>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<h3><span>Categorias</span></h3>
			<div class="panel-group accordion" id="accordion_client">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_client" href="#collapsedois">
								<i class="fa fa-bookmark"></i> Cadastrar categoria
							</a>
						</h4>
					</div>
					<div id="collapsedois" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="main-box-body clearfix">
								<form role="form" enctype="multipart/form-data" method="POST" action="<?php echo site_url("lib_generic/method/company_adm/clients/register_category") ?>">
									<div class="form-group">
										<label for="txt_name">Nome</label>
										<input type="text" name="name" class="form-control" id="txt_name" data-toggle="tooltip" data-placement="bottom" title="Digite o nome do cliente">
									</div>
									

									<div class="form-group">
										<label for="txt_name">Cor</label>
										<input type="text" name="color" class="form-control " id="color_picker">
									</div>

									<div class="form-group">
										<div class=" col-lg-12">
											<button type="submit" class="btn btn-success">Salvar</button>
										</div>
									</div>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>


<div class="row">
	<div class="col-lg-12">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
				<h2>Clientes.</h2>
			</header>
			
			<div class="main-box-body clearfix">
				<div class="table-responsive">
					<table id="list_client" class="table table-hover">
						<thead>
							<tr>
								<th>Razão Social</th>
								<th>Logo</th>
								<th>Status</th>
								<th>Opções</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($records as $record): ?>
							<tr>
								<td><?php echo $record->company_name ?></td>
								<td><img src="<?php echo ($logged_user->upload_path."/".$record->logo) ?>" style="max-height:50px" /></td>
								<td>
									<select class="form-control">
									<?php foreach ($adm_client_category as $cat):?>
										<option <?php echo $cat->id==$record->adm_client_category_id?"selected='selected'":'' ?>> <?php echo $cat->name ?> </option>
									<?php endforeach; ?>
									</select>	
								</td>
								<td>
									<a href="<?php echo site_url("lib_generic/method/company_adm/clients/client_detail/".$record->id) ?>" class="table-link">
										<span class="fa-stack">
											<i class="fa fa-square fa-stack-2x"></i>
											<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
										</span>
									</a>
									<a href="#" class="table-link danger">
										<span class="fa-stack">
											<i class="fa fa-square fa-stack-2x"></i>
											<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
										</span>
									</a>
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