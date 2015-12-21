<div class="row">
							
	<div class="col-md-6">
      <div class="widget">
         <div class="widget-head">
            <h5>Listagem de Módulos</h5>
         </div>
         <div class="widget-body no-padd">
					<div class="table-responsive">
						<table class="table table-hover table-bordered ">
						 <thead>
						   <tr>
							 <th>Nome</th>
               <th>Menu</th>
							 <th>Ativo</th>
						   </tr>
						 </thead>
						 <tbody>
						 	<?php foreach($database_module as $module):?>
							   <tr>
								 <td><a href="<?php echo site_url("modules/index/{$module->id}") ?>" ><?php echo $module->name?></a></td>
                 <td>
                  <select class="form-control menu_selector" name="company_id">
                    <option value="" >Selecione</option>
                    <?php foreach($database_menus as $menu):
                      $selected= ($menu->id==$module->menu_id)?"selected='true'":"";
                    ?>
                    <option value="<?php echo $menu->id?>_<?php echo $module->id ?>" <?php echo $selected ?> ><?php echo $menu->label?></option>
                    <?php endforeach; ?>
                  </select>

                 </td>
                  <td class="tex-center">
                  <input type="checkbox" data-element-id="<?php echo $module->id ?>" class="check_active" name="active" value="<?php echo $module->active ?>" <?php echo $module->active==1?"checked='checked'":"" ?> />
                  </td>
							   </tr>
							<?php endforeach; ?>
						 </tbody>
					   </table>
           </div>
           
         </div>
      </div>

      <div class="widget">
         <div class="widget-head">
            <h5>Menu</h5>
         </div>
         <div class="widget-body no-padd">
          <div class="table-responsive">
            <table class="table table-hover table-bordered ">
             <thead>
               <tr>
               <th>Nome</th>
               <th>Remover</th>
               </tr>
             </thead>
             <tbody>
              <?php foreach($database_menus as $menu):?>
                 <tr>
                 <td><?php echo $menu->label?></td>
                 <td>
                  <a href="<?php echo site_url("modules/remove_menu/{$menu->id}") ?>" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> </a>
                  </td>
                 </tr>
              <?php endforeach; ?>
                <tr>
                  <td colspan="2">
                    <form class="form-horizontal" method="POST" action="<?php echo site_url("modules/create_menu") ?>" role="form">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Label</label>
                        <div class="col-lg-10">
                          <input type="text" name="label" class="form-control" placeholder="Label">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">Salvar</button>
                      </div>
                    </div>

                    </form>
                  </td>

                </tr>
             </tbody>
             </table>
           </div>
           
         </div>
      </div>


      <div class="widget">

          <div class="page-content page-form">
            
            <div class="widget">
                           <div class="widget-head">
                              <h5>Rodar Query</h5>
                           </div>
                           <div class="widget-body">
                              <form class="form-horizontal" action="<?php echo site_url("modules/run_query") ?>" method="POST" role="form">
                              
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">Query</label>
                                  <div class="col-lg-10">
                                    <textarea class="form-control" name="runquery" rows="10">
          CREATE TABLE xxxxx (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255)  NULL,
            `description` text  NULL,
            `icon` varchar(255)  NULL,
            PRIMARY KEY (`id`)
          );

                                      </textarea>
                                  </div>
                                </div>    
                                

                                
                                
                                
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <button type="submit" class="btn btn-danger">Executar</button>
                                  </div>
                                </div>
                              </form>
                           </div>
               
               <div class="widget-foot">
               
               </div>
                        </div>
            
          </div>

      </div>
   </div>
	
	
	<div class="col-md-6">
		<div class="page-form">
				<div class="widget">
           <div class="widget-head">
           <?php if(!empty($module_obj->name)): ?>
              <h5>Editar <strong><?php echo @$module_obj->name ?></strong></h5>
           <?php  else:?>
              <h5>Cadastrar Módulo</h5>
            <?php endif; ?>
           </div>
           <div class="widget-body">
              <form class="form-horizontal" method="POST" action="<?php echo site_url("modules/register") ?>" role="form">
                <input type="hidden" value="<?php echo @$module_obj->id ?>" name="id" />
                
                <div class="form-group">
                  <label class="col-lg-2 control-label">Nome</label>
                  <div class="col-lg-10">
                    <input type="text" name="name" value="<?php echo @$module_obj->name ?>" class="form-control" placeholder="nome">
                  </div>
                </div>

                <div class="form-group">
	              <label class="col-lg-2 control-label">Empresa</label>
	              <div class="col-lg-10">
	                <select class="form-control" name="company_id">
	                  <?php foreach($database_company as $company):

                      $selected = "";
                      if(empty($module_obj)){
                        $selected = $logged_user->company_id==$company->id?"selected='true'":"";
                      }else{
                        $selected = $module_obj->company_id==$company->id?"selected='true'":"";
                      }

                      ?>
	                  	<option value="<?php echo $company->id?>" <?php echo $selected ?> ><?php echo $company->name?></option>
					  <?php endforeach; ?>
	                </select>
	              </div>
	            </div>

              <div class="form-group">
                <label class="col-lg-2 control-label">Snippets</label>
                <div class="col-lg-10">
                  <select class="form-control" name="snippet_id">
                      <option value="0">Usar JSON</option>

                    <?php foreach($database_snippets as $snippet):?>
                      <option value="<?php echo $snippet->id?>" <?php echo @$module_obj->snippet_id==$snippet->id?"selected='true'":"" ?> ><?php echo $snippet->label?></option>
            <?php endforeach; ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="col-lg-2 control-label">Icone</label>
                <div class="col-lg-10">
                  <select class="form-control" name="icon">
                    <?php foreach ($module_icons as $icon): ?>
                      <option value="<?php echo $icon->name ?>" <?php echo @$module_obj->icon==$icon->name?"selected='true'":"" ?> ><i class="fa fa-adjust"></i><?php echo $icon->description ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

	             <div class="form-group">
                  <label class="col-lg-2 control-label">Tabela</label>
                  <div class="col-lg-10">
                    <select class="form-control" id="table_select" name="mysql_table">
                      <option value="">Selecione</option>
                    
                    <?php foreach ($client_database_tables as $table): ?>
                      <option value="<?php echo $table ?>" <?php echo @$module_obj->mysql_table==$table?"selected='true'":"" ?> ><?php echo $table ?></option>
                    <?php endforeach; ?>
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-lg-2 control-label">JSON</label>
                  <div class="col-lg-10">
                    <textarea class="form-control" id="json_place" rows="40" name="json_config"  placeholder="JSON"><?php echo !empty($module_obj->json_config)?$module_obj->json_config:$default_json ?></textarea>
                  </div>
                </div>


                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <a href="<?php echo site_url("modules"); ?>" class="btn btn-info" >Novo</a>
                    <button type="submit" class="btn btn-success">Salvar</button>
                  </div>
                </div>
              </form>
           </div>
        </div>
			</div>
	</div>

