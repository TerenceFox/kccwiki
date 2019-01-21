<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class I18n {
  const
    textdomain = 'encyclopedia';

  private static
    $loaded = False;

  private static function loadTextDomain(){
    $locale = apply_Filters('plugin_locale', get_Locale(), static::textdomain);
    $language_folder = Core::$plugin_folder.'/languages';
    load_TextDomain (static::textdomain, "{$language_folder}/{$locale}.mo");
    load_Plugin_TextDomain(static::textdomain);
    static::$loaded = True;
  }

  public static function getTextDomain(){
    return static::textdomain;
  }

  public static function t($text, $context = Null){
    # Load text domain
    if (!static::$loaded) static::loadTextDomain();

    # Translate the string $text with context $context
    if (empty($context))
      return translate($text, static::textdomain);
    else
      return translate_With_GetText_Context($text, $context, static::textdomain);
  }

}
