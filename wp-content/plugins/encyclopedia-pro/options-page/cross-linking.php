<?php Namespace WordPress\Plugin\Encyclopedia;

$link_items = is_Array(Options::get('link_items')) ? Options::get('link_items') : Array();
$link_target = Options::get('link_item_target');
$exclude_post_type = Array('attachment', 'wp_block');
$post_types = get_Post_Types(Array('show_ui' => True), 'objects');

?>

<table class="form-table">

<?php foreach ($post_types as $type): if (in_Array($type->name, $exclude_post_type)) continue; ?>
<tr>
  <th><?php echo $type->label ?></th>
  <td>
    <label for="link_items_in_<?php echo $type->name ?>">
      <input type="checkbox" name="link_items[]" value="<?php echo $type->name ?>" id="link_items_in_<?php echo $type->name ?>" <?php checked(in_Array($type->name, $link_items)) ?> >
      <?php printf(I18n::t('Add links in %s'), $type->label) ?>
    </label><br>

    <label for="link_item_target_<?php echo $type->name ?>">
      <input type="checkbox" name="link_item_target[<?php echo $type->name ?>]" value="_blank" id="link_item_target_<?php echo $type->name ?>" <?php checked(isSet($link_target[$type->name])) ?> >
      <?php echo I18n::t('Open link in a new window/tab') ?>
    </label>
  </td>
</tr>
<?php endforeach ?>

<tr>
  <th><?php echo I18n::t('Text Widget') ?></th>
  <td>
    <label for="link_items_in_text_widget">
      <input type="checkbox" name="link_items_in_text_widget" value="1" id="link_items_in_text_widget" <?php checked(Options::get('link_items_in_text_widget')) ?> >
      <?php echo I18n::t('Add links in the default text widget') ?>
    </label><br>
  </td>
</tr>

<tr>
	<th><label for="link_item_min_length"><?php echo I18n::t('Minimum length') ?></label></th>
	<td>
		<input type="number" name="link_item_min_length" id="link_item_min_length" value="<?php echo esc_Attr(Options::get('link_item_min_length')) ?>">
    <?php echo I18n::t('characters') ?>
		<p class="help"><?php echo I18n::t('The minimum length of cross linked words. Shorter words <u>will not be</u> cross linked automatically.') ?></p>
	</td>
</tr>

<tr>
  <th><?php echo I18n::t('Filter order') ?></th>
  <td>
    <label for="cross_linker_priority">
      <select id="cross_linker_priority" name="cross_linker_priority">
        <option value="before_shortcodes" <?php selected(Options::get('cross_linker_priority') == 'before_shortcodes') ?> ><?php echo I18n::t('Before shortcodes') ?></option>
        <option value="" <?php selected(Options::get('cross_linker_priority') == 'after_shortcodes') ?> ><?php echo I18n::t('After shortcodes') ?></option>
      </select>
    </label>
    <p class="help"><?php echo I18n::t('By default the cross links should be added to the content after rendering all shortcodes. This works not for shortcodes which are calling the "the_content" filter while rendering. In this case please change this setting to "Before shortcodes".') ?></p>
  </td>
</tr>

<tr>
  <th><?php echo I18n::t('Complete words') ?></th>
  <td>
    <label for="link_complete_words_only">
      <input type="checkbox" name="link_complete_words_only" value="1" id="link_complete_words_only" <?php checked(Options::get('link_complete_words_only')) ?> >
      <?php echo I18n::t('Link complete words only.') ?>
    </label>
  </td>
</tr>

<tr>
  <th><?php echo I18n::t('Case sensitivity') ?></th>
  <td>
    <label for="link_items_case_sensitive">
      <input type="checkbox" name="link_items_case_sensitive" value="1" id="link_items_case_sensitive" <?php checked(Options::get('link_items_case_sensitive')) ?> >
      <?php echo I18n::t('Link items case sensitive.') ?>
    </label>
  </td>
</tr>

<tr>
  <th><?php echo I18n::t('First match only') ?></th>
  <td>
    <label for="link_items_once">
      <input type="checkbox" name="link_items_once" value="1" id="link_items_once" <?php checked(Options::get('link_items_once')) ?> >
      <?php echo I18n::t('Link the first match of each item only.') ?>
    </label>
  </td>
</tr>

<tr>
  <th><?php echo I18n::t('Recursion') ?></th>
  <td>
    <label for="link_item_in_its_content">
      <input type="checkbox" name="link_item_in_its_content" value="1" id="link_item_in_its_content" <?php checked(Options::get('link_item_in_its_content')) ?> >
      <?php echo I18n::t('Link the item in its own content.') ?>
    </label>
  </td>
</tr>

<tr>
	<th><label for="cross_link_title_length"><?php echo I18n::t('Link title length') ?></label></th>
	<td>
		<input type="number" name="cross_link_title_length" id="cross_link_title_length" value="<?php echo esc_Attr(Options::get('cross_link_title_length')) ?>">
    <?php echo I18n::t('words') ?>
		<p class="help"><?php echo I18n::t('The number of words of the linked item used as link title. This option does not affect manually created excerpts.') ?></p>
	</td>
</tr>

</table>
