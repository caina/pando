<?php foreach ($ads as $ad):
?>

<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
  <div class="main-box clearfix profile-box-contact">
    <div class="main-box-body clearfix">
      <div class="profile-box-header <?php echo $ad->aproved_bg?> clearfix">
        <img src="<?php echo !empty($ad->image_cover)?site_url("images/".$ad->image_cover->image_name."?width=110&height=110&force=true"):""?>" alt="" class="profile-img img-responsive">
        <h2><?php echo word_limiter($ad->title, 4)?></h2>
        <div class="job-position">
          R$ <?php echo $ad->formated_price?>
        </div>
        <ul class="contact-details">
          <!-- <li>
            <i class="fa fa-exclamation"></i>  <?php echo $ad->is_repasse == 1?'<span class="label label-warning">Repasse</span>':'<span class="label label-danger">Não aprovado Repasse</span>';?>
          </li> -->
          <li>
            <i class="fa fa-calendar-o"></i><?php echo $ad->formated_date?>
          </li>
          <li>
            <i class="fa fa-star"></i>
          <?php if ($ad->is_aproved):?>

          <?php echo anchor(site_url("lib_generic/method/anuncios/ad/create_new_ad/".$ad->id), "Editar");?>/

          <?php endif;?>

          <?php
          echo anchor(site_url("lib_generic/method/anuncios/ad/remove_ad/".$ad->id), 'Remover', array("onclick" => "return confirm('tem certeza que deseja remover?')"));
          ?>
          </li>
        </ul>
      </div>

      <div class="profile-box-footer clearfix">
        <a href="#">
          <span class="value">44</span>
          <span class="label">Visualizações</span>
        </a>
        <a href="#">
          <span class="value">91</span>
          <span class="label">Emails</span>
        </a>

        <div>
          <div class="row" id="payment_chose">
            <div class="col-md-4">
                <div class="form-group">
                  <form method="POST" action="<?php echo site_url("act/anuncios/ad/payment") ?>">
                    <input type="hidden" name='ad_id' value="<?php echo $ad->id ?>" />
                    <label>Topo</label>
                    <?php foreach ($prices as $price):?>
                    <div class="radio">
                      <input type="radio" name="price_id" value="<?php echo $price->id ?>" id="optionsRadios<?php echo $price->id."_".$ad->id ?>" value="option1" >
                      <label for="optionsRadios<?php echo $price->id."_".$ad->id ?>">
                        <?php echo $price->title ?>
                      </label>
                    </div>
                    <?php endforeach;?>
                    <button type="submit" class="btn btn-success">Comprar</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endforeach;?>
