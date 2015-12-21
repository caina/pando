<script type="text/javascript">
$(document).ready(function(){
  $("#ftp_test").click(function(e){
    e.preventDefault();

    var data = new Object();
    data.id = $("#company_id").val();

    $.ajax({
      type: "POST",
      url: "<?php echo site_url("company/test_ftp") ?>",
      data: data,
      success: function(){

      }
    });
  });
});

</script>
<div class="row">
							
	<div class="col-md-4">
      <div class="widget">
         <div class="widget-head">
            <h5>Listagem de Empresas</h5>
         </div>
         <div class="widget-body no-padd">
					<div class="table-responsive">
						<table class="table table-hover table-bordered ">
						 <thead>
						   <tr>
							 <th>#</th>
							 <th>Nome</th>
							 <th>Status</th>
							 <th></th>
						   </tr>
						 </thead>
						 <tbody>
						 	<?php foreach($database_company as $company):?>
							   <tr>
								 <td><?php echo $company->id?></td>
								 <td><?php echo $company->name?></td>
								 <td><span class="label label-success">Active</span></td>
								 <td><a href="<?php echo site_url("company/index/{$company->id}") ?>" >Editar</a></td>
							   </tr>
							<?php endforeach; ?>
						 </tbody>
					   </table>
           </div>
           
         </div>
      </div>
   </div>
	
	
	<div class="col-md-8">
		<div class="page-form">
				<div class="widget">
           <div class="widget-head">
           <?php if(!empty($company_obj->name)): ?>
              <h5>Editar <strong><?php echo @$company_obj->name ?></strong></h5>
           <?php  else:?>
              <h5>Cadastrar Empresa</h5>
            <?php endif; ?>
           </div>
           <div class="widget-body">
              <form class="form-horizontal" method="POST" action="<?php echo site_url("company/register") ?>" role="form">
                <input type="hidden" value="<?php echo @$company_obj->id ?>" name="id" id="company_id" />
                <div class="form-group">
                  <label class="col-lg-2 control-label">Nome</label>
                  <div class="col-lg-10">
                    <input type="text" name="name" value="<?php echo @$company_obj->name ?>" class="form-control" placeholder="nome">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-2 control-label">ID Google Analytics</label>
                  <div class="col-lg-10">
                    <input type="text" name="profile_id" value="<?php echo @$company_obj->profile_id ?>" class="form-control" placeholder="profile_id">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">FTP Host</label>
                  <div class="col-lg-10">
                    <input type="text" name="ftp_host" value="<?php echo @$company_obj->ftp_host ?>" class="form-control" placeholder="host">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-2 control-label">FTP Usu&aacute;rio</label>
                  <div class="col-lg-10">
                    <input type="text" name="ftp_user" value="<?php echo @$company_obj->ftp_user ?>" class="form-control" placeholder="host">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">FTP Senha</label>
                  <div class="col-lg-10">
                    <input type="text" name="ftp_pass" value="<?php echo @$company_obj->ftp_pass ?>" class="form-control" placeholder="FTP Senha">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Upload Path</label>
                  <div class="col-lg-10">
                    <input type="text" name="upload_path" value="<?php echo @$company_obj->upload_path ?>" class="form-control" placeholder="Upload Path">
                  </div>
                </div>

                
                
                <!-- mysql -->
                <div class="form-group">
                  <label class="col-lg-2 control-label">Mysql Host</label>
                  <div class="col-lg-10">
                    <input type="text" name="mysql_host" value="<?php echo @$company_obj->mysql_host ?>" class="form-control" placeholder="Mysql Host">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-lg-2 control-label">Mysql Usua.</label>
                  <div class="col-lg-10">
                    <input type="text" name="mysql_user" value="<?php echo @$company_obj->mysql_user ?>" class="form-control" placeholder="Mysql Usu&aacute;rio">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Mysql Senha</label>
                  <div class="col-lg-10">
                    <input type="text" name="mysql_pass" value="<?php echo @$company_obj->mysql_pass ?>" class="form-control" placeholder="Mysql Senha">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Mysql Banco</label>
                  <div class="col-lg-10">
                    <input type="text" name="mysql_db" value="<?php echo @$company_obj->mysql_db ?>" class="form-control" placeholder="Mysql Banco">
                  </div>
                </div>


                <!-- 
                
                DADOS PARA ENVIO DE EMAIL!
  
                 -->
                 <div class="form-group">
                  <label class="col-lg-2 control-label">Smtp User</label>
                  <div class="col-lg-10">
                    <input type="text" name="smtp_user" value="<?php echo @$company_obj->smtp_user ?>" class="form-control" placeholder="Smtp User">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Smtp Senha</label>
                  <div class="col-lg-10">
                    <input type="text" name="smtp_password" value="<?php echo @$company_obj->smtp_password ?>" class="form-control" placeholder="Smtp Senha">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Smtp Porta</label>
                  <div class="col-lg-10">
                    <input type="text" name="smtp_port" value="<?php echo @$company_obj->smtp_port ?>" class="form-control" placeholder="Smtp Porta">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Stmp Host</label>
                  <div class="col-lg-10">
                    <input type="text" name="smtp_host" value="<?php echo @$company_obj->smtp_host ?>" class="form-control" placeholder="Stmp Host">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Email</label>
                  <div class="col-lg-10">
                    <input type="text" name="email_contact" value="<?php echo @$company_obj->email_contact ?>" class="form-control" placeholder="Email que recebe os contatos">
                  </div>
                </div>


                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <?php if(!empty($company_obj->name)): ?>
                      <a href="<?php echo site_url("company"); ?>" class="btn btn-info" >Novo</a>
                      <a href="#" class="btn btn-primary " id="ftp_test" >Testar FTP</a>
                    <?php endif; ?>
                  </div>
                </div>
              </form>
           </div>

           


          
        </div>
			</div>
	</div>
</div>

