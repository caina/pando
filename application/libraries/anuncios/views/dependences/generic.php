<div class="form-group form-group-select2">
	<label><?php echo $data_generic->name?></label>
	<select name="vehicle[<?php echo $data_generic->table?>_id]" style="width:100%" class="form_enhaced"  id="<?php echo $data_generic->table?>" data_element="<?php echo $data_generic->watch_element?$data_generic->watch_element:''?>" data_watch="<?php echo $data_generic->watch?$data_generic->watch:''?>" data_table="<?php echo $data_generic->table?>">
		<option value="0">Selecione</option>
		<?php foreach ($data_generic->values as $value):?>
					<option value="<?php echo $value->id?>" <?php echo $value->id == $data_generic->selected?"selected='selected'":""?>><?php echo $value->title?></option>
		<?php endforeach;?>
</select>
</div>
