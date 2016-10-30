 <?php  // dump($company); ?>
<form role="form" method="POST" action="<?php echo site_url("act/financeiro/clientes/cadastro_action/".$company->id) ?>">
	<div class="row">
		
		<div class="col-lg-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Dados técnicos</h2>
				</header>
				
				<div class="main-box-body clearfix">
					<div class="form-group">
						<label for="client_profile">Id Google Analytics</label>
						<input type="text" name="client[profile_id]" value="<?php echo $company->profile_id ?>"  class="form-control" id="client_profile" >
					</div>

					<div class="form-group">
						<label for="client_email_contact">Email padrão de contato</label>
						<input type="text" name="client[email_contact]" value="<?php echo $company->email_contact ?>"  class="form-control" id="email_contact" >
					</div>

					<div class="form-group">
						<label for="client_upload_path">Upload Path</label>
						<input type="text" name="client[upload_path]" value="<?php echo $company->upload_path ?>"  class="form-control" id="client_upload_path" >
					</div>

					<h3><span>Banco de Dados</span></h3>

					<div class="form-group">
						<label for="client_mysql_host">Host</label>
						<input type="text" name="client[mysql_host]"  value="<?php echo $company->mysql_host ?>"  class="form-control" id="client_mysql_host" >
					</div>

					<div class="form-group">
						<label for="client_mysql_user">Usuário</label>
						<input type="text" name="client[mysql_user]"  value="<?php echo $company->mysql_user ?>"  class="form-control" id="client_mysql_user" >
					</div>

					<div class="form-group">
						<label for="client_mysql_mysql_pass">Senha</label>
						<input type="text" name="client[mysql_pass]"  value="<?php echo $company->mysql_pass ?>"  class="form-control" id="client_mysql_mysql_pass" >
					</div>

					<div class="form-group">
						<label for="client_mysql_mysql_db">Banco</label>
						<input type="text" name="client[mysql_db]"  value="<?php echo $company->mysql_db ?>"  class="form-control" id="client_mysql_mysql_db" >
					</div>

					<h3><span>FTP</span></h3>

					<div class="form-group">
						<label for="client_ftp_host">Host FTP</label>
						<input type="text" name="client[ftp_host]"  value="<?php echo $company->ftp_host ?>"  class="form-control" id="client_ftp_host" >
					</div>

					<div class="form-group">
						<label for="client_ftp_user">Usuário FTP</label>
						<input type="text" name="client[ftp_user]"  value="<?php echo $company->ftp_user ?>"  class="form-control" id="client_ftp_user" >
					</div>

					<div class="form-group">
						<label for="client_ftp_pass">Senha FTP</label>
						<input type="text" name="client[ftp_pass]"  value="<?php echo $company->ftp_pass ?>"  class="form-control" id="client_ftp_pass" >
					</div>

					

					<div class="form-group">
						<div class="col-md-12">
							<button type="submit" class="btn btn-success">Salvar</button>
						</div>
					</div>

				</div>
			</div>
		</div>	

		<div class="col-lg-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Dados do cliente</h2>
				</header>
				
				<div class="main-box-body clearfix">
						<div class="form-group">
							<label for="client_name_">Nome</label>
							<input type="text" name="client[name]" value="<?php echo $company->name ?>"  required class="form-control" id="client_name_" >
						</div>
						
						<div class="form-group">
							<label>Status</label>
							<select name="client[status]" class="form-control">
								<option <?php echo $company->status==1?"selected='selected'":'' ?> value="1">Ativo</option>
								<option <?php echo $company->status==2?"selected='selected'":'' ?> value="2">Prospectação</option>
								<option <?php echo $company->status==3?"selected='selected'":'' ?> value="3">Inativo</option>
							</select>
						</div>

						<div class="form-group">
							<label for="client_email">Email</label>
							<input type="email" name="client[email]" value="<?php echo $company->email ?>"  class="form-control" id="client_email" placeholder="">
						</div>
						
						<div class="form-group">
							<label for="client_phone_">Telefone</label>
							<input type="text" name="client[phone]" value="<?php echo $company->phone ?>" class="form-control" id="client_phone_" >
						</div>
						
						<div class="form-group">
							<label for="client_observation_">Observações</label>
							<textarea class="form-control" name="client[observation]" id="client_observation_" rows="5"><?php echo $company->observation; ?></textarea>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-success">Salvar</button>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</form>