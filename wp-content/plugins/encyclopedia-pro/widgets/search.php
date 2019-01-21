<?php Namespace WordPress\Plugin\Encyclopedia;

use WP_Widget;

class Search_Widget extends WP_Widget {

  function __construct(){
    parent::__construct (
      'encyclopedia_search',
      sprintf(I18n::t('%s Search'), Post_Type_Labels::getEncyclopediaType()),
      Array('description' => sprintf(I18n::t('A search form for your %s.'), Post_Type_Labels::getItemPluralName()))
    );
  }

  static function registerWidget(){
    if (doing_Action('widgets_init'))
      register_Widget(static::class);
    else
      add_Action('widgets_init', Array(static::class, __FUNCTION__));
  }

  function getDefaultOptions(){
    # Default settings
    return Array(
      'title' => '',
      'search_mode' => 'normal'
    );
  }

  function loadOptions(&$options){
    setType($options, 'ARRAY');
    $options = Array_Filter($options);
    $options = Array_Merge($this->getDefaultOptions(), $options);
    setType($options, 'OBJECT');
  }

  function Form($options){
    $this->loadOptions($options);
    ?>

    <p>
      <label for="<?php echo $this->get_Field_Id('title') ?>"><?php _e('Title:') ?></label>
      <input type="text" id="<?php echo $this->get_Field_Id('title') ?>" name="<?php echo $this->get_Field_Name('title')?>" value="<?php echo esc_Attr($options->title) ?>" class="widefat">
    </p>

    <h4><?php echo I18n::t('Search mode:') ?></h4>

    <p>
      <label for="<?php echo $this->get_Field_Id('search_mode_normal') ?>">
        <input type="radio" id="<?php echo $this->get_Field_Id('search_mode_normal') ?>" name="<?php echo $this->get_Field_Name('search_mode')?>" value="normal" <?php checked($options->search_mode, 'normal') ?> >
        <strong><?php echo I18n::t('Normal') ?></strong>: <?php printf(I18n::t('Will match all %s which contains the search phrase in the title or the content field.'), Post_Type_Labels::getItemPluralName()) ?>
      </label>
    </p>
    <p>
      <label for="<?php echo $this->get_Field_Id('search_mode_prefix') ?>">
        <input type="radio" id="<?php echo $this->get_Field_Id('search_mode_prefix') ?>" name="<?php echo $this->get_Field_Name('search_mode')?>" value="prefix" <?php checked($options->search_mode, 'prefix') ?> >
        <strong><?php echo I18n::t('Prefix') ?></strong>: <?php printf(I18n::t('Will match all %s which title field starts with the search phrase.'), Post_Type_Labels::getItemPluralName()) ?>
      </label>
    </p>
    <p>
      <label for="<?php echo $this->get_Field_Id('search_mode_exact') ?>">
        <input type="radio" id="<?php echo $this->get_Field_Id('search_mode_exact') ?>" name="<?php echo $this->get_Field_Name('search_mode')?>" value="exact" <?php checked($options->search_mode, 'exact') ?> >
        <strong><?php echo I18n::t('Exact') ?></strong>: <?php printf(I18n::t('Will match all %s which title or content field matches exactly the search phrase.'), Post_Type_Labels::getItemPluralName()) ?>
      </label>
    </p>

    <?php
  }

  function Widget($widget, $options){
    # Load widget args
    setType($widget, 'OBJECT');

    # Load options
    $this->loadOptions($options);

    # Load widget title
    $widget->title = apply_Filters('widget_title', $options->title, (Array) $options, $this->id_base);

    # Enqueue Javascript in Website footer
    WP_Enqueue_Script('encyclopedia-search', Core::$base_url.'/assets/js/search-form.js', Array('jquery-ui-autocomplete'), Null, True);
    WP_Localize_Script('encyclopedia-search', 'Encyclopedia_Search', apply_Filters('encyclopedia-search-widget', Array(
      'ajax_url' => add_Query_Arg(Array('action' => 'encyclopedia_search'), Admin_URL('admin-ajax.php')),
      'minLength' => Options::get('autocomplete_min_length'),
      'delay' => Options::get('autocomplete_delay')
    )));

    # Display Widget
    echo $widget->before_widget;
    !empty($widget->title) && print($widget->before_title . $widget->title . $widget->after_title);
    echo Template::load('searchform-encyclopedia.php', Array(
      'widget' => $widget,
      'options' => $options
    ));
    echo $widget->after_widget;
  }

}

Search_Widget::registerWidget();
