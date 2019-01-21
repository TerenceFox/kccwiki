<?php Namespace WordPress\Plugin\Encyclopedia;

use WP_Query;

abstract class Core {
  public static
    $base_url, # url to the plugin directory
    $plugin_file, # the main plugin file
    $plugin_folder; # the path to the folder the plugin files contains

  public static function init($plugin_file){
    static::$plugin_file = $plugin_file;
    static::$plugin_folder = DirName(static::$plugin_file);

    Updates::init(static::$plugin_file, Options::get('update_username'), Options::get('update_password'));

    register_Activation_Hook(static::$plugin_file, Array(static::class, 'installPlugin'));
    register_Deactivation_Hook(static::$plugin_file, Array(static::class, 'uninstallPlugin'));
    add_Action('plugins_loaded', Array(static::class, 'loadBaseUrl'));
    add_Action('loop_start', Array(static::class, 'printPrefixFilter'));
    add_Action('wp_enqueue_scripts', Array(static::class, 'enqueueScripts'));
    add_action('wp_head', Array(static::class, 'setNoindexTag'));
    add_Filter('get_the_archive_title', Array(static::class, 'filterArchiveTitle'));
    add_Filter('posts_results', Array(static::class, 'checkSearchRequestForExactMatch'), 10, 2);
  }

  public static function loadBaseURL(){
    $absolute_plugin_folder = RealPath(static::$plugin_folder);

    if (StrPos($absolute_plugin_folder, ABSPATH) === 0)
      static::$base_url = site_url().'/'.SubStr($absolute_plugin_folder, Strlen(ABSPATH));
    else
      static::$base_url = Plugins_Url(BaseName(static::$plugin_folder));

    static::$base_url = Str_Replace("\\", '/', static::$base_url); # Windows Workaround
  }

  public static function installPlugin(){
    Taxonomies::registerTaxonomies();
    Post_Type::registerPostType();
    flush_Rewrite_Rules();
  }

  public static function uninstallPlugin(){
    flush_Rewrite_Rules();
  }

  public static function enqueueScripts(){
    if (Options::get('embed_default_style'))
      WP_Enqueue_Style('encyclopedia', static::$base_url.'/assets/css/encyclopedia.css');
  }

  public static function checkSearchRequestForExactMatch($posts, $query){
    if (count($posts) == 1 && $query->is_Search() && $query->is_Post_Type_Archive(Post_Type::post_type_name) && Options::get('redirect_user_to_search_item')){
      $match_post = reset($posts);
      if (strcasecmp($query->get('s'), $match_post->post_title) == 0){
        $redirect_url = get_Permalink($match_post);
        WP_Redirect($redirect_url);
        exit;
      }
    }

    return $posts;
  }

  public static function isEncyclopediaArchive($query){
		if ($query->is_post_type_archive || $query->is_tax){
      $encyclopedia_taxonomies = get_Object_Taxonomies(Post_Type::post_type_name);
			if ($query->is_Post_Type_Archive(Post_Type::post_type_name) || (!empty($encyclopedia_taxonomies) && $query->is_Tax($encyclopedia_taxonomies))){
				return True;
			}
		}
		return False;
	}

  public static function isEncyclopediaSearch($query){
    if ($query->is_search){
      # Check post type
			if ($query->get('post_type') == Post_Type::post_type_name) return True;

      # Check taxonomies
      $encyclopedia_taxonomies = get_Object_Taxonomies(Post_Type::post_type_name);
      if (!empty($encyclopedia_taxonomies) && $query->is_Tax($encyclopedia_taxonomies)) return True;
    }
    return False;
  }

