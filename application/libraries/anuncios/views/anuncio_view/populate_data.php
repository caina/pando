<input type="hidden" value="<?php echo site_url("lib_generic/method/anuncios/ad/")?>" id="host">
<div class="col-md-12">
	<div class="main-box clearfix">
		<header class="main-box-header clearfix">
			<h2>Marcas</h2>
			<a href="#" id="execute" class="btn btn-primary pull-right">
				<i class="fa fa-plus-circle fa-lg"></i> Add product
			</a>
		</header>

		<div class="main-box-body clearfix">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><span>Marca</span></th>

							<th><span></span></th>

						</tr>
					</thead>
					<tbody>
<?php foreach ($brands as $brand):?>
							<tr data-id="<?php echo $brand->id?>">
								<td width="10%">
<?php echo $brand->title?>
</td>
								<td width="90%">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th><span>Modelo</span></th>
												<th><span></span></th>
											</tr>
										</thead>
										<tbody id="brand_html_<?php echo $brand->id?>">
											<tr >
												<td>
													Palio
												</td>
												<td>
													<table class="table table-bordered">
														<thead>
															<tr>
																<th><span>Ano</span></th>
																<th><span></span></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	1999
																</td>
																<td>
																	<table class="table table-bordered">
																		<thead>
																			<tr>
																				<th><span>Versão</span></th>
																				<th><span>preco</span></th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td>
																					hatch
																				</td>
																				<td>
																					R$ 33.000,00
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>

										</tbody>
									</table>
								</td>
							</tr>

<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>