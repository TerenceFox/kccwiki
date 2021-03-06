<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Taxonomies {

  static function init(){
    add_Action('init', Array(static::class, 'registerTaxonomies'), 9);
    add_Filter('nav_menu_meta_box_object', Array(static::class, 'changeTaxonomyMenuLabel'));
    add_Action('init', Array(static::class, 'addTaxonomyArchiveUrls'), 99);
  }

  static function registerTaxonomies(){
		if (Options::get('encyclopedia_categories')){
      static::registerCategoryTaxonomy();
		}

    if (Options::get('encyclopedia_tags')){
      static::registerTagTaxonomy();
    }
  }

  static function registerCategoryTaxonomy(){
    $taxonomy_name = 'encyclopedia-category';

    $labels = Array(
      'name' => I18n::t('Categories'),
      'singular_name' => I18n::t('Category'),
      'search_items' => I18n::t('Search Categories'),
      'all_items' => I18n::t('All Categories'),
      'parent_item' => I18n::t('Parent Category'),
      'parent_item_colon' => I18n::t('Parent Category:'),
      'edit_item' => I18n::t('Edit Category'),
      'update_item' => I18n::t('Update Category'),
      'add_new_item' => I18n::t('Add New Category'),
      'new_item_name' => I18n::t('New Category')
    );

    $args = Array(
      'label' => sprintf(I18n::t('%s: Categories'), Post_Type_Labels::getEncyclopediaType()),
      'labels' => $labels,
      'show_admin_column' => True,
      'hierarchical' => True,
      'show_ui' => True,
      'query_var' => True,
      'rewrite' => Array(
        'with_front' => False,
        'slug' => ltrim(sprintf(I18n::t('%s/category', 'URL slug'), Post_Type_Labels::getArchiveSlug()), '/')
      ),
      'advanced_capability' => 'manage_encyclopedia_categories',
      'show_in_rest' => True,
    );

    if (Options::get('enable_advanced_capabilities')){
      $advanced_post_type_capabilities = Capabilities::getPostTypeCapabilities();

      $args['capabilities'] = Array(
        'manage_terms' => 'manage_encyclopedia_categories',
        'edit_terms' => 'manage_encyclopedia_categories',
        'delete_terms' => 'manage_encyclopedia_categories',
        'assign_terms' => $advanced_post_type_capabilities->edit_posts
      );
    }

    register_Taxonomy($taxonomy_name, Post_Type::post_type_name, $args);
    add_Filter("{$taxonomy_name}_rewrite_rules", Array(static::class, 'addPrefixFilterRewriteRules'));
  }

  static function registerTagTaxonomy(){
    $taxonomy_name = 'encyclopedia-tag';

    $labels = Array(
      'name' => I18n::t('Tags'),
      'singular_name' => I18n::t('Tag'),
      'search_items' => I18n::t('Search Tags'),
      'all_items' => I18n::t('All Tags'),
      'edit_item' => I18n::t('Edit Tag'),
      'update_item' => I18n::t('Update Tag'),
      'add_new_item' => I18n::t('Add New Tag'),
      'new_item_name' => I18n::t('New Tag')
    );

    $args = Array(
      'label' => sprintf(I18n::t('%s: Tags'), Post_Type_Labels::getEncyclopediaType()),
      'labels' => $labels,
      'show_admin_column' => True,
      'hierarchical' => False,
      'show_ui' => True,
      'query_var' => True,
      'rewrite' => Array(
        'with_front' => False,
        'slug' => ltrim(sprintf(I18n::t('%s/tag', 'URL slug'), Post_Type_Labels::getArchiveSlug()), '/')
      ),
      'advanced_capability' => 'manage_encyclopedia_tags',
      'show_in_rest' => True,
    );

    if (Options::get('enable_advanced_capabilities')){
      $advanced_post_type_capabilities = Capabilities::getPostTypeCapabilities();

      $args['capabilities'] = Array(
        'manage_terms' => 'manage_encyclopedia_tags',
        'edit_terms' => 'manage_encyclopedia_tags',
        'delete_terms' => 'manage_encyclopedia_tags',
        'assign_terms' => $advanced_post_type_capabilities->edit_posts
      );
    }

    register_Taxonomy($taxonomy_name, Post_Type::post_type_name, $args);
    add_Filter("{$taxonomy_name}_rewrite_rules", Array(static::class, 'addPrefixFilterRewriteRules'));
  }

  public static function addPrefixFilterRewriteRules($rules){
    $current_filter = current_Filter();
    $filter_suffix_pos = StrPos($current_filter, '_rewrite_rules');
    if (!$filter_suffix_pos) return $rules;
    $taxonomy_name = SubStr($current_filter, 0, $filter_suffix_pos);
    $taxonomy = get_Taxonomy($taxonomy_name);
    if (!$taxonomy) return $rules;

    $new_rules = Array();
    $taxonomy_slug = $taxonomy->rewrite['slug'];

    $new_rules[ltrim(sprintf('%s/([^/]+)/prefix:([^/]+)/?$', $taxonomy_slug), '/')] = sprintf('index.php?%s=$matches[1]&prefix=$matches[2]', $taxonomy->name);
    $new_rules[ltrim(sprintf('%s/([^/]+)/prefix:([^/]+)/page/([0-9]{1,})/?$', $taxonomy_slug), '/')] = sprintf('index.php?%s=$matches[1]&prefix=$matches[2]&paged=$matches[3]', $taxonomy->name);

    $rules = Array_Merge($new_rules, $rules);

    return $rules;
  }

  static function changeTaxonomyMenuLabel($tax){
    if (isSet($tax->object_type) && in_Array(Post_Type::post_type_name, $tax->object_type)){
      $tax->labels->name = sprintf('%1$s (%2$s)', $tax->labels->name, Post_Type_Labels::getEncyclopediaType());
    }
    return $tax;
  }

  static function addTaxonomyArchiveUrls(){
    foreach (get_Object_Taxonomies(Post_Type::post_type_name) as $taxonomy){
      add_Action("{$taxonomy}_edit_form_fields", Array(static::class, 'printTaxonomyArchiveUrls'), 10, 3);
    }
  }

  static function printTaxonomyArchiveUrls($tag, $taxonomy){
    $taxonomy = get_Taxonomy($taxonomy);
    $archive_url = get_Term_Link(get_Term($tag->term_id, $taxonomy->name));
    $archive_feed = get_Term_Feed_Link($tag->term_id, $taxonomy->name);
    ?>
    <tr class="form-field">
      <th scope="row" valign="top"><?php echo I18n::t('Archive Url') ?></th>
      <td>
        <code><a href="<?php echo $archive_url ?>" target="_blank"><?php echo $archive_url ?></a></code><br>
        <span class="description"><?php printf(I18n::t('This is the URL to the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
      </td>
    </tr>
    <tr class="form-field">
      <th scope="row" valign="top"><?php echo I18n::t('Archive Feed') ?></th>
      <td>
        <code><a href="<?php echo $archive_feed ?>" target="_blank"><?php echo $archive_feed ?></a></code><br>
        <span class="description"><?php printf(I18n::t('This is the URL to the feed of the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
      </td>
    </tr>
    <?php
  }

}

Taxonomies::init();
