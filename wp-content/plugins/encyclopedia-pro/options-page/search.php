<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">
<tr>
  <th><label for="redirect_user_to_search_item"><?php echo I18n::t('Query matches directly') ?></label></th>
  <td>
		<select name="redirect_user_to_search_item" id="redirect_user_to_search_item">
			<option value="1" <?php selected(Options::get('redirect_user_to_search_item')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('redirect_user_to_search_item')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enable this feature to redirect the user to the matched item if the user searched for an exact title.') ?></p>
	</td>
</tr>

<tr>
	<th><label for="autocomplete_min_length"><?php echo I18n::t('Autocomplete min length') ?></label></th>
	<td>
    <input type="number" name="autocomplete_min_length" id="autocomplete_min_length" value="<?php echo Options::get('autocomplete_min_length') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1"> <?php echo I18n::t('characters', 'characters unit') ?>
    <p class="help"><?php echo I18n::t('The minimum number of characters a user must type before suggestions will be shown.') ?></p>
  </td>
</tr>

<tr>
	<th><label for="autocomplete_delay"><?php echo I18n::t('Autocomplete delay') ?></label></th>
	<td>
    <input type="number" name="autocomplete_delay" id="autocomplete_delay" value="<?php echo Options::get('autocomplete_delay') ?>" min="0" max="<?php echo PHP_INT_MAX ?>" step="1"> <?php echo I18n::t('ms', 'milliseconds time unit') ?>
    <p class="help"><?php echo I18n::t('The delay in milliseconds between a keystroke occurs and the suggestions will be shown.') ?></p>
  </td>
</tr>
</table>
