<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">

<tr>
  <th><label for="activate_tooltips"><?php echo I18n::t('Tooltips') ?></label></th>
  <td>
		<select name="activate_tooltips" id="activate_tooltips">
			<option value="1" <?php selected(Options::get('activate_tooltips')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('activate_tooltips')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the tooltips for item links on the frontend.') ?></p>
	</td>
</tr>

<tr>
	<th><label for="tooltips_animation_duration"><?php echo I18n::t('Animation duration') ?></label></th>
	<td>
    <input type="number" name="tooltips_animation_duration" id="tooltips_animation_duration" value="<?php echo Options::get('tooltips_animation_duration') ?>" min="0" max="<?php echo PHP_INT_MAX ?>" step="1">
    <?php echo I18n::t('ms', 'milliseconds time unit') ?>
    <p class="help"><?php echo I18n::t('The duration for the opening and closing animations, in milliseconds.') ?></p>
  </td>
</tr>

<tr>
	<th><label for="tooltips_delay"><?php echo I18n::t('Delay') ?></label></th>
	<td>
    <input type="number" name="tooltips_delay" id="tooltips_delay" value="<?php echo Options::get('tooltips_delay') ?>" min="0" max="<?php echo PHP_INT_MAX ?>" step="1">
    <?php echo I18n::t('ms', 'milliseconds time unit') ?>
    <p class="help"><?php echo I18n::t('Upon mouse interaction, this is the delay before the tooltip starts its opening and closing animations, in milliseconds.') ?></p>
  </td>
</tr>

</table>
