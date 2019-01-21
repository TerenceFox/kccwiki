<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">

<tr>
  <th><label for="embed_default_style"><?php echo I18n::t('Default style') ?></label></th>
  <td>
		<select name="embed_default_style" id="embed_default_style">
			<option value="1" <?php selected(Options::get('embed_default_style')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('embed_default_style')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the encyclopedia default CSS on the frontend.') ?></p>
	</td>
</tr>

</table>
