<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Advanced_Custom_Fields {

  public static function init(){
    add_Action('plugins_loaded', Array(static::class, 'registerContentFilter'));
  }

  public static function registerContentFilter(){
    if (!is_Admin()){
      $cross_linker_priority = Options::get('cross_linker_priority') == 'before_shortcodes' ? 10.5 : 15;
      add_Filter('acf/format_value_for_api/type=wysiwyg', Array(static::class, 'filterFieldValue'), $cross_linker_priority, 3);
      add_Filter('acf/format_value_for_api/type=textarea', Array(static::class, 'filterTextValue'), $cross_linker_priority, 3);
      add_Filter('acf/format_value_for_api/type=text', Array(static::class, 'filterTextValue'), $cross_linker_priority, 3);
    }
  }

  public static function filterFieldValue($content, $post_id, $field){
    $post = get_Post($post_id);

    # Check if Cross-Linking is activated for this post type
    if (in_Array($post->post_type, Options::get('link_items')) && apply_Filters('encyclopedia_link_items_in_post', True, $post)){
      $content = Core::addCrossLinks($content, $post);
    }

    return $content;
  }

  public static function filterTextValue($content, $post_id, $field){
    $compatible_formattings = ['html', 'br'];

    if (in_Array($field['formatting'], $compatible_formattings)){
      $content = static::filterFieldValue($content, $post_id, $field);
    }

    return $content;
  }

}

Advanced_Custom_Fields::init();
