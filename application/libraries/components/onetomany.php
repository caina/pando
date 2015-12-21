<?php
/****
	CRIA FORMS USANDO OS DADOS DA TABELA NO BANCO
	E COMBINA COM OS DADOS DE CONFIGURAÇÃO DO MÓDULO


*/
class Onetomany {
	
	 var $ci;
	 var $options;
	 var $user_model;
	 var $config;

	 public function __construct($config){
		$this->ci =&get_instance();
		$this->config = $config;
	}

	public function show($options = array(),$data){
		
		return "";
	}

	public function save_action($field=""){
		$this->ci->load->model("screen_model");
		$this->ci->screen_model->database_client->update($this->config["table"], array($this->config["fieldKey"]=> $field), array("mngr_token"=>$this->config["form_data"]["mngr_token"]));
		return "";
	}

	public function list_act(){

	}

	public function generate(){
		// dump($this->config);
		// instanciar o Screnn.php
		$sql_table = $this->config["table"];
		$mngr_token = $this->config["mngr_token"];
		// dump($sql_table);
		$this->ci->load->library("form_creator");
		$this->ci->form_creator->submitasajax = true;
		$this->ci->form_creator->sql_table = $sql_table;
		$this->ci->form_creator->mngr_token = $mngr_token;

		$this->ci->form_creator->load_company_config($sql_table,true);
		
		$vinculo_texto = @$this->config["texto_header"];
		//chamar o metodo  generate, isso vai ser muito loco :D
		$html = $this->ci->form_creator->create_form(false);
		//printar o form
		// dump($html);

		return "
		</div>
		</div>
		<div class='row'>

			<div class='col-lg-12'>

				<div class='main-box'>
					<header class='main-box-header clearfix'>
				        <h2> {$vinculo_texto} </h2>
				      </header>
					<div class='main-box-body clearfix'>
						<div class='row'>
							<div class='col-lg-6'>
								<div class='row'>
									<div class='form-group col-md-12'>
										{$html}
										<div class='loader' style='display:none;min-height: 60px;' >
						                 	<center>
						                 		<style> #circleG{width:149.33333333333334px; } .circleG{background-color:#FFFFFF; float:left; height:32px; margin-left:17px; width:32px; -moz-animation-name:bounce_circleG; -moz-animation-duration:1.5s; -moz-animation-iteration-count:infinite; -moz-animation-direction:linear; -moz-border-radius:21px; -webkit-animation-name:bounce_circleG; -webkit-animation-duration:1.5s; -webkit-animation-iteration-count:infinite; -webkit-animation-direction:linear; -webkit-border-radius:21px; -ms-animation-name:bounce_circleG; -ms-animation-duration:1.5s; -ms-animation-iteration-count:infinite; -ms-animation-direction:linear; -ms-border-radius:21px; -o-animation-name:bounce_circleG; -o-animation-duration:1.5s; -o-animation-iteration-count:infinite; -o-animation-direction:linear; -o-border-radius:21px; animation-name:bounce_circleG; animation-duration:1.5s; animation-iteration-count:infinite; animation-direction:linear; border-radius:21px; } #circleG_1{-moz-animation-delay:0.3s; -webkit-animation-delay:0.3s; -ms-animation-delay:0.3s; -o-animation-delay:0.3s; animation-delay:0.3s; } #circleG_2{-moz-animation-delay:0.7s; -webkit-animation-delay:0.7s; -ms-animation-delay:0.7s; -o-animation-delay:0.7s; animation-delay:0.7s; } #circleG_3{-moz-animation-delay:0.9s; -webkit-animation-delay:0.9s; -ms-animation-delay:0.9s; -o-animation-delay:0.9s; animation-delay:0.9s; } @-moz-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-webkit-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-ms-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-o-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } </style> <div id='circleG'> <div id='circleG_1' class='circleG'> </div> <div id='circleG_2' class='circleG'> </div> <div id='circleG_3' class='circleG'> </div> </div>
						                 	</center>
					                 	</div>	
									</div>
								</div>
							</div>
							<!--</div>-->
							<div class='col-lg-6'>
								<div class='row'>
									<div class='form-group col-md-12'>
										<div class='widget-head'>
						                  <h5>Dados Cadastrados</h5>
						                 </div>
						                 <div class='widget-body onetomanydisplay' table='{$sql_table}' token='{$mngr_token}'>
						                 
						                 </div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		";
		// return "
		// <div class='row' id='user-profile'>
		// 	<div class='col-lg-6 col-md-6 col-sm-6'>
		// 		<div class='main-box clearfix'>
					
		// 			{$html}
		// 			<div class='loader' style='display:none;min-height: 60px;' >
	 //                 	<center>
	 //                 		<style> #circleG{width:149.33333333333334px; } .circleG{background-color:#FFFFFF; float:left; height:32px; margin-left:17px; width:32px; -moz-animation-name:bounce_circleG; -moz-animation-duration:1.5s; -moz-animation-iteration-count:infinite; -moz-animation-direction:linear; -moz-border-radius:21px; -webkit-animation-name:bounce_circleG; -webkit-animation-duration:1.5s; -webkit-animation-iteration-count:infinite; -webkit-animation-direction:linear; -webkit-border-radius:21px; -ms-animation-name:bounce_circleG; -ms-animation-duration:1.5s; -ms-animation-iteration-count:infinite; -ms-animation-direction:linear; -ms-border-radius:21px; -o-animation-name:bounce_circleG; -o-animation-duration:1.5s; -o-animation-iteration-count:infinite; -o-animation-direction:linear; -o-border-radius:21px; animation-name:bounce_circleG; animation-duration:1.5s; animation-iteration-count:infinite; animation-direction:linear; border-radius:21px; } #circleG_1{-moz-animation-delay:0.3s; -webkit-animation-delay:0.3s; -ms-animation-delay:0.3s; -o-animation-delay:0.3s; animation-delay:0.3s; } #circleG_2{-moz-animation-delay:0.7s; -webkit-animation-delay:0.7s; -ms-animation-delay:0.7s; -o-animation-delay:0.7s; animation-delay:0.7s; } #circleG_3{-moz-animation-delay:0.9s; -webkit-animation-delay:0.9s; -ms-animation-delay:0.9s; -o-animation-delay:0.9s; animation-delay:0.9s; } @-moz-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-webkit-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-ms-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-o-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } </style> <div id='circleG'> <div id='circleG_1' class='circleG'> </div> <div id='circleG_2' class='circleG'> </div> <div id='circleG_3' class='circleG'> </div> </div>
	 //                 	</center>
  //                	</div>
		// 		</div>
		// 	</div>
			
		// 	<div class='col-lg-6 col-md-6 col-sm-6'>
		// 		<div class='main-box clearfix'>
		// 			<div class='tabs-wrapper profile-tabs'>
						
		// 				<div class='widget-head'>
		//                   <h5>Dados Cadastrados</h5>
		//                  </div>
		//                  <div class='widget-body onetomanydisplay' table='{$sql_table}' token='{$mngr_token}'>
		                 
		//                  </div>
						
		// 			</div>
		// 		</div>
		// 	</div>
		// </div>
		// ";

		// return "</div> 
		


		// <div class='page-content page-ui'>
  //       <div class='blue-block'>
		// 	<div class='page-title'>
		// 		<h3 class='pull-left'><i class='fa fa-link'></i>
		// 		 {$vinculo_texto} 
		// 		</h3> 	
		// 		<div class='clearfix'></div>
		// 	</div>
		// </div>    
  //           <div class='row'>
                  
  //            <div class='col-md-6'>
  //             <div class='widget'>
  //                <div class='widget-head'>
  //                 <h5>Cadastro</h5>
  //                </div>
  //                <div class='widget-body'>

  //                	{$html}
  //                	<div class='loader' style='display:none;min-height: 60px;' >
	 //                 	<center>
	 //                 		<style> #circleG{width:149.33333333333334px; } .circleG{background-color:#FFFFFF; float:left; height:32px; margin-left:17px; width:32px; -moz-animation-name:bounce_circleG; -moz-animation-duration:1.5s; -moz-animation-iteration-count:infinite; -moz-animation-direction:linear; -moz-border-radius:21px; -webkit-animation-name:bounce_circleG; -webkit-animation-duration:1.5s; -webkit-animation-iteration-count:infinite; -webkit-animation-direction:linear; -webkit-border-radius:21px; -ms-animation-name:bounce_circleG; -ms-animation-duration:1.5s; -ms-animation-iteration-count:infinite; -ms-animation-direction:linear; -ms-border-radius:21px; -o-animation-name:bounce_circleG; -o-animation-duration:1.5s; -o-animation-iteration-count:infinite; -o-animation-direction:linear; -o-border-radius:21px; animation-name:bounce_circleG; animation-duration:1.5s; animation-iteration-count:infinite; animation-direction:linear; border-radius:21px; } #circleG_1{-moz-animation-delay:0.3s; -webkit-animation-delay:0.3s; -ms-animation-delay:0.3s; -o-animation-delay:0.3s; animation-delay:0.3s; } #circleG_2{-moz-animation-delay:0.7s; -webkit-animation-delay:0.7s; -ms-animation-delay:0.7s; -o-animation-delay:0.7s; animation-delay:0.7s; } #circleG_3{-moz-animation-delay:0.9s; -webkit-animation-delay:0.9s; -ms-animation-delay:0.9s; -o-animation-delay:0.9s; animation-delay:0.9s; } @-moz-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-webkit-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-ms-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @-o-keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } @keyframes bounce_circleG{0%{} 50%{background-color:#26FC82} 100%{} } </style> <div id='circleG'> <div id='circleG_1' class='circleG'> </div> <div id='circleG_2' class='circleG'> </div> <div id='circleG_3' class='circleG'> </div> </div>
	 //                 	</center>
  //                	</div>
  //                </div>
  //                <div class='widget-foot'>
  //                </div>
  //             </div>
  //            </div>
  //            <div class='col-md-6'>
  //             <div class='widget'>
  //                <div class='widget-head'>
  //                 <h5>Dados Cadastrados</h5>
  //                </div>
  //                <div class='widget-body onetomanydisplay' table='{$sql_table}' token='{$mngr_token}'>
                 
  //                </div>
  //                <div class='widget-foot'>
  //                </div>
  //             </div>
  //            </div>
  //           </div>
		// ";
	}

	public function set_options($options){
		$this->options = (object) $options;

	}

	private function get_options(){
		
	}

	


}

?>