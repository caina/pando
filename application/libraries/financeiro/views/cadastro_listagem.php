


<div class="row">
	<div class="col-lg-12">
		<div class="main-box no-header clearfix">
			<div class="main-box-body clearfix">
				<div class="table-responsive">
					
					<ul class="pagination">
						<li><a href="#" class="page_handler" data_page="previous"><i class="fa fa-chevron-left "></i></a></li>
						<?php for($i=0;$i<$pages;$i++): ?>
							<li>
								<a href="#" data_page="<?php echo $i ?>" class="page_handler">
									<?php echo $i+1 ?>
								</a>
							</li>
						<?php endfor; ?>
						<li><a href="#" class="page_handler" data_page="next"><i class="fa fa-chevron-right"></i></a></li>
					</ul>

					<table class="table user-list table-hover">
						<thead>
							<tr>
								<th><span>Cliente</span></th>
								<th><span>Cadastro</span></th>
								<th class="text-center"><span>Tipo</span></th>
								<th><span>Email</span></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($clientes as $cliente):
								$client_type="";
								$client_label = "label-default";
								switch ($cliente->status) {
									case 1:
										$client_type="Ativo";
										$client_label = "label-success";
										break;
									case 2:
										$client_type="Prospectado";
										$client_label = "label-primary";
										break;
									case 3:
										$client_type="Inativo";
										$client_label = "label-danger";
										break;
								}
								?>
								<tr>
									<td>
										<?php echo $cliente->name ?>
									</td>
									<td>
										<?php echo date("d/m/Y",strtotime($cliente->created_at))?>
									</td>
									<td class="text-center">
										<span class="label <?php echo $client_label ?>"><?php echo $client_type ?></span>
									</td>
									<td>
										<a href="mailto:<?php echo $cliente->email ?>"><?php echo $cliente->email ?></a>
									</td>
									<td style="width: 20%;">
										<!-- <a href="#" class="table-link">
											<span class="fa-stack">
												<i class="fa fa-square fa-stack-2x"></i>
												<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
											</span>
										</a> -->
										<a href="<?php echo site_url("act/financeiro/clientes/cadastro/".$cliente->id) ?>" class="table-link">
											<span class="fa-stack">
												<i class="fa fa-square fa-stack-2x"></i>
												<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
											</span>
										</a>
										<a href="<?php echo site_url("act/financeiro/clientes/inativar/".$cliente->id) ?>" class="table-link danger">
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
					<ul class="pagination">
						<li><a href="#" class="page_handler" data_page="previous"><i class="fa fa-chevron-left "></i></a></li>
						<?php for($i=0;$i<$pages;$i++): ?>
							<li>
								<a href="#" data_page="<?php echo $i ?>" class="page_handler">
									<?php echo $i+1 ?>
								</a>
							</li>
						<?php endfor; ?>
						<li><a href="#" class="page_handler" data_page="next"><i class="fa fa-chevron-right"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>