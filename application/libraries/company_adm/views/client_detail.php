<div class="row">

	<div class="col-lg-7 col-md-7 col-sm-7">
		<div class="main-box clearfix profile-box-menu">
			<div class="main-box-body clearfix">
				<div class="profile-box-header green-bg clearfix" style="background-image: url('<?php echo base_url("theme_pack/cube/img/samples/nature.jpg") ?>');">
					<img src="<?php echo ($logged_user->upload_path."/".$client->logo) ?>" alt="" class="profile-img img-responsive" />
					<h2><?php echo $client->company_name ?></h2>
					<div class="job-position">
						<?php echo $client->email ?>
					</div>
				</div>
				
				<div class="profile-box-content clearfix">
					<ul class="menu-items">
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-user fa-lg"></i> Razão social
								<span class=" pull-right"><?php echo $client->company_name ?></span>
							</a>
						</li>
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-phone fa-lg"></i> Telefone
								<span class=" pull-right"><?php echo $client->phone_number ?></span>
							</a>
						</li>
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-envelope fa-lg"></i> Email
								<span class=" pull-right"><?php echo $client->email ?></span>
							</a>
						</li>
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-bell-o fa-lg"></i> CNPJ
								<span class=" pull-right"><?php echo $client->cnpj ?></span>
							</a>
						</li>
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-building-o fa-lg"></i> Endereço
								<span class=" pull-right"><?php echo $client->address ?></span>
							</a>
						</li>
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-building-o fa-lg"></i> Cidade
								<span class=" pull-right"><?php echo $client->city_name ?></span>
							</a>
						</li>
						<li>
							<a href="#" class="clearfix">
								<i class="fa fa-building-o fa-lg"></i> Estado
								<span class=" pull-right"><?php echo $client->state_name ?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo site_url("lib_generic/method/company_adm/clients/client_detail_edit/".$client->id) ?>" class="clearfix">
								<i class="fa fa-unlock-alt"></i> Editar
							</a>
						</li>
						
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-5 col-md-5 col-sm-5">
		aqui vai o lucro dele
	</div>
</div>

<div class="row">

	<div class="col-lg-12 col-md-12 col-sm-12">
		ultimos projetos e tarefas
	</div>

</div>