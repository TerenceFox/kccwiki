<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class AJAX_Requests {

  public static function init(){
    add_Action('wp_ajax_encyclopedia_search', Array(static::class, 'Search_Autocomplete'));
    add_Action('wp_ajax_nopriv_encyclopedia_search', Array(static::class, 'Search_Autocomplete'));
  }

  public static function Search_Autocomplete(){
    $search_phrase = trim($_REQUEST['term']);

    $arr_encyclopedia_items = get_Posts(Array(
      'numberposts' => 25,
      'post_type' => Post_Type::post_type_name,
      'post_title_like' => sprintf('%%%s%%', $search_phrase),
      'orderby' => 'title menu_order',
      'order' => 'ASC',
      'suppress_filters' => False
    ));

    foreach ($arr_encyclopedia_items as &$item){
      $item = Array(
        'label' => $item->post_title,
        'url' => get_Permalink($item->ID)
      );
    }

    echo JSON_Encode($arr_encyclopedia_items); exit;
  }

}

AJAX_Requests::init();
