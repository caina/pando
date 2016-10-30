
<div class="col-lg-12">
  
  <div class="row" id="user-profile">
    <div class="col-lg-3 col-md-4 col-sm-4">
      <div class="main-box clearfix">
        <header class="main-box-header clearfix">
          <h2><?php echo $user->name ?></h2>
        </header>
        
        <div class="main-box-body clearfix">
          
          <img src="<?php echo !empty($logged_user->facebook_id)? "//graph.facebook.com/".$logged_user->facebook_id."/picture?type=large" : base_url("assets/img/avatar_160_160.gif") ?>" alt="" class="profile-img img-responsive center-block" />
          
          <div class="profile-label">
            <span class="label label-danger"><?php echo $user->role ?></span>
          </div>
          
          
          <div class="profile-details">
            <form role="form" method="POST" action="<?php echo site_url("profile/update_account_data") ?>">
              <div class="form-group">
                <label for="website">Website</label>
                <input type="text" name="user[website]" class="form-control" value="<?php echo $user->website ?>" id="website_" placeholder="Um website pessoal ou rede social">
              </div>
              <div class="form-group">
                <label for="description_">Sobre você</label>
                <textarea class="form-control" name="user[description]"  id="description_" rows="3"><?php echo $user->description ?></textarea>
              </div>
              <div class="form-group">
                  <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Salvar</button>
              </div>
            </form>


          </div>
            <div class="profile-message-btn center-block text-center">
              <a href="<?php echo $facebook_link['login_url'] ?>" class="btn <?php echo !empty($user->facebook_id)?'disabled':'' ?> btn-facebook btn-primary">
                <i class="fa fa-facebook"></i>
                Vincular com Facebook
              </a>
            </div>
        </div>
        
      </div>
    </div>
    


     <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="main-box clearfix">
          <header class="main-box-header clearfix">
            <h2>Dados do usuário</h2>
          </header>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tabs-wrapper profile-tabs">
             


              <div class="tab-content">
                <div class="tab-pane fade in active" id="tab-register">
                  <div class="table-responsive">

                     <form method="POST" action="<?php echo site_url("profile/save_user") ?>">


                        <div class="form-group">
                          <label class="" for="form-first-name">Nome</label>
                          <input type="text" value="<?php echo $user->name ?>" name="user[name]" class="form-first-name form-control" id="form-first-name">
                        </div>

                        <div class="form-group">
                          <label class="" for="username">Usuário / Email</label>
                          <input type="text" disabled="disabled" value="<?php echo $user->username ?>" class="form-first-name form-control" id="username">
                        </div>

                        <div class="form-group" id="cpf">
                            <label class="" for="phone_">Telefone</label>
                            <input type="text" id="phone_" data-mask="(99) 9999-9999" name="user[phone]" value="<?php echo @$user->phone ?>" class="form-first-name form-control" >
                          </div>

                         <div class="form-group">
                          <label class=""  for="cpf">Sexo</label>
                          <select class="form-control" name="user[sex]" id="sel1">
                            <option value='0'>Selecione</option>
                            <option <?php echo @$user->sex=="M"?"selected":"" ?> value='M'>Masculino</option>
                            <option <?php echo @$user->sex=="F"?"selected":"" ?> value='F'>Feminino</option>
                          </select>
                        </div>


                        <!-- <div class="form-group">
                          <label class="" for="password">Senha</label>
                          <input type="password" name="user[password]" placeholder="****" class="form-first-name form-control" id="password">
                        </div> -->
                        
                        <div class="form-group" id="radio_combo">
                          <label>Tipos de conta</label>
                          <div class="radio">
                            <input type="radio" class='radio_user_type' name='user[userType]' id="optionsRadios1" data-element='1' value="1" <?php echo @$user->userType == 1?"checked":"" ?>>
                            <label for="optionsRadios1">
                              Pessoa Física
                            </label>
                          </div>
                          <div class="radio">
                            <input type="radio" class='radio_user_type' name='user[userType]' id="optionsRadios2" data-element='2' value="2" <?php echo @$user->userType == 2?"checked":"" ?> >
                            <label for="optionsRadios2">
                              Pessoa Jurídica
                            </label>
                          </div>
                        </div>


                        <div class="form-group" id="cpf">
                          <label class="" for="cpf_">CPF</label>
                          <input type="text" name="user[cpf]" data-mask="999.999.999-99" value="<?php echo @$user->cpf ?>" class="form-first-name form-control" id="cpf_">
                        </div>

                        <div class="form-group" id="cnpj">
                          <label class="" for="cpf_">CNPJ</label>
                          <input type="text" name="user[cnpj]" data-mask="99.999.999/9999-99" value="<?php echo @$user->cnpj ?>" class="form-first-name form-control" id="cnpj_">
                        </div>  


                          <h3><span>Localização</span></h3>

                          <div class="form-group">
                          <label class="" for="zip_code">CEP</label>
                            <input type="text" name="user[zip_code]" data-mask="99.999-999" value="<?php echo @$user->zip_code ?>" class="form-first-name form-control" id="zip_code">
                          </div>

                          <div class="form-group">
                          <label class="" for="form_endereco">Endereço</label>
                            <input type="text" name="user[address]" value="<?php echo @$user->address ?>" class="form-first-name form-control" id="form_endereco">
                          </div>

                          <div class="form-group">
                          <label class="" for="number">Número</label>
                            <input type="text" name="user[number]"  value="<?php echo @$user->number ?>"  class="form-first-name form-control" id="number">
                          </div>
                                    
                          <div class="form-group">
                          <label class="" for="estado">Estado</label>
                          <select class="form-control" id="estado" ng-model="estadoSelecionado" ng-change="stateChose()" >
                          <option value='' >Selecione</option>
                            <?php foreach ($states as $state): ?>
                              <option value="<?php echo $state->id ?>" <?php echo $state->id == @$user->state_id?"selected":"" ?>><?php echo $state->title ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="form-group">
                          <label class="" for="cidade">Região </label>
                          <select class="form-control" name="user[zone_id]" id="cidade"  >
                          <option value=''>Selecione</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <div class="col-md-12">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Salvar</button>
                          </div>
                        </div>

                      </form>



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


    toggle_user_data();
    update_estado();

    $(".radio_user_type").change(function(event) {
      toggle_user_data();
    });

    $("#estado").change(function(event) {
      update_estado();
    });
  });


  function update_estado(){
    $("#cidade").empty();
    $.post('<?php echo site_url("login/get_zones") ?>', {estado:$("#estado").val()}, function(data, textStatus, xhr) {
      $.each(data,function(index, el) {
        $("#cidade").append($("<option>").val(el.id).html(el.iso_name+" "+el.iso+" "+el.title));
      });
    });
  }

  function toggle_user_data(){
    var selected = $("#radio_combo input[type='radio']:checked").val();
    if(selected==1){
      $("#cpf_").parent().show();
      $("#cnpj_").parent().hide();
    }else{
      $("#cpf_").parent().hide();
      $("#cnpj_").parent().show();
    }
  }
</script>


