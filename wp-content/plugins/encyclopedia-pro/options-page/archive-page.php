<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">
<tr>
	<th><label for="items_per_page"><?php printf(I18n::t('%s per page'), Post_Type_Labels::getItemPluralName()) ?></label></th>
	<td>
    <input type="number" name="items_per_page" id="items_per_page" value="<?php echo Options::get('items_per_page') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
    <p class="help"><?php printf(I18n::t('This option affects all %s archive pages.'), Post_Type_Labels::getEncyclopediaType()) ?></p>
  </td>
</tr>

<tr>
  <th><label for="prefix_filter_for_archives"><?php echo I18n::t('Prefix filter') ?></label></th>
  <td>
		<select name="prefix_filter_for_archives" id="prefix_filter_for_archives">
			<option value="1" <?php selected(Options::get('prefix_filter_for_archives')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('prefix_filter_for_archives')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the prefix filter above the first item in the archive.') ?></p>
	</td>
</tr>

<tr>
	<th><label for="prefix_filter_archive_depth"><?php echo I18n::t('Prefix filter depth') ?></label></th>
	<td>
    <input type="number" name="prefix_filter_archive_depth" id="prefix_filter_archive_depth" value="<?php echo Options::get('prefix_filter_archive_depth') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
    <p class="help"><?php echo I18n::t('The depth of the prefix filter is usually the number of rows with prefixes which are shown.') ?></p>
  </td>
</tr>
</table>
