<?php foreach($users as $user):?>
   <tr>
    <td>
      <img src="<?php echo !empty($user->facebook_id)? "//graph.facebook.com/".$user->facebook_id."/picture?type=small" : base_url("assets/img/avatar_160_160.gif") ?>" alt="">
      <a href="#" class="user-link"><?php echo $user->name?></a>
       <?php if($logged_user->developer == 1): ?>
      <span class="user-subhead"><?php echo $user->company_name?></span>
       <?php endif; ?>
    </td>
    
    <td class="text-center">
        <select class="form-control user_permission">
          <option value="<?php echo $user->id."-0" ?>">Selecione</option>
          <?php foreach ($user->permissions as $permission):?>
            <option value="<?php echo $user->id."-".$permission->id ?>" <?php echo $permission->id == $user->user_role?"selected='selected'":"" ?>><?php echo $permission->title ?></option>
          <?php endforeach; ?>
        </select>
    </td>
    <td>
      <a href="#"><?php echo $user->username?></a>
    </td>
    <td style="width: 20%;">
      
      <a href="<?php echo site_url('user/index/'.$user->id) ?>" class="table-link">
        <span class="fa-stack">
          <i class="fa fa-square fa-stack-2x"></i>
          <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
        </span>
      </a>
      <a href="<?php echo site_url('user/remove/'.$user->id) ?>" class="table-link danger">
        <span class="fa-stack">
          <i class="fa fa-square fa-stack-2x"></i>
          <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
        </span>
      </a>
    </td>
  </tr>
<?php endforeach; ?>