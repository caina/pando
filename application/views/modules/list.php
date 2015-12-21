<?php // dump($module_obj); ?>
<!--  <a href="<?php echo site_url("modules"); ?>" class="btn btn-info" >Novo</a> -->
<!-- LISTAGEM MODULOS CADASTRADOS -->
<div class="col-md-6 col-lg-6">
  <div class="main-box no-header clearfix">
    <div class="main-box-body clearfix" id="modules_list">
      <div class="table-responsive">
        <table class="table user-list table-hover">
          <thead>
            <tr>
              <th>Nome</th>
                    <th>Menu</th>
              <th>Ativo</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($database_module as $module):?>
              <tr class="<?php echo $module->is_valid==0?'alert alert-warning':'' ?>">
                <td>
                  <a href="<?php echo site_url("modules/index/{$module->id}") ?>" >
                    <?php if($module->is_valid==0): ?>
                      <i class="fa fa-warning fa-fw fa-lg"></i>
                    <?php endif; ?>
                    <?php echo $module->name?></a>
                  </td>
                <td>
                  <select class="form-control menu_selector" name="company_id">
                    <option value="" >Selecione</option>
                    <?php foreach($database_menus as $menu): ?>
                      <?php $selected= ($menu->id==$module->menu_id)?"selected='true'":""; ?>
                      <option value="<?php echo $menu->id?>_<?php echo $module->id ?>" <?php echo $selected ?> ><?php echo $menu->label?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td class="tex-center">
                  <input type="checkbox" data-element-id="<?php echo $module->id ?>" class="check_active" name="active" value="<?php echo $module->active ?>" <?php echo $module->active==1?"checked='checked'":"" ?> />
                </td>
                <td style="width: 20%;">
                  <a href="<?php echo site_url("modules/index/{$module->id}") ?>" class="table-link">
                    <span class="fa-stack">
                      <i class="fa fa-square fa-stack-2x"></i>
                      <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                    </span>
                  </a>
                  <a href="<?php echo site_url("modules/delete_module/{$module->id}") ?>" onclick="return confirm('deseja realmente remover este módulo?');" class="table-link danger">
                    <span class="fa-stack">
                      <i class="fa fa-square fa-stack-2x"></i>
                      <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                    </span>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="main-box-body clearfix" id="documentation" style="display:none">
    <?php echo $documentation ?>
    </div>

  </div>

  <div class="main-box no-header clearfix">
    <header class="main-box-header clearfix">
      <h2>Menus</h2>
    </header>
    <div class="main-box-body clearfix">

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
              <td>
                <?php echo $menu->label?>
              </td>
              <td>
                <a href="<?php echo site_url("modules/remove_menu/{$menu->id}") ?>" class="btn btn-xs btn-danger"><i class="fa fa-times"></i> </a>
              </td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="2">
              <form class="form-horizontal" method="POST" action="<?php echo site_url("modules/create_menu") ?>" role="form">
                <div class="form-group">
                  <label class="col-lg-2 control-label">Nome</label>
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

  <div class="main-box no-header clearfix">
    <header class="main-box-header clearfix">
      <h2>Rodar Query</h2>
    </header>
    <div class="main-box-body clearfix">
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
  </div>


</div>
<!-- FIM LISTAGEM MODULOS CADASTRADOS -->

