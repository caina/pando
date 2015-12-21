<style>
    .text_header{
      padding: 20px 0 3px;
      margin: 0;
      display: inline-block;
      font-weight: 600;
      line-height: 1.1;
      color:#000;
    }

</style>

<div class="col-lg-12">
  
  <div class="row">
    <div class="col-lg-12">
      <h1>Gerenciar usuários</h1>
    </div>
  </div> 
  
  <div class="row" id="user-profile">

      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="main-box clearfix">
          <div class="tabs-wrapper profile-tabs">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-register" data-toggle="tab">Cadastrar</a></li>
              <li><a href="#tab-user_list" data-toggle="tab">Listar</a></li>
              <li><a href="#tab-permissions" data-toggle="tab">Permissões</a></li>
            </ul>
            
            <div class="tab-content">
              <div class="tab-pane fade in active" id="tab-register">
                <div class="table-responsive">
                  <form role="form" method="POST" action="<?php echo site_url("user/register") ?>">
                    <input type="hidden" value="<?php echo @$user_obj->id ?>" name="id"  />


                    <div class="form-group">
                      <label for="exampleName">Nome</label>
                      <input type="text" class="form-control" id="exampleName" name="name" value="<?php echo @$user_obj->name ?>" placeholder="Digite o nome">
                    </div>

                    <div class="form-group">
                      <label for="exampleUser">Usuário</label>
                      <input type="text" class="form-control" id="exampleUser" name="username" value="<?php echo @$user_obj->username ?>" name="name" value="<?php echo @$user_obj->name ?>" placeholder="Digite o nome">
                    </div>

                    <div class="form-group">
                      <label for="examplePass">Senha</label>
                      <input type="text" class="form-control" id="examplePass" name="password" value="" name="name" value="<?php echo @$user_obj->name ?>" placeholder="Digite o nome">
                    </div>

                    <div class="form-group">
                      <label>Permissão</label>
                      <select class="form-control" name="role_id">
                      <option value="" >Selecione</option>
                        <?php foreach($permissions as $permissao): ?>
                          <?php $selected= ($user_obj->role_id==$permissao->id)?"selected='true'":"";?>
                            <option value="<?php echo $permissao->id?>" <?php echo $selected ?> ><?php echo $permissao->title?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    
                    <?php if($logged_user->developer == 1): ?>
                      <div class="form-group">
                        <label>Empresa</label>
                        <select class="form-control" name="company_id">
                        <option value="" >Selecione</option>
                          <?php foreach($database_company as $empresa): ?>
                            <?php $selected= (@$user_obj->company_id==$empresa->id)?"selected='true'":"";?>
                              <option value="<?php echo $empresa->id?>" <?php echo $selected ?> ><?php echo $empresa->name?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    <?php endif; ?>

                    <div class="form-group">
                      <div class="col-lg-12">
                        <button type="submit" class="btn btn-success">Salvar</button>
                        <?php if(isset($user_obj->id)): ?>
                          <a href="<?php echo site_url("user"); ?>" class="btn btn-info" >Novo</a>
                        <?php endif; ?>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
              
              <div class="tab-pane fade" id="tab-user_list">
                

                <div class="main-box no-header clearfix">
                  <div class="main-box-body clearfix">
                    <div class="table-responsive">

                      

                      <table class="table user-list table-hover">
                        <thead>
                          <tr>
                            <th><span>Nome</span></th>
                            <th class="text-center"><span>Permissão</span></th>
                            <th><span>Usuário</span></th>
                            <th>&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody id="user_list_display">
                          
                        </tbody>
                      </table>

                      <ul class="pagination">
                        <?php for ($i=0; $i < $user_pages; $i++):?>
                          <li class="<?php echo $i==0?'active':'' ?> user_pagination"><a href="#"  data-display="<?php echo $i ?>"><?php echo $i+1 ?></a></li>
                        <?php endfor; ?>
                      </ul>
                    </div>
                  </div>
                </div>


             
               
              </div>
              
              <div class="tab-pane clearfix fade" id="tab-permissions">
                <div id="newsfeed">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="main-box">
                        <header class="main-box-header clearfix">
                          <h2>Cadastrar nova regra</h2>
                        </header>
                        
                        <div class="main-box-body clearfix">
                          <form class="form-inline" id="permission_creater" method="POST" action="<?php echo site_url("permissions/set_role") ?>" role="form">
                            <div class="form-group">
                              <label class="sr-only" for="exampleInputEmail2">Nome</label>
                              <input type="text" class="form-control" requred id="exampleInputEmail2" placeholder="Digite o nome">
                            </div>
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                          </form>
                        </div>                
                      </div>
                    </div>  
                  </div>
                  <!-- ajax -->
                  <div id="permissions_list">
                  </div> 
                  <!-- fim ajax -->
              </div>
              
              
            </div>
          </div>
        </div>
      </div>

  </div>

</div>


<script type="text/javascript">
  
  jQuery(document).ready(function($) {
    list_roles();
    list_users(0);
    $("#permission_creater").submit(function(event) {
      event.preventDefault();
      var _this = $(this);
      $.post($(this).attr("action"), {name:$(this).find("input").val()}, function(data, textStatus, xhr) {
        _this.trigger("reset");
        list_roles();
      });
    });

    $(".user_pagination a").click(function(event) {
      event.preventDefault();
      $(".user_pagination").removeClass('active');
      $(this).parent().addClass('active');
      list_users($(this).attr("data-display"));
    });

    $(document).on('change', '.user_permission', function(event) {
      event.preventDefault();
      /* Act on the event */
      $.post('<?php echo site_url("permissions/change_role") ?>', {role:$(this).val()}, function(data, textStatus, xhr) {
        console.log(data);
      });
      console.log($(this).val());
    });

    $(document).on('click', '.menu-items li a', function(event) {
      event.preventDefault();
      $(this).find("input").prop('checked', !$(this).find("input").prop('checked'));
      $("#"+$(this).attr("data-form")).trigger('submit');
    });


    $(document).on('submit', '#permissions_list form[class="permission_update"]', function(event) {
      event.preventDefault();
      $.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
        console.log(data);
      });
      /* Act on the event */
    });

    toggle_user_data();
    update_estado();

    $(".radio_user_type").change(function(event) {
      toggle_user_data();
    });

    $("#estado").change(function(event) {
      update_estado();
    });
  });

  function list_users(page){
    $.post('<?php echo site_url("user/list_users") ?>', {page:page}, function(data, textStatus, xhr) {
      $("#user_list_display").empty().html(data);
    });
  }

  function list_roles(){
    $.post('<?php echo site_url("permissions/list_roles") ?>',{}, function(data, textStatus, xhr) {
      $("#permissions_list").empty().html(data);
    });
  }

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
          
