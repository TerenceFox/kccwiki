<?php Namespace WordPress\Plugin\Encyclopedia;

/* version 2017-12-17 */

abstract class Updates {
  public static
    $base_url,

    $plugin_file, # absolute path to the main file of the plugin
    $plugin_slug, # the slug used to identify this plugin
    $plugin_data, # the information stored in the plugin header
    $plugin_transient, # the transient name which stores the data from the last server request

    $username, # The username of the subscriber
    $password, # The password of the subscriber
    $show_notification = True; # Show update notifications to the user or not

  static function init($plugin_file, $username = Null, $password = Null, $show_notification = True){
    # Collect parameters
    static::$username = $username;
    static::$password = $password;
    static::$show_notification = $show_notification;

    static::$plugin_file = $plugin_file;
    static::$plugin_slug = BaseName(DirName(static::$plugin_file));
    static::$plugin_transient = sprintf('%s-updater-data', static::$plugin_slug);

    static::$base_url = site_url().'/'.SubStr(realPath(DirName(static::$plugin_file)), Strlen(ABSPATH));

    add_Action('init', Array(static::class, 'registerHooks'), 1);
  }

  static function registerHooks(){
    # Add warning if license data are empty
    if ((empty(static::$username) || empty(static::$password)) && current_user_can('manage_options')){
      add_Action('admin_notices', Array(static::class, 'printEmptyLicenseDataNotice'));
    }

    # The updater will only be loaded when an admin is logged in
    if (current_user_can('update_plugins')){
      add_Filter('site_transient_update_plugins', Array(static::class, 'filterUpdatePlugins'));
      add_Filter('plugins_api', Array(static::class, 'filterPluginsAPI'), 10, 3);
      add_Filter('http_response', Array(static::class, 'extendHTTPErrorMessages'), 10, 3);
    }
  }

  static function printEmptyLicenseDataNotice(){
    # Load local plugin data
    static::loadPluginHeaderData();

    # prepare admin notice
    $message = sprintf(I18n::t('You have not setup your license for <strong>%s</strong>. Please enter your license data on the <strong>settings page</strong>.'), static::$plugin_data->Name);

    printf('<div class="notice notice-error"><p>%s</p></div>', $message);
  }

  static function extendHTTPErrorMessages($response, $args, $url){
    # Load local plugin data
    static::loadPluginHeaderData();

    $request_host = @parse_Url($url, PHP_URL_HOST); # the url from which the file should be downloaded
    $plugin_host = @parse_Url(static::$plugin_data->PluginURI, PHP_URL_HOST); # the url which is defined in the main plugin files header
    $response_code = isSet($response['response']['code']) ? IntVal($response['response']['code']) : False;
    $response_message = &$response['response']['message'];

    if ($request_host == $plugin_host){
      if ($response_code == 401)
        $response_message = sprintf(I18n::t('Wrong username or password for %s. Please check the license data on the settings page of the plugin. Then run the update again.'), $request_host);

      elseif ($response_code == 403)
        $response_message = sprintf(I18n::t('You are not allowed to download this plugin. Please check on %s if your update subscription has expired.'), $request_host);
    }

    return $response;
  }

  static function loadPluginHeaderData(){
    if (empty(static::$plugin_data) && Function_Exists('get_Plugin_Data') && File_Exists(static::$plugin_file)){
      static::$plugin_data = (Object) get_Plugin_Data(static::$plugin_file);
    }
  }

  static function requestRemotePluginData(){
    # Load local plugin data
    static::loadPluginHeaderData();

    $parameter = Array(
      #'purpose' => 'version_check',
      'format' => 'json',
      'subscriber' => RAWUrlEncode(static::$username),
      'locale' => get_Locale(),
      'referrer' => RAWUrlEncode(Home_Url()),
    );
    $url = add_Query_Arg($parameter, static::$plugin_data->PluginURI);

    $raw_response = @WP_Remote_Get($url, Array('timeout' => 3));
    if (!$raw_response || is_WP_Error($raw_response)) return False;

  	$raw_response = trim(WP_Remote_Retrieve_Body($raw_response));
    $response = @JSON_Decode($raw_response);

    return $response;
  }