<!-- FORMULARIO DE CADASTRO -->
<div class="col-lg-6">
  <?php if(isset($module_obj->is_valid) && $module_obj->is_valid==0): ?>
    <div class="main-box">
      <header class="main-box-header clearfix">
        
        <div class="alert alert-danger">
          <i class="fa fa-times-circle fa-fw fa-lg"></i>
          <strong>Módulo com problema</strong> 
          <?php echo $module_obj->problem_message ?>
        </div>
      </header>

    </div>
  <?php endif; ?>
  <div class="main-box">
    <header class="main-box-header clearfix">
      <h2>
        <?php if(!empty($module_obj->name)): ?>
                Editar <strong><?php echo @$module_obj->name ?></strong>
             <?php  else:?>
               Cadastrar Módulo
              <?php endif; ?>
      </h2>
    </header>
    
    <div class="main-box-body clearfix">
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
              <?php foreach($database_company as $company): ?>
                <?php
                $selected = "";
                if(empty($module_obj)){
                  $selected = $logged_user->company_id==$company->id?"selected='true'":"";
                }else{
                  $selected = $module_obj->company_id==$company->id?"selected='true'":"";
                }?>
                <option value="<?php echo $company->id?>" <?php echo $selected ?> ><?php echo $company->name?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label">Snippets</label>
          <div class="col-lg-10">
            <select class="form-control"   name="snippet_id">
              <option value="0">Usar JSON</option>
              <?php foreach($database_snippets as $snippet):?>
                <option  value="<?php echo $snippet->id?>" <?php echo @$module_obj->snippet_id==$snippet->id?"selected='true'":"" ?> ><?php echo $snippet->label?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>


        <div class="form-group">
          <label class="col-lg-2 control-label">Icone</label>
          <div class="col-lg-10">
            <select class="form-control selectpicker" data-live-search="true" name="icon">
              <?php foreach ($module_icons as $icon): ?>
                <option data-icon="<?php echo $icon->description ?>" value="<?php echo $icon->name ?>" <?php echo @$module_obj->icon==$icon->name?"selected='true'":"" ?>> <?php echo $icon->description ?></option>
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
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label">&nbsp;</label>
          <div class="col-lg-10">
            <button type="button" id="formatt_json" class="btn btn-primary btn-lg">
              <span class="fa fa-anchor"></span> Formatar JSON
            </button>

            <button type="button" class="btn btn-primary btn-lg help_open" data-modal="modal-2">
              <span class="fa fa-question"></span> Ajuda
            </button>
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
            <button type="submit" class="btn btn-success">Salvar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- FIM FORMULARIO CADASTRO -->
<div class="md-modal md-effect-2" id="modal-2">
    <div class="md-content">
      <div class="modal-header">
        <button class="md-close close">&times;</button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
  <div class="md-overlay"></div><!-- the overlay element -->

<script type="text/javascript">
  $(document).ready(function(){

    $(".help_open").click(function(event) {
      /* Act on the event */
      if($("#modules_list").is(":visible")){
        $("#modules_list").hide();
        $("#documentation").show();
      }else{
        $("#modules_list").show();
        $("#documentation").hide();
      }
    });

    $("#formatt_json").click(function(event) {
      /* Act on the event */
      event.preventDefault();
      var json = $("#json_place").val();
      json = JSON.parse(json);
      $("#json_place").val(JSON.stringify(json, undefined, 4));
    });

    $("#formatt_json").trigger('click'); 

    $("#json_place").keyup(function(event) {
      /* Act on the event */
      var json = $("#json_place").val();
      $("#json_place").removeClass("alert-warning");
      try {
          JSON.parse(json);
      } catch (e) {
        $("#json_place").addClass("alert-warning");
      }


    });

    $(".menu_selector").change(function(){
        var data = new Object();
        data.menu_module_id = $(this).val();


        $.ajax({
          type: "POST",
          url: "<?php echo site_url("modules/set_menu") ?>",
          data: data,
          success: function(){

          }
        });
    });

    $("#table_select").change(function(event) {
      var data = new Object();
      data.table = $(this).val();

      $.ajax({
          type: "POST",
          url: "<?php echo site_url("modules/configure_json") ?>",
          data: data,
          success: function(data){
            $("#json_place").val(data);
          } 
        });
    });

    $(".check_active").click(function(event) {
       var data = new Object();
        data.active = $(this).is(":checked");
        data.post_id = $(this).attr("data-element-id");

      $.ajax({
          type: "POST",
          url: "<?php echo site_url("modules/active_inactive") ?>",
          data: data,
          success: function(){

          }
        });
    });
  });

</script>