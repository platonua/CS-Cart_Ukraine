{* $Id: platoncharge.tpl  $cas *}

<div class="control-group">
	<label class="control-label" for="merchantid">KEY выдан в Platon:</label>
  <div class="controls">
    <input type="text" name="payment_data[processor_params][platoncharge_shop_id]" id="platoncharge_shop_id" value="{$processor_params.platoncharge_shop_id}" class="input-text" />
  </div>
</div>

<div class="control-group">
	<label class="control-label" for="details">Password выдан в Platon:</label>
  <div class="controls">
    <input type="text" name="payment_data[processor_params][platoncharge_shop_pass]" id="secret" value="{$processor_params.platoncharge_shop_pass}" class="input-text" size="100" />
  </div>
</div>



