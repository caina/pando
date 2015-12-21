 <!-- Sidebar starts -->
<div class="sidebar">
  <!-- Logo starts -->
  <div class="logo">
     <h1><a href="<?php echo site_url("dashboard")?>">PANDO</a></h1>
  </div>
  <!-- Logo ends -->

  <!-- Sidebar buttons starts -->
  <div class="sidebar-buttons text-center">
     <!-- User button -->
     <div class="btn-group">
       <a href="<?php echo site_url("dashboard") ?>" class="btn btn-black btn-xs"><i class="fa fa-user"></i></a>
       <a href="<?php echo site_url("dashboard") ?>" class="btn btn-danger btn-xs"><?php echo "{$logged_user->name}";?></a>
     </div>
     
     <!-- Logout button -->
     <div class="btn-group">
       <a href="<?php echo site_url("login/logout") ?>" class="btn btn-black btn-xs"><i class="fa fa-power-off"></i></a>
       <a href="<?php echo site_url("login/logout") ?>" class="btn btn-danger btn-xs">Logout</a>
     </div>
  </div>
  <!-- Sidebar buttons ends -->

<!-- Sidebar search -->
   <div class="sidebar-search">
      <form class="form-inline" role="form">
         <div class="input-group">
            <input type="text" class="form-control" id="s" placeholder="Type Here to Search...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
            </span>
          </div>
      </form>
   </div>
                  <!-- Sidebar search -->
                  
                  <!-- Sidebar navigation starts -->
				  
  <div class="sidebar-dropdown"><a href="#">Navigation</a></div>
    <div class="sidey">
       <ul class="nav">
           <!-- Main navigation. Refer Notes.txt files for reference. -->
           <?php if($logged_user->developer==1): ?>
            <li class="has_submenu">
               <a href="#">
                   <i class="fa fa-file"></i> Desenvolvedor
                   <!-- Icon to show dropdown -->
                   <span class="caret pull-right"></span>
               </a>
               <!-- Sub navigation -->
               <ul>
                   <!-- Use the class "active" in sub menu to hightlight current sub menu -->
                  <li><a href="<?php echo site_url("modules") ?>"><i class="fa fa-angle-double-right"></i>Modulos</a></li>
                  <li><a href="<?php echo site_url("company") ?>"><i class="fa fa-angle-double-right"></i>Empresas</a></li>
                  <li><a href="<?php echo site_url("user") ?>"><i class="fa fa-angle-double-right"></i>Usu&aacute;rios</a></li>
                  
               </ul>
           </li> 
         <?php endif; ?>

        <!-- colocar permissoes aqui -->
         <li class="has_submenu">
               <a href="#">
                   <i class="fa fa-cog"></i> Gerenciador
                   <span class="caret pull-right"></span>
               </a>
               <ul>
                  <li><a href="<?php echo site_url("user") ?>"><i class="fa fa-users"></i>Usuários</a></li>
               </ul>
           </li> 


          <?php 
          // dump($logged_user->modules);
          foreach($logged_user->modules as $key => $module):
            $class_li = "current";
            if(empty($module->submenu)){
              $link = site_url("screen/listing/{$module->mysql_table}");
            }else{
              $link = "#";
              $class_li .=" has_submenu";
            }

          ?>
            <li class="<?php echo $class_li ?>">
              <a href="<?php echo $link?>">
                <i class="fa <?php echo $module->icon ?>"></i>
                 <?php echo $module->name ?>

                  <?php if(!empty($module->submenu)): ?>
                    <span class="caret pull-right"></span>
                  <?php endif; ?>
              </a>

              <?php if(!empty($module->submenu)): ?>
                <ul>
                   <!-- Use the class "active" in sub menu to hightlight current sub menu -->
                  <?php foreach ($module->submenu as $submenu): ?>
                    <li>
                      <a href="<?php echo site_url("{$submenu->controller}/{$submenu->method}"); ?>">
                        <i class="fa fa-angle-double-right"></i>
                          <?php echo $submenu->label ?>
                        </a>
                      </li>
                  <?php endforeach; ?>
               </ul>
              <?php endif; ?>
            </li>


          <?php endforeach;?>                      
       </ul>               
    </div>
        <!-- Sidebar status starts -->
        <!-- <div class="sidebar-status hidden-xs">
           
        </div> -->
        <!-- Sidebar status ends -->
        <?php if($logged_user->developer==1):?>
        <div class="sidebar-status hidden-xs">
                     
           <div class="sidebar-status-item">
              <div class="sidebar-status-title">Disk Space <span class="pull-right"><?php echo bytes_converter(disk_free_space("/")); ?> / 20.00 GB</span></div>
              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo bytes_converter(disk_free_space("/"),false); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo bytes_converter(disk_free_space("/"),false) *100/20; ?>%">
                </div>
              </div>
           </div>

           <div class="sidebar-status-item">
              <div class="sidebar-status-title">Memória <span class="pull-right"><?php echo bytes_converter(memory_get_usage(true)); ?> / 500</span></div>
              <div class="progress progress-striped">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo bytes_converter(memory_get_usage(true)); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo bytes_converter(memory_get_usage(true),false)*100/500; ?>%">
                </div>
              </div>
           </div>
           
           
        </div>
        <?php endif; ?>

  </div>
            <!-- Sidebar ends -->