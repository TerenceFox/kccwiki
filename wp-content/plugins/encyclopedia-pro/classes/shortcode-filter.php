<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class ShortCode_Filter {
  private static
    $shortcode_callbacks;

  static function init(){
    #add_Action('template_redirect', Array(static::class, 'hijackAllShortcodes'), 100);
  }

  static function hijackAllShortcodes(){
    global $shortcode_tags;
    static $done = false;

    if (!$done){
      static::$shortcode_callbacks = $shortcode_tags;
      foreach ($shortcode_tags as $tag => &$callback){
        $callback = Array(static::class, 'renderShortcode');
      }
      unset($callback);
      $done = True;
    }
  }

  static function renderShortcode($atts, $content, $tag){
    $original_callback = isSet(static::$shortcode_callbacks[$tag]) ? static::$shortcode_callbacks[$tag] : False;

    if (is_Callable($original_callback)){
      $output = call_User_Func($original_callback, $atts, $content, $tag);
      $output = apply_Filters('shortcode_output', $output, $atts, $content, $tag);
      $output = apply_Filters("shortcode_output_{$tag}", $output, $atts, $content, $tag);
    }

    return $output;
  }

}

Shortcode_Filter::init();
