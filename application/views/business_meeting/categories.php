
<div class="row">
              
  <div class="col-md-4">
      <div class="widget">
         <div class="widget-head">
            <h5>Listagem de Categoras</h5>
         </div>
         <div class="widget-body no-padd">
          <div class="table-responsive">
            <table class="table table-hover table-bordered ">
             <thead>
               <tr>
               <th>#</th>
               <th>Nome</th>
               <th>Cor</th>
               <th>A&ccedil;&otilde;es</th>
               </tr>
             </thead>
             <tbody>
              <?php foreach($categories as $category):?>
                 <tr>
                 <td><?php echo $category->id?></td>
                 <td><?php echo $category->categorie_name?></td>
                 <td><?php echo $category->color?></td>
                 <td>
                  <a class="btn btn-xs btn-danger" href="<?php echo site_url("business_meeting/categories_manager/{$category->id}") ?>"><i class="fa fa-pencil"></i> </a>
                  <a class="btn btn-xs btn-success" onclick="return confirm('Deseja realmente deletar <?php echo $category->categorie_name?>?')" href="<?php echo site_url("business_meeting/categories_manager/{$category->id}/1") ?>"><i class="fa fa-trash-o"></i> </a>
                 </td>
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
           <?php if(!empty($category_obj->categorie_name)): ?>
              <h5>Editar <strong><?php echo @$category_obj->categorie_name ?></strong></h5>
           <?php  else:?>
              <h5>Nova Categoria</h5>
            <?php endif; ?>
           </div>
           <div class="widget-body">
              <form class="form-horizontal" method="POST" action="<?php echo site_url("business_meeting/categories_manager") ?>" role="form">
                <input type="hidden" value="<?php echo @$category_obj->id ?>" name="id" />
                <div class="form-group">
                  <label class="col-lg-2 control-label">Nome</label>
                  <div class="col-lg-10">
                    <input type="text" name="categorie_name" value="<?php echo @$category_obj->categorie_name ?>" class="form-control" placeholder="Nome da Categoria">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Cor da barra</label>
                  <div class="col-lg-10">
                    <select class="form-control" name="color">
                    <option>Selecione</option>
                      <?php foreach ($arr_color_categories as $color):?>
                        <option value="<?php echo $color ?>" <?php echo $color==@$category_obj->color?"selected='selected'":""?> ><?php echo ucfirst($color) ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <a href="<?php echo site_url("business_meeting/categories_manager")?>" class="btn btn-info">Novo</a>
                    <button type="submit" class="btn btn-success" value="salvar">Salvar</button>
                  </div>
                </div>

              </form>
           </div>
        </div>
      </div>
  </div>
</div>