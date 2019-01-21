<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Tooltips {

  public static function init(){
    add_Action('init', Array(static::class, 'registerScripts'));
    add_Action('wp_enqueue_scripts', Array(static::class, 'enqueueScripts'));
  }

  public static function registerScripts(){
    wp_register_script('tooltipster', Core::$base_url.'/assets/js/tooltipster.bundle.min.js', Array('jquery'), '4.2.6', True);
    wp_register_script('encyclopedia-tooltips', Core::$base_url.'/assets/js/tooltips.js', Array('tooltipster'), Null, True);

    $js_parameters = Array(
      'animation_duration' => Options::get('tooltips_animation_duration'),
      'delay' => Options::get('tooltips_delay'),
    );

    $js_parameters = apply_Filters('encyclopedia_tooltip_js_parameters', $js_parameters);

    wp_localize_script('encyclopedia-tooltips', 'Encyclopedia_Tooltips', $js_parameters);
  }

  public static function enqueueScripts(){
    if (Options::get('activate_tooltips')){
      wp_enqueue_style('encyclopedia-tooltips', Core::$base_url.'/assets/css/tooltips.css');
      wp_enqueue_script('encyclopedia-tooltips');
    }
  }

}

Tooltips::init();