  public static function addCrossLinks($content, $post = Null){
    $post_id = isSet($post->ID) ? $post->ID : Null;
    $post_type = isSet($post->post_type) ? $post->post_type : Null;

    # Start Cross Linker
    $cross_linker = new Cross_Linker();
    $cross_linker->setSkipElements(apply_Filters('encyclopedia_cross_linking_skip_elements', Array('a', 'script', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'button', 'textarea', 'select', 'style', 'pre', 'code', 'kbd', 'tt')));
    if (!$cross_linker->loadContent($content))
      return $content;

    # Link target, Complete words only, link terms once
    $link_target = Options::get('link_item_target');
    $cross_linker->setLinkTarget(isSet($link_target[$post_type]) ? $link_target[$post_type] : '_self');
    $cross_linker->linkCompleteWordsOnly(Options::get('link_complete_words_only'));
    $cross_linker->linkWordsCaseSensitive(Options::get('link_items_case_sensitive'));
    $cross_linker->replacePhrasesOnce(Options::get('link_items_once'));

    # Go through all encyclopedia items
    for ($current_page = $max_num_pages = 1; $current_page <= $max_num_pages; $current_page++){
      # Build the Query
      $query_args = Array(
        'post_type' => Post_Type::post_type_name,
        'post__not_in' => Options::get('link_item_in_its_content') ? Null : Array($post_id),
        'posts_per_page' => apply_Filters('encyclopedia_cross_linking_posts_per_page', 100),
        'paged' => $current_page,
        'orderby' => 'post_title_length',
        'order' => 'DESC',
        'min_title_length' => (int) Options::get('link_item_min_length'),
        'cache_results' => False
      );

      # Query the items
      $query = new WP_Query($query_args);

      # Set the max_pages
      $max_num_pages = $query->max_num_pages;

      # Create the links
      while ($query->have_Posts()){
        $item = $query->next_Post();

        if (apply_Filters('encyclopedia_link_item_in_content', True, $item, $post, $cross_linker)){
          $cross_linker->linkPhrase($item->post_title, Array(static::class, 'getCrossLinkItemDetails'), $item);
        }
      }
    }

    # Overwrite the content with the parsers document which contains the links to each term
    $content = $cross_linker->getParserDocument();

    return $content;
	}

  public static function getCrossLinkItemDetails($item){
    return (Object) Array(
      'phrase' => $item->post_title,
      'title' => static::getCrossLinkItemTitle($item),
      'url' => get_Permalink($item),
    );
  }

  public static function getCrossLinkItemTitle($post){
    $title = $more = $length = False;

    if (empty($post->post_excerpt)){
      $more = apply_Filters('encyclopedia_link_title_more', '&hellip;');
      #$more = HTML_Entity_Decode($more, ENT_QUOTES, 'UTF-8');
      $length = apply_Filters('encyclopedia_link_title_length', Options::get('cross_link_title_length'));
      $title = strip_Shortcodes($post->post_content);
      $title = WP_Strip_All_Tags($title);
      #$title = HTML_Entity_Decode($title, ENT_QUOTES, 'UTF-8');
      $title = WP_Trim_Words($title, $length, $more);
    }
    else {
      $title = WP_Strip_All_Tags($post->post_excerpt, True);
      #$title = HTML_Entity_Decode($title, ENT_QUOTES, 'UTF-8');
    }

    $title = apply_Filters('encyclopedia_item_link_title', $title, $post, $more, $length);

    return $title;
  }

