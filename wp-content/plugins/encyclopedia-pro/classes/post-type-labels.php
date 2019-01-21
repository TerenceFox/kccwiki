<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Post_Type_Labels {

  static function getEncyclopediaType(){
    $type = Options::get('encyclopedia_type');
    $type = WPML::t($type, 'Encyclopedia type');
    return $type;
  }

  static function getArchiveSlug(){
    $slug = Options::get('archive_url_slug');
    # Do not translate this! It would break the permalinks!
    #$slug = WPML::t($slug, 'Archive URL slug');
    return $slug;
  }

  static function getItemSlug(){
    $slug = Options::get('item_url_slug');
    # Do not translate this! It would break the permalinks!
    #$slug = WPML::t($slug, 'Item slug');
    return $slug;
  }

  static function getItemSingularName(){
    $name = Options::get('item_singular_name');
    $name = WPML::t($name, 'Item singular name');
    return $name;
  }

  static function getItemPluralName(){
    $name = Options::get('item_plural_name');
    $name = WPML::t($name, 'Item plural name');
    return $name;
  }

}
