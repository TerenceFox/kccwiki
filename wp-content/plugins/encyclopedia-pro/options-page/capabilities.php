<?php Namespace WordPress\Plugin\Encyclopedia;

#Capabilities::resetCapabilities();

# load post type object
$post_type = get_Post_Type_Object(Post_Type::post_type_name);
$arr_user_roles = Capabilities::getUserRoles();
$enable_advanced_capabilities = Options::get('enable_advanced_capabilities');
$capabilities = Capabilities::getPostTypeCapabilities();

?>
<label for="enable_advanced_capabilities">
  <input type="checkbox" name="enable_advanced_capabilities" id="enable_advanced_capabilities" value="1" <?php checked($enable_advanced_capabilities) ?> >
  <?php echo I18n::t('Enable advanced user capabilities') ?>
</label>

<div class="capability-editor">
<?php

# User capabilities
$arr_capability_descriptions = Array(
  $capabilities->edit_posts => sprintf(I18n::t('Edit and create %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->edit_others_posts => sprintf(I18n::t('Edit others %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->edit_private_posts => sprintf(I18n::t('Edit private %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->edit_published_posts => sprintf(I18n::t('Edit published %s'), Post_Type_Labels::getItemPluralName()),

  $capabilities->delete_posts => sprintf(I18n::t('Delete %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->delete_private_posts => sprintf(I18n::t('Delete private %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->delete_published_posts => sprintf(I18n::t('Delete published %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->delete_others_posts => sprintf(I18n::t('Delete others %s'), Post_Type_Labels::getItemPluralName()),

  $capabilities->publish_posts => sprintf(I18n::t('Publish %s'), Post_Type_Labels::getItemPluralName()),
  $capabilities->read_private_posts => sprintf(I18n::t('View others private %s'), Post_Type_Labels::getItemPluralName()),
);

# Taxonomies
foreach (get_Object_Taxonomies(Post_Type::post_type_name) as $taxonomy){
  $taxonomy = get_Taxonomy($taxonomy);
  $taxonomy_capability = empty($taxonomy->advanced_capability) ? $taxonomy->cap->manage_terms : $taxonomy->advanced_capability;
  if (empty($arr_capability_descriptions[$taxonomy_capability])){
    $arr_capability_descriptions[$taxonomy_capability] = sprintf(I18n::t('Manage %s'), $taxonomy->labels->name);
  }
}

# Show the user roles
foreach ($arr_user_roles as $role_name => $arr_role): ?>
  <h4><?php echo translate_User_Role($arr_role['name']) ?></h4>

  <?php foreach ($arr_capability_descriptions as $capability => $description): ?>
  <div class="capability-selection">
    <input type="hidden" name="capabilities[<?php echo $role_name ?>][<?php echo $capability ?>]" value="no" <?php disabled(!$enable_advanced_capabilities) ?> >
    <input type="checkbox" name="capabilities[<?php echo $role_name ?>][<?php echo $capability ?>]" id="capabilities-<?php echo $role_name ?>-<?php echo $capability ?>" value="yes" <?php checked(Capabilities::hasCap($role_name, $capability)); disabled(!$enable_advanced_capabilities) ?> >
    <label for="capabilities-<?php echo $role_name ?>-<?php echo $capability ?>"><?php echo $description ?></label>
  </div>
  <?php endforeach ?>

<?php endforeach ?>
</div>