  public static function printPrefixFilter($query){
    global $post;

    static $loop_already_started;
    if ($loop_already_started)
      return False;

    # If this is a feed we leave
    if (is_Feed())
      return False;

    # If the current query is not a post query we leave
    if (!(getType($query) == 'object' && get_Class($query) == 'WP_Query'))
      return False;

    # If we are in head section we leave
    if (!did_Action('wp_head'))
      return False;

    # Run filter
    if (!apply_Filters('encyclopedia_print_prefix_filter', True, $query))
      return False;

    # Conditions
    if ($query->is_Main_Query() && !$query->get('suppress_filters')){
      $is_archive_filter = static::isEncyclopediaArchive($query) && Options::get('prefix_filter_for_archives');
      $is_singular_filter = $query->is_Singular(Post_Type::post_type_name) && Options::get('prefix_filter_for_singulars');

      # Get current Filter string
      $current_filter = '';
      if (get_Query_Var('prefix') !== '')
        $current_filter = RawUrlDecode(get_Query_Var('prefix'));
      elseif (is_Singular())
        $current_filter = MB_StrToLower(isSet($post->post_title) ? $post->post_title : '');

      # Get the Filter depth
      $filter_depth = False;
      if ($is_archive_filter)
        $filter_depth = Options::get('prefix_filter_archive_depth');
      elseif ($is_singular_filter)
        $filter_depth = Options::get('prefix_filter_singular_depth');

      # Check if we are inside a taxonomy archive
      $taxonomy_term = is_Tax() ? get_Queried_Object() : False;

      if ($is_archive_filter || $is_singular_filter){
        Prefix_Filter::printFilter($current_filter, $filter_depth, $taxonomy_term);
        $loop_already_started = True;
      }
    }
  }

  public static function getTagRelatedItems($arguments = Null){
    global $wpdb, $post;

    $arguments = is_Array($arguments) ? $arguments : Array();

    # Load default arguments
    $arguments = (Object) Array_Merge(Array(
      'post_id' => empty($post->ID) ? Null : $post->ID,
      'number' => 10,
      'taxonomy' => 'encyclopedia-tag'
    ), $arguments);

    # apply filter
    $arguments = apply_Filters('encyclopedia_tag_related_items_arguments', $arguments);

    # if there is no term set we leave
    if (empty($arguments->post_id))
      return False;

    # if the taxonomy does not exists we leave
    if (!Taxonomy_Exists($arguments->taxonomy))
      return False;

    # Get the Tags
    $arr_tags = WP_Get_Post_Terms($arguments->post_id, $arguments->taxonomy);
    if (empty($arr_tags))
      return False;

    # Get term IDs
    $arr_term_ids = Array_Map(function($taxonomy){ return $taxonomy->term_taxonomy_id; }, $arr_tags);
    $str_term_id_list = implode(',', $arr_term_ids);

    # The Query to get the related posts
    $stmt = "
      SELECT
        posts.id,
        COUNT(term_relationships.object_id) AS common_tag_count

      FROM
        {$wpdb->term_relationships} AS term_relationships,
        {$wpdb->posts} AS posts

      WHERE
        term_relationships.object_id = posts.id AND
        term_relationships.term_taxonomy_id IN({$str_term_id_list}) AND
        posts.id != {$arguments->post_id} AND
        posts.post_status = 'publish'

      GROUP BY
        term_relationships.object_id

      ORDER BY
        common_tag_count DESC,
        posts.post_date_gmt DESC";

    # Get the related post ids
    $related_post_ids = $wpdb->get_Col($stmt);

    # If there are no related posts we leave
    if (empty($related_post_ids))
      return False;

    # Generate Query args
    $query_args = Array(
      'post_type' => Post_Type::post_type_name,
      'post__in' => $related_post_ids,
      'orderby' => 'post__in',
      'posts_per_page' => $arguments->number,
      'ignore_sticky_posts' => True
    );

    # Put it in a WP_Query
    $query = new WP_Query($query_args);

    # apply a filter to the query object
    do_Action('encyclopedia_tag_related_items_query_object', $query, $arguments);

    # return
    return $query->have_Posts() ? $query : False;
  }

  public static function setNoindexTag(){
    global $wp_query;
    if (static::isEncyclopediaArchive($wp_query) && StrLen($wp_query->get('prefix')) && get_Option('blog_public')){
      WP_No_Robots();
    }
  }

  public static function filterArchiveTitle($title){
    if (is_Post_Type_Archive(Post_Type::post_type_name))
      return Post_Type_Archive_Title('', False);
    else
      return $title;
  }

}
