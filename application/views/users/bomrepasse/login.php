<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="login-box">
				<div id="login-box-holder">
					<div class="row">
						<div class="col-xs-12">
							<header id="login-header">
								<div id="login-logo">
									<img src="img/logo.png" alt=""/>
								</div>
							</header>
							<div id="login-box-inner">
								<form role="form" action="<?php echo site_url("login/do_login")?>" method="POST">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input class="form-control" type="text" name="username" placeholder="Email">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-key"></i></span>
										<input type="password" name="password" class="form-control" placeholder="Senha">
									</div>
									<div id="remember-me-wrapper">
										<div class="row">
											<div class="col-xs-6">
												<div class="checkbox-nice">
													<input type="checkbox" id="remember-me" checked="checked" />
													<label for="remember-me">
														Lembrar
													</label>
												</div>
											</div>
											<a href="<?php echo site_url("login/recover_password") ?>" id="login-forget-link" class="col-xs-6">
												Forgot password?
											</a>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<button type="submit" class="btn btn-success col-xs-12">Login</button>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<p class="social-text">Ou logue com</p>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<a href="<?php echo $facebook_login['login_url'] ?>" class="btn btn-primary col-xs-12 btn-facebook">
												<i class="fa fa-facebook"></i> facebook
											</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				
				<div id="login-box-footer">
					<div class="row">
						<div class="col-xs-12">
							Do not have an account? 
							<a href="<?php echo site_url("login/register") ?>">
								Cadastre-se agora
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>