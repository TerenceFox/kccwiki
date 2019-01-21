<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">
<tr>
  <th><label for="enable_editor"><?php echo I18n::t('Text Editor') ?></label></th>
  <td>
		<select name="enable_editor" id="enable_editor">
			<option value="1" <?php selected(Options::get('enable_editor')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_editor')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the text editor.') ?></p>
	</td>
</tr>

<tr>
  <th><label for="enable_block_editor"><?php echo I18n::t('Block Editor') ?></label></th>
  <td>
		<select name="enable_block_editor" id="enable_block_editor">
			<option value="1" <?php selected(Options::get('enable_block_editor')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_block_editor')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the block editor (Gutenberg).') ?></p>
	</td>
</tr>

<tr>
  <th><label for="enable_excerpt"><?php echo I18n::t('Excerpt') ?></label></th>
  <td>
		<select name="enable_excerpt" id="enable_excerpt">
			<option value="1" <?php selected(Options::get('enable_excerpt')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_excerpt')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
    <p class="help"><?php echo I18n::t('Enables or disables the text excerpt input field.') ?></p>
	</td>
</tr>

<tr>
  <th><label for="enable_revisions"><?php echo I18n::t('Revisions') ?></label></th>
  <td>
		<select name="enable_revisions" id="enable_revisions">
			<option value="1" <?php selected(Options::get('enable_revisions')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_revisions')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables revisions.') ?></p>
	</td>
</tr>

<tr>
  <th><label for="enable_comments"><?php echo I18n::t('Comments &amp; Trackbacks') ?></label></th>
  <td>
		<select name="enable_comments" id="enable_comments">
			<option value="1" <?php selected(Options::get('enable_comments')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_comments')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables comments and trackbacks.') ?></p>
	</td>
</tr>

<tr>
  <th><label for="enable_thumbnail"><?php echo I18n::t('Featured Image') ?></label></th>
  <td>
		<select name="enable_thumbnail" id="enable_thumbnail">
			<option value="1" <?php selected(Options::get('enable_thumbnail')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_thumbnail')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the featured image.') ?></p>
	</td>
</tr>

<tr>
  <th><label for="enable_custom_fields"><?php echo I18n::t('Custom Fields') ?></label></th>
  <td>
		<select name="enable_custom_fields" id="enable_custom_fields">
			<option value="1" <?php selected(Options::get('enable_custom_fields')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('enable_custom_fields')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables custom fields.') ?></p>
	</td>
</tr>
</table>
