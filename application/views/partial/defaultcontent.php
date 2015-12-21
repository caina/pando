<div id="theme-wrapper">
		<header class="navbar" id="header-navbar">
			<div class="container">
				<a href="<?php echo site_url("dashboard") ?>" id="logo" class="navbar-brand">
					<img src="<?php echo base_url("assets/img/panda-sistema.png") ?>" alt="" class="normal-logo logo-white"/>
					<img src="<?php echo base_url("assets/img/panda-sistema.png") ?>" alt="" class="normal-logo logo-black"/>
					<img src="<?php echo base_url("assets/img/panda-sistema.png") ?>" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
				</a>
				
				<div class="clearfix">
				<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bars"></span>
				</button>
				
				<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
					<ul class="nav navbar-nav pull-left">
						<li>
							<a class="btn" id="make-small-nav">
								<i class="fa fa-bars"></i>
							</a>
						</li>
						<?php if($logged_user->developer==1):?>
						<li class="dropdown hidden-xs">
							<a class="btn dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-cog"></i>
							</a>
							<ul class="dropdown-menu notifications-list">
								<li class="pointer">
									<div class="pointer-inner">
										<div class="arrow"></div>
									</div>
								</li>
								<li class="item">
									<a href="<?php echo site_url("modules")?>">
										<i class="fa fa-cart-plus"></i>
										<span class="content">Módulos</span>
									</a>
								</li>
								<li class="item">
									<a href="<?php echo site_url("company") ?>">
										<i class="fa fa-diamond"></i>
										<span class="content">Empresas</span>
									</a>
								</li>
								<li class="item">
									<a href="<?php echo site_url("user") ?>">
										<i class="fa fa-user"></i>
										<span class="content">Usuários</span>
									</a>
								</li>
							</ul>
						</li>
						
						
						<li class="dropdown hidden-xs">
							<a class="btn dropdown-toggle" data-toggle="dropdown">
								Empresas
								<i class="fa fa-caret-down"></i>
							</a>
							<!-- <form method="POST" action="<?php echo site_url("company/change_selected_company") ?>"> -->
								<ul class="dropdown-menu">
									<?php foreach($session_user_companies_select as $session_company):?>
										<li class="item">
											<a href="<?php echo site_url("company/change_selected_company/".$session_company->id) ?>">
												<i class="fa fa-sitemap"></i> 
												<?php echo $session_company->name?>
											</a>
										</li>
									<?php endforeach; ?>

								</ul>
							<!-- </form> -->
						</li>
						<?php endif; ?>
						<?php echo @$top_widget ?>

					</ul>
				</div>
				
				<div class="nav-no-collapse pull-right" id="header-nav">
					<ul class="nav navbar-nav pull-right">
						<li class="dropdown profile-dropdown">
							<a href="<?php echo site_url("profile") ?>" class="dropdown-toggle" >
								<img src="<?php echo !empty($logged_user->facebook_id)? "//graph.facebook.com/".$logged_user->facebook_id."/picture?type=small" : base_url("assets/img/avatar_160_160.gif") ?>" alt="">
								<span class="hidden-xs"><?php echo $logged_user->name ?></span> 
							</a>
						</li>
						<li class="hidden-xxs">
							<a class="btn" href="<?php echo site_url("login/logout") ?>">
								<i class="fa fa-power-off"></i>
							</a>
						</li>
					</ul>
				</div>
				</div>
			</div>
		</header>

<div id="page-wrapper" class="container">
			<div class="row">
				<div id="nav-col">
					<section id="col-left" class="col-left-nano">
						<div id="col-left-inner" class="col-left-nano-content">
							<div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
								<img alt="" src="<?php echo !empty($logged_user->facebook_id)? "//graph.facebook.com/".$logged_user->facebook_id."/picture?type=small" : base_url("assets/img/avatar_160_160.gif") ?>" />
								<div class="user-box">
									<span class="name">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<?php echo cut_string(trim($logged_user->name),10);  ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu">
											<li><a href="<?php echo site_url("profile") ?>"><i class="fa fa-user"></i>Profile</a></li>
											<li><a href="<?php echo site_url("login/logout") ?>"><i class="fa fa-power-off"></i>Logout</a></li>
										</ul>
									</span>
									<span class="status">
										<i class="fa fa-circle"></i><?php echo cut_string($logged_user->role,13) ?>
									</span>
								</div>
							</div>
							<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
								<ul class="nav nav-pills nav-stacked">
									 <?php echo $view_menu ?>
								</ul>
							</div>
						</div>
					</section>
					<div id="nav-col-submenu"></div>
				</div>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<div id="content-header" class="clearfix">
										<div class="pull-left">
											<ol class="breadcrumb">
												<li><a href="<?php echo site_url("dashboard") ?>">Página inicial</a></li>
												<!-- <li class="active"><span>Dashboard</span></li> -->
											</ol>
										</div>
										<?php echo @$top_menu; ?>

										<?php if(!empty($permissions)):?>
											<div class="pull-right top-page-ui">
												<?php if(in_array("c", @$permissions)): ?>
													<a href="<?php echo site_url("screen/create/{$mysql_table}") ?>" class="btn btn-primary pull-right" style="margin-left: 10px;">
														<i class="fa fa-plus-circle fa-lg"></i> Novo
													</a>
												<?php endif; ?>
												&nbsp;&nbsp;
												<?php if(in_array("r", @$permissions)): ?>
													<a href="<?php echo site_url("screen/listing/{$mysql_table}") ?>" class="btn btn-primary pull-right">
														<i class="fa fa-bars fa-lg"></i> Listar
													</a>
												<?php endif; ?>
											</div>
										<?php endif; ?>




									</div>
								</div>
							</div>

							
							<div class="row">
						      <div class="col-lg-12">
						        <?php if(has_message()): ?>
						         <div class="alert alert-<?php echo type_message()==1?"success":"danger" ?>">
						            <i class="fa <?php echo type_message()==1?"fa-check-circle":"fa-times-circle" ?> fa-fw fa-lg"></i>
						           <?php echo display_message() ?>
						          </div>
						        <?php  endif; ?>
						      </div>
						    </div> 

							<?php echo $view_content ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
    
