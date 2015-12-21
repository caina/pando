  <?php
    $block_name="";
    $first_block = true;
    $element_used = array();
  ?>

  <?php foreach ($logged_user->modules as $module):?>
    <?php if(in_array($module->menu_id, $element_used)) continue; ?>

    <?php if($module->block_name != $block_name):?>
      <li class="nav-header <?php echo $first_block?'nav-header-first':'' ?> hidden-sm hidden-xs">
        <?php echo $module->block_name ?>
      </li>
      <?php $block_name = $module->block_name;$first_block=false?>
    <?php endif; ?>

  <li>

    <a 
      href=" <?php echo !empty($module->menu_id)&& $module->menu_count > 1?'#': site_url($module->controller."/".$module->method)  ?>" 
      class="<?php echo !empty($module->menu_id) && $module->menu_count > 1?'dropdown-toggle':'';?>
             <?php echo $logged_user->selected_menu==$module->id? "active":''?>
      ">
      
      <i class="fa <?php echo $module->icon ?>"></i>
      <span><?php echo !empty($module->menu_id) && $module->menu_count > 1?$module->menu_label:$module->name ?></span>
      <?php if(!empty($module->badge)): ?>
        <span class="label label-primary label-circle pull-right"><?php echo $module->badge ?></span>
      <?php endif; ?>
      

      <?php if(!empty($module->menu_id) && $module->menu_count > 1): ?>
        <i class="fa fa-angle-right drop-icon"></i>

        <ul class="submenu">
          <?php foreach ($logged_user->modules as $submenu_modules):?>
            <?php if($submenu_modules->menu_id == $module->menu_id): ?>
              <li>
                <a href="<?php echo site_url($submenu_modules->controller."/".$submenu_modules->method) ?>"
                   class="<?php //echo $logged_user->selected_menu==$submenu_modules->id? "active":'' ?>"
                  >
                  <?php echo $submenu_modules->label ?>
                </a>
              </li>
              <?php $element_used[] = $submenu_modules->menu_id ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </a>

  </li>
<?php endforeach; ?>