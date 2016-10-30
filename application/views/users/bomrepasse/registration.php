<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="login-box">
				<div class="row">
					<div class="col-xs-12">
						<header id="login-header">
							<div id="login-logo">
								<img src="img/logo.png" alt=""/>
							</div>
						</header>
						<div id="login-box-inner">
							<?php if(!empty($error_message)): ?>
								<div class="alert alert-warning">
									<i class="fa fa-warning fa-fw fa-lg"></i>
									<?php echo $error_message ?>
								</div>
							<?php endif; ?>
							<form role="form" action="<?php echo site_url("login/register_action") ?>" method="POST">

								<div class="input-group <?php echo  !empty(form_error('name'))?'has-error':'' ?> ">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input class="form-control" name="name" value="<?php echo set_value('name'); ?>" type="text" placeholder="Nome">
								</div>
								<div class="input-group <?php echo  !empty(form_error('email'))?'has-error':'' ?>">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input class="form-control" name="email" value="<?php echo set_value('email'); ?>" type="text" placeholder="Email">
								</div>
								<div class="input-group <?php echo  !empty(form_error('password'))?'has-error':'' ?>">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" name="password" class="form-control" placeholder="Senha">
								</div>
								<div class="input-group <?php echo  !empty(form_error('passconf'))?'has-error':'' ?>">
									<span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
									<input type="password" name="passconf" class="form-control" placeholder="Digite sua senha novamente">
								</div>
								<div id="remember-me-wrapper">
									<div class="row">
										<div class="col-xs-12">
											<div class="checkbox-nice">
												<input type="checkbox" id="terms-cond" />
												<label for="terms-cond">
													Aceitos os termos de condição
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<button type="submit" id="button_accept" class="btn btn-success disabled col-xs-12">Register</button>
									</div>
								</div>
							</form>
							<div class="row">
								<div class="col-xs-12 col-sm-12">
									<a href="<?php echo $facebook_login['login_url'] ?>" class="btn btn-primary col-xs-12 btn-facebook">
										<i class="fa fa-facebook"></i> Cadastrar com Facebook
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#terms-cond").change(function(event) {
			if($("#button_accept").hasClass('disabled')){
				$("#button_accept").removeClass('disabled');
			}else{
				$("#button_accept").addClass('disabled');
			}
		});
		
	});
</script>