<form method="POST" class='permission_update' id="permission_<?php echo $role->id ?>" action="<?php echo site_url("permissions/update_role/".$role->id) ?>">
  <div class="col-lg-4 col-md-5 col-sm-6">
    <div class="main-box clearfix profile-box-menu">
      <div class="main-box-body clearfix">
        <div class="profile-box-header green-bg clearfix" style="text-shadow: 1px 1px rgb(100, 97, 97);background-image: url('<?php  echo base_url("assets/img/accept_panda.jpg") //http://lorempixel.com/g/400/400/city/?>');">
          <h2>
          <?php echo $role->title ?>
          </h2>
        </div>
        
        <div class="profile-box-content clearfix">
            <ul class="menu-items">
              <?php foreach($permissions as $permission): ?>
                <li>
                  <a href="#"  class="clearfix checkbox_marquer" data-form="permission_<?php echo $role->id ?>">
                    <i class="fa <?php echo $permission->icon ?> fa-lg"></i> <?php echo $permission->name ?>
                    <span class="pull-right"><input type="checkbox" <?php echo $permission->selected >0?"checked='checked'":'' ?> name="permission[]" value="<?php echo $permission->id ?>" ></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- onclick="select_permission();return false" -->