<?php foreach($users as $user):?>
   <tr>
    <td>
      <img src="img/samples/scarlet-159.png" alt="">
      <a href="#" class="user-link"><?php echo $user->name?></a>
       <?php if($logged_user->developer == 1): ?>
      <span class="user-subhead"><?php echo $user->company_name?></span>
       <?php endif; ?>
    </td>
    
    <td class="text-center">
      <span class="label label-default">aqui fica os </span>
    </td>
    <td>
      <a href="#"><?php echo $user->username?></a>
    </td>
    <td style="width: 20%;">
      <a href="#" class="table-link">
        <span class="fa-stack">
          <i class="fa fa-square fa-stack-2x"></i>
          <i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
        </span>
      </a>
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