  static function getRemotePluginData(){
    $last_plugin_remote_data = get_Transient(static::$plugin_transient);

    if ($last_plugin_remote_data === False){
      $last_plugin_remote_data = static::requestRemotePluginData();
      setType($last_plugin_remote_data, 'ARRAY');
      $last_plugin_remote_data = Array_Filter($last_plugin_remote_data);
      set_Transient(static::$plugin_transient, $last_plugin_remote_data, 12 * HOUR_IN_SECONDS);
    }

    if (empty($last_plugin_remote_data)){
      return False;
    }
    else {
      setType($last_plugin_remote_data, 'OBJECT');
      return $last_plugin_remote_data;
    }
  }

  static function getRelativePluginPath(){
    if (!Function_Exists('get_Plugins'))
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');

    $arr_plugins = get_Plugins();
    if (!is_Array($arr_plugins)) return False;

    foreach ($arr_plugins as $file => $data){
      if (SubStr(static::$plugin_file, -1*StrLen($file)) == $file){
        return $file;
      }
    }

    return False;
  }

  static function filterUpdatePlugins($value){
    global $wp_version;

    # Find this plugin
    $relative_plugin_path = static::getRelativePluginPath();
    if (!$relative_plugin_path) return $value;

    # Get current version from server
    $remote_plugin_data = static::getRemotePluginData();
    if (!$remote_plugin_data) return $value;

    # Check if the update function is disabled
    if (!static::$show_notification) return $value;

    # Load local plugin data
    static::loadPluginHeaderData();

    # Compare versions
    if (isSet(static::$plugin_data->Version, $remote_plugin_data->version) && Version_Compare(static::$plugin_data->Version, $remote_plugin_data->version, '<')){
      $credentials_entered = !empty(static::$username) && !empty(static::$password);
      $value->response[$relative_plugin_path] = (Object) Array(
        'id' => $remote_plugin_data->id,
        'slug' => static::$plugin_slug,
        'plugin' => $relative_plugin_path,
        'new_version' => $remote_plugin_data->version,
        'url' => $remote_plugin_data->url,
        'package' => $credentials_entered && isSet($remote_plugin_data->download) ? sprintf($remote_plugin_data->download, RAWUrlEncode(static::$username), RAWUrlEncode(static::$password)) : False,
        'icons' => Array(
          'default' => $remote_plugin_data->icon,
        ),
        'banners' => Array (
          'default' => $remote_plugin_data->banner,
        ),
        'tested' => $wp_version,
      );
    }

    # Return the filter input
    return $value;
  }

  static function filterPluginsAPI($result, $action, $args){
    global $wp_version;
    if ($action == 'plugin_information' && $args->slug == static::$plugin_slug){
      WP_Enqueue_Style(static::$plugin_slug.'-plugin-details', static::$base_url.'/assets/css/plugin-details.css');
      $remote_plugin_data = static::getRemotePluginData();
      $author = isSet($remote_plugin_data->author) ? $remote_plugin_data->author : False;
      $credentials_entered = !empty(static::$username) && !empty(static::$password);
      $plugin = (Object) Array(
        'name' => $remote_plugin_data->name,
        'slug' => static::$plugin_slug,
        'version' => $remote_plugin_data->version,
        'author' => isSet($author->url, $author->display_name) ? sprintf('<a href="%1$s">%2$s</a>', $author->url, $author->display_name) : Null,
        'author_profile' => isSet($author->url) ? $author->url : Null,
        'contributors' => isSet($author->url) ?  Array('dhoppe' => $author->url) : Null,
        'requires' => $wp_version,
        'tested' => $wp_version,
        'requires_php' => Null,
        'rating' => $remote_plugin_data->rating,
        'num_ratings' => $remote_plugin_data->num_ratings,
        'active_installs' => $remote_plugin_data->active_installs,
        'last_updated' => $remote_plugin_data->last_updated,
        'homepage' => isSet($remote_plugin_data->url) ? $remote_plugin_data->url : Null,
        'sections' => is_Object($remote_plugin_data->content) ? Array_Filter((Array) $remote_plugin_data->content) : Array( __('Description') => (String) $remote_plugin_data->content),
        'download_link' => $credentials_entered && isSet($remote_plugin_data->download) ? sprintf($remote_plugin_data->download, RAWUrlEncode(static::$username), RAWUrlEncode(static::$password)) : Null,
        'donate_link' => Null,
        'banners' => Array(
          'low' => $remote_plugin_data->banner,
          'high' => Null,
        ),
        'external' => True
      );
      return $plugin;
    }

    return $result;
  }

  static function checkLicense($username, $password){
    #1 no username
    if (empty($username)) return -1;

    #2 no password
    if (empty($password)) return -2;

    #3 username password combination invalid

    #4 username password correct, but update license expired
  }

}
