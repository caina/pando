      <div class="out-container">
         <div class="outer">
            <!-- Sidebar starts -->
             <?php echo $view_menu ?>
            <!-- Sidebar ends -->
            
            <!-- Mainbar starts -->
            <div class="mainbar">
               
			    <!-- Page heading starts -->
				<div class="page-head">
					<div class="container-liquid">
						<div class="row">
							<!-- Page heading -->
							<div class="col-md-6 col-sm-8 col-xs-8">
								<form role="form" method="POST" action="<?php echo site_url("company/change_selected_company") ?>">
									<h2>
										<i class="fa fa-desktop"></i>
										<?php if($logged_user->developer==1):?>
											<select id="company_selector" class="" name="company_id">
							                  <option value="0"  >Selecione</option>
							                  <?php foreach($session_user_companies_select as $session_company):?>
							                  	<option value="<?php echo $session_company->id?>" <?php echo ($logged_user->company_id == $session_company->id?"selected='true'":"") ?> ><?php echo $session_company->name?></option>
											  <?php endforeach; ?>
							                </select>
							            	<button type="submit" class="btn btn-info btn-xs">Trocar</button>
							             <?php else:?>
							             	<?php echo $logged_user->company_name?>
						            	<?php endif; ?>
						            	
										<!-- Bem vindo : <?php echo "{$logged_user->name} - {$logged_user->company_name}". ($logged_user->developer==1?"vo&ccedil;&etilde; est&aacute; logado como super usu&aatilde;rio":"") ;  ?> -->
									</h2>
								</form>
							</div>
							<div class="col-md-6 col-sm-0 hidden-sm hidden-xs">
								<span class="ph-status">
									<!-- <a href="#"><strong>Visits</strong> <i class="fa fa-chevron-up ph-red"></i> 25% &nbsp; -</a> -->
									<!-- <a href="#"><strong>Stock</strong> <i class="fa fa-chevron-down ph-green"></i> 15.0 &nbsp; -</a> -->
									<!-- <a href="#"><strong>Disco</strong> <i class="fa fa-chevron-up ph-red"></i> </a> -->
								</span>

							</div>
							<!-- <div class="col-md-3 col-sm-6 col-xs-6">
								<span class="ph-misc pull-right">
									<a href="#" class="bs-tooltip" title="Share" data-placement="bottom"><i class="fa fa-share"></i> </a>
									<a href="#" class="bs-tooltip" title="Export" data-placement="bottom"><i class="fa fa-upload"></i> </a>
									<a href="#" class="bs-tooltip" title="Print" data-placement="bottom"><i class="fa fa-print"></i> </a> 
								</span>
								<div class="clearfix"></div>
							</div>	 -->
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

				<div class="blue-block">
					<div class="page-title">
						<h3 class="pull-left"><i class="fa fa-rocket"></i>
						<?php

							echo @$top_menu;
      //      					if($logged_user->developer==1){

						// 	echo "<pre>";
						// 	print_r($_adm_user);
						// 	echo "</pre>";
						// }
						?>

						<!-- Modulo <span>Breve descricao</span> --> </h3> 	
						<?php if(!empty($permissions)):?>
							<div class="breads pull-right">
								<?php if(in_array("c", @$permissions)): ?>
									<a href="<?php echo site_url("screen/create/{$mysql_table}") ?>" type="button" class="btn btn-primary">Novo</a>
								<?php endif; ?>
								<?php if(in_array("r", @$permissions)): ?>
									<a href="<?php echo site_url("screen/listing/{$mysql_table}") ?>" type="button" class="btn btn-info">Listar</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="clearfix"></div>
					</div>
				</div>

				<div class="container">
					<?php echo $view_content ?>
				</div>
				
				<!-- Content ends -->				
			   
            </div>
            <!-- Mainbar ends -->
            
            <div class="clearfix"></div>
         </div>
      </div>
      <!-- Scroll to top -->
      <span class="totop"><a href="#"><i class="fa fa-chevron-up"></i></a></span> 