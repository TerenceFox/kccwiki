<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Options {
  const
    page_slug = 'encyclopedia-options',
    options_key = 'wp_plugin_encyclopedia';

  private static
    $arr_option_box = Array();

  public static function init(){
    # Option boxes
    static::$arr_option_box = Array( 'main' => Array(), 'side' => Array() );
    add_Action('admin_menu', Array(static::class, 'addOptionsPage'));
  }

  public static function addOptionsPage(){
    $handle = add_Options_Page(
      sprintf(I18n::t('%s Settings'), Post_Type_Labels::getEncyclopediaType()),
      Post_Type_Labels::getEncyclopediaType(),
      'manage_options',
      static::page_slug,
      Array(static::class, 'printOptionsPage')
    );

    # Add JavaScript to this handle
    add_Action('load-' . $handle, Array(static::class, 'loadOptionsPage'));

    # Add option boxes
    static::addOptionBox(I18n::t('Labels'), Core::$plugin_folder.'/options-page/post-type-labels.php');
    static::addOptionBox(__('Appearance'), Core::$plugin_folder.'/options-page/appearance.php');
    static::addOptionBox(I18n::t('Features'), Core::$plugin_folder.'/options-page/features.php');
    static::addOptionBox(I18n::t('Taxonomies'), Core::$plugin_folder.'/options-page/taxonomies.php');
    static::addOptionBox(I18n::t('Archives'), Core::$plugin_folder.'/options-page/archive-page.php');
    static::addOptionBox(I18n::t('Search'), Core::$plugin_folder.'/options-page/search.php');
    static::addOptionBox(I18n::t('Single View'), Core::$plugin_folder.'/options-page/single-page.php');
    static::addOptionBox(I18n::t('Cross Linking'), Core::$plugin_folder.'/options-page/cross-linking.php');
    static::addOptionBox(I18n::t('Tooltips'), Core::$plugin_folder.'/options-page/tooltips.php');
    static::addOptionBox(I18n::t('User Permissions'), Core::$plugin_folder.'/options-page/capabilities.php', 'main');
    static::addOptionBox(I18n::t('Archive URLs'), Core::$plugin_folder.'/options-page/archive-link.php', 'side');
    static::addOptionBox(I18n::t('License'), Core::$plugin_folder.'/options-page/license.php', 'side');
  }

  public static function getOptionsPageUrl($parameters = Array()){
    $url = add_Query_Arg(Array('page' => static::page_slug), Admin_Url('options-general.php'));
    if (is_Array($parameters) && !empty($parameters)) $url = add_Query_Arg($parameters, $url);
    return $url;
  }

  public static function loadOptionsPage(){
    flush_Rewrite_Rules();

    # If this is a Post request to save the options
    if (static::saveOptions()){
      WP_Redirect(static::getOptionsPageUrl(Array('options_saved' => 'true')));
    }

    WP_Enqueue_Style('dashboard');

    WP_Enqueue_Style(static::page_slug, Core::$base_url . '/options-page/options-page.css');
    WP_Enqueue_Script(static::page_slug, Core::$base_url . '/options-page/options-page.js', Array('jquery'), Null, True);

    # Remove incompatible JS Libs
    WP_Dequeue_Script('post');
  }

  public static function printOptionsPage(){
    include Core::$plugin_folder.'/options-page/options-page.php';
  }

  public static function addOptionBox($title, $include_file, $column = 'main'){
    # if the box file does not exists we are wrong here
    if (!is_File($include_file)) return False;

    # Title cannot be empty
    if (empty($title)) $title = '&nbsp;';

    # Column (can be 'side' or 'main')
    if ($column != 'main') $column = 'side';

    # Add a new box
    static::$arr_option_box[$column][] = (Object) Array(
      'title' => $title,
      'file' => $include_file
    );
  }

  public static function get($key = Null, $default = False){
    static $arr_options;

    # Read Options
    if (empty($arr_options)){
      $saved_options = get_Option(static::options_key);
      setType($saved_options, 'ARRAY');
      $default_options = static::getDefaultOptions();
      $arr_options = Array_Merge($default_options, $saved_options);
    }

    # Locate the option
    if (empty($key))
      return $arr_options;
    elseif (isSet($arr_options[$key]))
      return $arr_options[$key];
    else
      return $default;
  }

  public static function saveOptions(){
    # Check if this is a post request
    if (empty($_POST)) return False;

    # Check the nonce
    check_Admin_Referer('save_encyclopedia_options');

    # Add Capabilities
    if (!empty($_POST['capabilities']) && is_Array($_POST['capabilities'])){
      foreach ($_POST['capabilities'] as $role_name => $arr_role){
        foreach ($arr_role as $capability => $yes_no){
          Capabilities::setCap($role_name, $capability, $yes_no == 'yes');
        }
      }
    }
    unset($_POST['capabilities']);

    # Clean the Post array
    $options = stripSlashes_Deep($_POST);
    $options = Array_Filter($options, function($value){ return $value == '0' || !empty($value); });

    # Save Options
    update_Option(static::options_key, $options);

    return True;
  }

  public static function getDefaultOptions(){
    return Array(
      'encyclopedia_type' => I18n::t('Encyclopedia'),
      'item_singular_name' => I18n::t('Entry'),
      'item_plural_name' => I18n::t('Entries'),
      'archive_url_slug' => I18n::t('encyclopedia', 'URL slug'),
      'item_url_slug' => I18n::t('encyclopedia', 'URL slug'),
      'embed_default_style' => True,

      'enable_editor' => True,
      'enable_block_editor' => False,
      'enable_excerpt' => True,
      'enable_revisions' => False,
      'enable_comments' => False,
      'enable_thumbnail' => True,
      'enable_custom_fields' => False,

      'encyclopedia_categories' => True,
      'encyclopedia_tags' => True,

      'items_per_page' => get_Option('posts_per_page'),
      'prefix_filter_for_archives' => True,
      'prefix_filter_archive_depth' => 3,

      'redirect_user_to_search_item' => True,
      'autocomplete_min_length' => 2,
      'autocomplete_delay' => 400,

      'related_items' => 'below',
      'number_of_related_items' => 5,
      'prefix_filter_for_singulars' => True,
      'prefix_filter_singular_depth' => 3,

      'link_items' => Array(),
      'link_item_target' => Array(),
      'link_items_in_text_widget' => False,
      'link_item_min_length' => 0,
      'link_complete_words_only' => False,
      'link_items_case_sensitive' => False,
      'link_items_once' => False,
      'link_item_in_its_content' => False,
      'cross_link_title_length' => apply_Filters('excerpt_length', 55),

      'cross_linker_priority' => 'after_shortcodes',

      'activate_tooltips' => True,
      'tooltips_animation_duration' => 350,
      'tooltips_delay' => 300,

      'enable_advanced_capabilities' => False,

      'update_username' => '',
      'update_password' => ''
    );
  }

}

Options::init();
