<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux_Framework_sample_config' ) ) {

        class Redux_Framework_sample_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                // If Redux is running as a plugin, this will remove the demo notice and links
                //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
                //print_r($options); //Option values
                //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

                /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'redux-framework-demo' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                    'icon'   => 'el el-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                        ReduxFrameworkPlugin::instance(),
                        'plugin_metalinks'
                    ), null, 2 );

                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'redux-framework-demo' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'redux-framework-demo' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'redux-framework-demo' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'redux-framework-demo' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'redux-framework-demo' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'redux-framework-demo' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'redux-framework-demo' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'redux-framework-demo' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // General Settings
                $this->sections[] = array(
                    'icon' => 'el el-cogs',
                    'title' => __('General', 'helper'),
                    'fields' => array(
                        array(
                            'id'                          => 'phone_number',
                            'type'                        => 'text', 
                            'title'                       => __('Phone Number', 'helper'),
                            'desc'                        => __('Phone number in header.', 'helper'),
                            'placeholder'                 => '',
                            'default'                     => '715.387.5006'
                        ),
                        array(
                            'id'                    => 'general_display_backstretch',
                            'type'                  => 'switch',
                            'title'                 => __('Stretch Header Background', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'enable_foooter_widgets',
                            'type'                  => 'switch',
                            'title'                 => __('Display Footer Widget Section', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_social_footer',
                            'type'                  => 'switch',
                            'title'                 => __('Display Social Account on Footer', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_advanced_search',
                            'type'                  => 'switch',
                            'title'                 => __('Display Advanced Search Widget', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_back_to_top',
                            'type'                  => 'switch',
                            'title'                 => __('Display Back To Top', 'helper'),
                            'desc'                  => __('Display back to top button.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'section-general-post',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Post Settings', 'helper'),
                            'desc'                  => __('Post common settings.', 'helper'),
                        ),

                         array(
                            'id'                    => 'general_post_title_length',
                            'type'                  => 'slider',
                            'title'                 => __('Post Title Length', 'hospitalplus'),
                            'desc'                  => __('Post title length in each post.', 'hospitalplus'),
                            'default'               => 6,
                            'min'                   => 4,
                            'step'                  => 1,
                            'max'                   => 20,
                            'display_value'         => 'text'
                        ),

                        array(
                            'id'                    => 'general_display_post_meta',
                            'type'                  => 'switch',
                            'title'                 => __('Display Post Meta', 'helper'),
                            'desc'                  => __('Display post meta in each post and in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_share_post',
                            'type'                  => 'switch',
                            'title'                 => __('Display Share this Posts', 'helper'),
                            'desc'                  => __('Display  share this posts in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_tags_posts',
                            'type'                  => 'switch',
                            'title'                 => __('Display Tags Posts', 'helper'),
                            'desc'                  => __('Display tags in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_print_this_post',
                            'type'                  => 'switch',
                            'title'                 => __('Display Print this Posts', 'helper'),
                            'desc'                  => __('Display print this posts in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_like_this_post',
                            'type'                  => 'switch',
                            'title'                 => __('Display Like this Posts', 'helper'),
                            'desc'                  => __('Display like this posts in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_about_author',
                            'type'                  => 'switch',
                            'title'                 => __('Display About Author', 'helper'),
                            'desc'                  => __('Display about author in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_related_posts',
                            'type'                  => 'switch',
                            'title'                 => __('Display Related Posts', 'helper'),
                            'desc'                  => __('Display related posts in post detail.', 'helper'),
                            'default'               => 1,
                        ),

                        array(
                            'id'                    => 'general_display_comments_posts',
                            'type'                  => 'switch',
                            'title'                 => __('Display Comment posts', 'helper'),
                            'desc'                  => __('Display comment form in post detail.', 'helper'),
                            'default'               => 1,
                        ),                            

                        array(
                            'id'                    => 'general_display_breadcrumb',
                            'type'                  => 'switch',
                            'title'                 => __('Display Breadcrumb', 'helper'),
                            'desc'                  => __('Display breadcrumb in pages, each post, and in post detail.', 'helper'),
                            'default'               => 1,
                        ),
                     )
                );

                // Appearance Settings
                $this->sections[] = array(
                    'icon' => 'el el-website',
                    'title' => __('Appearance', 'helper'),
                    'fields' => array(
                        array(
                            'id'                    => 'section-appearance-main',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Main', 'helper'),
                            'desc'                  => __('Main settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'appearance_body_background_color',
                            'type'                  => 'background',
                            'title'                 => __('Body Background Color', 'helper'),
                            'output'                => array('body'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#ffffff',
                                                    )
                        ),

                        array(
                            'id'                    =>'appearance_favicon',
                            'type'                  => 'media', 
                            'title'                 => __('Favicon', 'helper'),
                            'output'                => 'true',
                            'mode'                  => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                            'desc'                  => __('Upload your favicon.', 'helper'),
                            'default'               => array('url' => get_stylesheet_directory_uri().'/images/favicon.png'),
                        ),

                        array(
                            'id'                    => 'section-appearance-header',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Header', 'helper'),
                            'desc'                  => __('Header settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'appearance_logo_type',
                            'type'                  => 'button_set',
                            'title'                 => __('Logo Type', 'helper'), 
                            'desc'                  => sprintf(__('Use site <a href="%s" target="_blank">title & desription</a> or use image logo.', 'helper'), admin_url('/options-general.php') ),
                            'options'               => array('1' => __('Site Title', 'helper'), '2' => __('Image', 'helper')),
                            'default'               => '2'
                        ),

                        array(
                            'id'                    => 'appearance_logo_image',
                            'type'                  => 'media', 
                            'url'                   => true,
                            'required'              => array('appearance_logo_type', 'equals', '2'),
                            'title'                 => __('Image Logo', 'helper'),
                            'output'                => 'true',
                            'desc'                  => __('Upload your logo or type the URL on the text box.', 'helper'),
                            'default'               => array('url' => get_stylesheet_directory_uri() .'/images/logo.png'),
                        ),

                        array(
                            'id'                    => 'custom_css',
                            'type'                  => 'ace_editor',
                            'title'                 => __('Custom CSS Codes', 'helper'),
                            'mode'                  => 'css',
                            'theme'                 => 'monokai',
                            'desc'                  => __('Type your custom CSS codes here, alternatively you can also write down you custom CSS styles on the custom.css file located on the theme root directory.', 'moticia'),
                            'default'               => ''
                        )
                    )
                );

                // Widget Settings
                $this->sections[] = array(
                    'icon' => 'el el-th',
                    'title' => __('Widgets', 'helper'),
                    'fields' => array(

                    )
                );        

                    $this->sections[] = array(
                        'title'      => __( 'Knowledge Base Statistics Widget Settings', 'hospitalplus' ),
                        'subsection' => true,
                        'fields'     => array(
                            array(
                                'id'                    => 'section-color-kb-statistics',
                                'type'                  => 'info',
                                'icon'                  => 'el el-info-sign',
                                'title'                 => __('Knowledge Base Statistic Color', 'helper'),
                                'desc'                  => __('Knowledge Base Statistic color settings.', 'helper'),
                            ),

                            array(
                                'id'                    => 'kb_statistics_icon_color',
                                'type'                  => 'color',
                                'title'                 => __('Knowledge Base Statistic Icon Color', 'helper'),
                                'active'                => false,
                                'output'                => array('#statistic-widget .count-icon i'),
                                'default'               => '#ffffff',
                                'validate'              => 'color'
                            ),

                            array(
                                'id'                    => 'section-typography-kb-statistics',
                                'type'                  => 'info',
                                'icon'                  => 'el el-info-sign',
                                'title'                 => __('Knowledge Base Statistic Typography', 'helper'),
                                'desc'                  => __('Knowledge base statistic typography settings.', 'helper'),
                            ),

                            array(
                                'id'                    => 'kb_statistics_counter_typography',
                                'type'                  => 'typography',
                                'title'                 => __('Knowledge Base Statistic Counter Text', 'helper'),
                                'google'                => true,
                                'subsets'               => true,
                                'preview'               => true,
                                'text-transform'        => false,
                                'line-height'           => false,
                                'text-align'            => false,
                                'output'                => array('.odometer.count-number'),
                                'default'               => array(
                                    'font-family'           => 'Roboto',
                                    'font-size'             => '50px',
                                    'color'                 => '#ffffff',
                                    'font-weight'           => '700',
                                    'text-transform'        => 'none',
                                )
                            ),

                            array(
                                'id'                    => 'kb_statistics_text_typography',
                                'type'                  => 'typography',
                                'title'                 => __('Knowledge Base Statistic Description Text', 'helper'),
                                'google'                => true,
                                'subsets'               => true,
                                'preview'               => true,
                                'text-transform'        => true,
                                'line-height'           => false,
                                'text-align'            => false,
                                'font-weight'           => true,
                                'output'                => array('.count-label'),
                                'default'               => array(
                                    'font-family'           => 'Roboto',
                                    'font-size'             => '15px',
                                    'color'                 => '#ffffff',
                                    'font-weight'           => '400',
                                    'text-transform'        => 'none',
                                )
                            ),
                        )
                    );

                    $this->sections[] = array(
                        'title'      => __( 'Featured Posts Widget Settings', 'hospitalplus' ),
                        'subsection' => true,
                        'fields'     => array(
                            array(
                                'id'                    => 'section-color-featured-post',
                                'type'                  => 'info',
                                'icon'                  => 'el el-info-sign',
                                'title'                 => __('Featured Post Color', 'helper'),
                                'desc'                  => __('Featured post color settings.', 'helper'),
                            ),

                            array(
                                'id'                    => 'featured_post_icon_color',
                                'type'                  => 'color',
                                'title'                 => __('Featured Post Icon Color', 'helper'),
                                'active'                => false,
                                'output'                => array('.feature-widget ul li .feature-icon'),
                                'default'               => '#269dce',
                                'validate'              => 'color'
                            ),

                            array(
                                'id'                        => 'featured_post_link_color',
                                'type'                      => 'link_color',
                                'title'                     => __('Featured Post Link Color', 'helper'),
                                'active'                    => false,
                                'output'                    => array('h2.feature-title a'),
                                'default'                   => array(
                                                                'regular'  => '#0c6897',
                                                                'hover'    => '#0c6897',
                                )
                            ),

                            array(
                                'id'                    => 'featured_post_button_color',
                                'type'                  => 'background',
                                'title'                 => __('Featured Post Button Color', 'helper'),
                                'output'                => array('.feature-detail a.button'),
                                'preview'               => false,
                                'preview_media'         => false,
                                'background-repeat'     => false,
                                'background-attachment' => false,
                                'background-position'   => false,
                                'background-image'      => false,
                                'background-gradient'   => false,
                                'background-clip'       => false,
                                'background-origin'     => false,
                                'background-size'       => false,
                                'default'               => array(
                                                            'background-color'      => '#ffffff',
                                                        )
                            ),

                            array(
                                'id'                => 'featured_post_button_border',
                                'type'              => 'border',
                                'title'             => __('Featured Post Button Border', 'helper'),
                                'output'            => array('.feature-detail a.button'),
                                'all'               => true,
                                'default'           => array(
                                    'border-color'      => '#269dce',
                                    'border-style'      => 'solid',
                                    'border-width'      => '1px', 
                                )
                            ),

                            array(
                                'id'                    => 'featured_post_button_color_hover',
                                'type'                  => 'background',
                                'title'                 => __('Featured Post Button Color Hover', 'helper'),
                                'output'                => array('.feature-detail a.button:hover'),
                                'preview'               => false,
                                'preview_media'         => false,
                                'background-repeat'     => false,
                                'background-attachment' => false,
                                'background-position'   => false,
                                'background-image'      => false,
                                'background-gradient'   => false,
                                'background-clip'       => false,
                                'background-origin'     => false,
                                'background-size'       => false,
                                'default'               => array(
                                                            'background-color'      => '#269dce',
                                                        )
                            ),

                            array(
                                'id'                    => 'featured_post_button_link_color',
                                'type'                  => 'color',
                                'title'                 => __('Featured Post Button Link Color Hover', 'helper'),
                                'active'                => false,
                                'output'                => array('.feature-detail a.button:hover'),
                                'default'               => '#ffffff',
                                'validate'              => 'color'
                            ),

                            array(
                                'id'                    => 'section-typography-featured-post',
                                'type'                  => 'info',
                                'icon'                  => 'el el-info-sign',
                                'title'                 => __('Featured Post Typography', 'helper'),
                                'desc'                  => __('featured post typography settings.', 'helper'),
                            ),

                            array(
                                'id'                    => 'featured_post_title_typography',
                                'type'                  => 'typography',
                                'title'                 => __('Featured Post Title Text', 'helper'),
                                'google'                => true,
                                'subsets'               => true,
                                'preview'               => true,
                                'text-transform'        => true,
                                'line-height'           => false,
                                'text-align'            => false,
                                'font-weight'           => true,
                                'output'                => array('.feature-title'),
                                'default'               => array(
                                    'font-family'           => 'Roboto',
                                    'font-size'             => '24px',
                                    'color'                 => '#0c6897',
                                )
                            ),

                            array(
                                'id'                    => 'featured_post_description_typography',
                                'type'                  => 'typography',
                                'title'                 => __('Featured Post Description Text', 'helper'),
                                'google'                => true,
                                'subsets'               => true,
                                'preview'               => true,
                                'text-transform'        => false,
                                'line-height'           => true,
                                'text-align'            => false,
                                'font-weight'           => true,
                                'output'                => array('.feature-detail p'),
                                'default'               => array(
                                    'font-family'           => 'Roboto',
                                    'font-size'             => '15px',
                                    'color'                 => '#666',
                                    'font-weight'           => '400',
                                    'line-height'           => '28px',
                                )
                            ),

                            array(
                                'id'                    => 'featured_post_button_typography',
                                'type'                  => 'typography',
                                'title'                 => __('Featured Post Button Text', 'helper'),
                                'google'                => true,
                                'subsets'               => true,
                                'preview'               => true,
                                'text-transform'        => false,
                                'line-height'           => false,
                                'text-align'            => false,
                                'font-weight'           => true,
                                'output'                => array('.feature-detail a.button'),
                                'default'               => array(
                                    'font-family'           => 'Roboto',
                                    'font-size'             => '12px',
                                    'color'                 => '#269dce',
                                    'font-weight'           => '700',
                                    'text-transform'        => 'uppercase',
                                )
                            ),
                        )
                    );

                    $this->sections[] = array(
                        'title'      => __( 'Browse Topics Widget Settings', 'hospitalplus' ),
                        'subsection' => true,
                        'fields'     => array(
                            array(
                                'id'                    => 'section-color-browse-topics',
                                'type'                  => 'info',
                                'icon'                  => 'el el-info-sign',
                                'title'                 => __('Browse Topics Color', 'helper'),
                                'desc'                  => __('Browse topics color settings.', 'helper'),
                            ),

                            array(
                                'id'                => 'browse_topics_tab_border',
                                'type'              => 'border',
                                'title'             => __('Browse Topics Tab Border', 'helper'),
                                'output'            => array('.simple-tab-nav li a'),
                                'all'               => true,
                                'default'           => array(
                                    'border-color'      => '#ccc',
                                    'border-style'      => 'solid',
                                    'border-width'      => '1px', 
                                )
                            ),

                            array(
                                'id'                => 'browse_topics_tab_content_border',
                                'type'              => 'border',
                                'title'             => __('Browse Topics Tab Content Border', 'helper'),
                                'output'            => array('.simple-tab-nav'),
                                'all'               => false,
                                'top'               => false,
                                'right'             => false,
                                'left'              => false,
                                'bottom'            => true,
                                'default'           => array(
                                    'border-color'      => '#ccc',
                                    'border-style'      => 'solid',
                                    'border-width'      => '1px', 
                                )
                            ),

                            array(
                                'id'                        => 'browse_topics_tab_link',
                                'type'                      => 'link_color',
                                'title'                     => __('Browse Topics Tab Link Color', 'helper'),
                                'active'                    => false,
                                'output'                    => array('.simple-tab-nav li a'),
                                'default'                   => array(
                                                                'regular'  => '#555555',
                                                                'hover'    => '#2e7da3',
                                )
                            ),

                            array(
                                'id'                    => 'browse_topics_date_background',
                                'type'                  => 'background',
                                'title'                 => __('Browse Topics Date Background', 'helper'),
                                'output'                => array('article.featured-post .entry-meta'),
                                'preview'               => false,
                                'preview_media'         => false,
                                'background-repeat'     => false,
                                'background-attachment' => false,
                                'background-position'   => false,
                                'background-image'      => false,
                                'background-gradient'   => false,
                                'background-clip'       => false,
                                'background-origin'     => false,
                                'background-size'       => false,
                                'default'               => array(
                                                            'background-color'      => '#ffffff',
                                                        )
                            ),

                            array(
                                'id'                => 'browse_topics_date_border',
                                'type'              => 'border',
                                'title'             => __('Browse Topics Date Border', 'helper'),
                                'output'            => array('article.featured-post .entry-meta'),
                                'all'               => false,
                                'top'               => false,
                                'right'             => true,
                                'left'              => false,
                                'bottom'            => false,
                                'default'           => array(
                                    'border-color'      => '#EC7411',
                                    'border-style'      => 'solid',
                                    'border-width'      => '3px', 
                                )
                            ),
                        )
                    );

                // Typography Settings
                $this->sections[] = array(
                    'icon'    => 'el el-text-width',
                    'title'   => __('Typography', 'helper'),
                    'fields'  => array(
                        array(
                            'id'                    => 'section-typography-main',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Main', 'helper'),
                            'desc'                  => __('Main typography settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'main_typography',
                            'type'                  => 'typography',
                            'title'                 => __('Main', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'text-transform'        => false,
                            'line-height'           => false,
                            'text-align'            => false,
                            'letter-spacing'        => true,
                            'output'                => array('body'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '400',
                                    'color'             => '#4b4f56',
                            )
                        ),

                        array(
                            'id'                        => 'section-livesearch-typography',
                            'type'                      => 'info',
                            'icon'                      => 'el el-info-sign',
                            'title'                     => __('Live Search Typography', 'helper'),
                            'desc'                      => __('Live search typography settings.', 'helper'),
                        ),

                        array(
                            'id'                        => 'live_search_text_field_font',
                            'type'                      => 'typography',
                            'title'                     => __('Live Search Form Field Text', 'helper'),
                            'google'                    => true,
                            'subsets'                   => true,
                            'preview'                   => true,
                            'line-height'               => false,
                            'text-align'                => false,
                            'output'                    => array('#warrior-advanced-search .input-holder input.input'),
                            'default'                   => array(
                                'font-family'               => 'Open Sans',
                                'font-size'                 => '18px',
                                'font-weight'               => '400',
                                'color'                     => '#666',
                            )
                        ),

                        array(
                            'id'                        => 'live_search_text_button_font',
                            'type'                      => 'typography',
                            'title'                     => __('Live Search Form Button Text', 'helper'),
                            'google'                    => true,
                            'subsets'                   => true,
                            'preview'                   => true,
                            'text-transform'            => true,
                            'line-height'               => false,
                            'text-align'                => false,
                            'output'                    => array('#warrior-advanced-search button.searchbutton'),
                            'default'                   => array(
                                'font-family'               => 'Open Sans',
                                'font-size'                 => '16px',
                                'font-weight'               => '700',
                                'text-transform'            => 'uppercase',
                                'color'                     => '#ffffff',
                                'letter-spacing'            => '1px'
                            )
                        ),

                        array(
                            'id'                            => 'live_search_lists_font',
                            'type'                          => 'typography',
                            'title'                         => __('Live Search Suggest List Text', 'helper'),
                            'google'                        => true,
                            'subsets'                       => true,
                            'preview'                       => true,
                            'line-height'                   => false,
                            'text-align'                    => false,
                            'output'                        => array('.livesearch'),
                            'default'                       => array(
                                'font-family'                   => 'Open Sans',
                                'font-size'                     => '15px',
                                'font-weight'                   => '400',
                                'color'                         => '#666',
                            )
                        ),

                        array(
                            'id'                    => 'breadcrumb_text',
                            'type'                  => 'typography',
                            'title'                 => __('Breadcrumb Text', 'helper'),
                            'desc'                  => __('Also used for breadcrumbe.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => false,
                            'output'                => array('.breadcrumb ul li'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '12px',
                                    'font-weight'       => '400',
                                    'color'             => '#a9a9a9',
                            )
                        ),

                        array(
                            'id'                    => 'post_content_fotn',
                            'type'                  => 'typography',
                            'title'                 => __('Article Text in Single Post', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => false,
                            'letter-spacing'        => true,
                            'output'                => array('body.single #leftcontent article.hentry .entry-content'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '16px',
                                    'font-weight'       => '400',
                                    'color'             => '#4b4f56',
                            )
                        ),

                        array(
                            'id'                    => 'author_name_text',
                            'type'                  => 'typography',
                            'title'                 => __('Author Name', 'helper'),
                            'desc'                  => __('Author name in list comment & about author.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('.comment-detail .author h5', 'h5.author-name', '.widget.about-author .author-detail h4'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '14px',
                                    'font-weight'       => '600',
                                    'color'             => '#2f2f2f',
                            )
                        ),

                        array(
                            'id'                    => 'comment_moderation_text',
                            'type'                  => 'typography',
                            'title'                 => __('Comment Under Moderation', 'helper'),
                            'desc'                  => __('Comment moderation alert text.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('p.moderate'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '12px',
                                    'font-weight'       => '600',
                                    'color'             => '#2f2f2f',
                            )
                        ),

                        array(
                            'id'                    => 'section-typography-header',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Header', 'helper'),
                            'desc'                  => __('Header typography settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'typography_site_title_font',
                            'type'                  => 'typography',
                            'title'                 => __('Site Title Logo', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'output'                => array('#logo h2'),
                            'default'               => array(
                                'font-family'           => 'Roboto',
                                'font-size'             => '34px',
                                'font-weight'           => '700',
                                'color'                 => '#fcfcfc',
                            )
                        ),

                        array(
                            'id'                    => 'typography_nav_main_menu_font',
                            'type'                  => 'typography',
                            'title'                 => __('Main Menu', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'text-transform'        => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'output'                => array('.menu-header .site-navigation ul li a'),
                            'default'               => array(
                                'font-family'           => 'Roboto',
                                'font-size'             => '16px',
                                'font-weight'           => '700',
                                'line-height'           => '24px',
                                'text-transform'        => 'none',
                                'color'                 => '#555555',
                                'letter-spacing'        => '0.5px'
                            )
                        ),

                        array(
                            'id'                    => 'section-typography-pages',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Pages & Posts', 'helper'),
                            'desc'                  => __('Pages & Posts typography settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'main-content-section-heading',
                            'type'                  => 'typography',
                            'title'                 => __('Main Content Section Heading', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#leftcontent h4.widget-title'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '20px',
                                    'font-weight'       => '700',
                                    'color'             => '#2f2f2f',
                                    'line-height'       => '24px',
                                    'text-transform'    => 'none'
                            )
                        ),


                        array(
                            'id'                    => 'heading_h1',
                            'type'                  => 'typography',
                            'title'                 => __('Heading H1', 'helper'),
                            'desc'                  => __('Also used for post & page title.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('#leftcontent .entry-content h1'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '36px',
                                    'font-weight'       => '700',
                                    'line-height'       => '130%',
                                    'color'             => '#2f2f2f'
                            )
                        ),

                        array(
                            'id'                    => 'heading_h2',
                            'type'                  => 'typography',
                            'title'                 => __('Heading H2', 'helper'),
                            'desc'                  => __('Also used for post & page title.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('#leftcontent .entry-content h2'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '26px',
                                    'font-weight'       => '700',
                                    'line-height'       => '130%',
                                    'color'             => '#2f2f2f'
                            )
                        ),

                        array(
                            'id'                    => 'heading_h3',
                            'type'                  => 'typography',
                            'title'                 => __('Heading H3', 'helper'),
                            'desc'                  => __('Also used for post & page title.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('#leftcontent .entry-content h3'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '20px',
                                    'font-weight'       => '700',
                                    'line-height'       => '130%',
                                    'color'             => '#2f2f2f',
                            )
                        ),

                        array(
                            'id'                    => 'heading_h4',
                            'type'                  => 'typography',
                            'title'                 => __('Heading H4', 'helper'),
                            'desc'                  => __('Also used for post & page title.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('#leftcontent .entry-content h4'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '15px',
                                    'font-weight'       => '700',
                                    'line-height'       => '150%',
                                    'color'             => '#2f2f2f',
                            )
                        ),

                        array(
                           'id'                     => 'heading_h5',
                            'type'                  => 'typography',
                            'title'                 => __('Heading H5', 'helper'),
                            'desc'                  => __('Also used for post & page title.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('#leftcontent .entry-content h5'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '13px',
                                    'font-weight'       => '700',
                                    'line-height'       => '150%',
                                    'color'             => '#2f2f2f',
                            )
                        ),

                        array(
                           'id'                     => 'heading_h6',
                            'type'                  => 'typography',
                            'title'                 => __('Heading H6', 'helper'),
                            'desc'                  => __('Also used for post & page title.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'output'                => array('#leftcontent .entry-content h6'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '14px',
                                    'font-weight'       => '400',
                                    'line-height'       => '130%',
                                    'color'             => '#2f2f2f',
                            )
                        ),

                        array(
                            'id'                    => 'archive_post_title',
                            'type'                  => 'typography',
                            'title'                 => __('Archive Post Title', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#leftcontent article.hentry h3.post-title'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '25px',
                                    'font-weight'       => '700',
                                    'color'             => '#2f2f2f',
                                    'line-height'       => '120%',
                                    'letter-spacing'   => '0'
                            )
                        ),

                        array(
                            'id'                    => 'post_title_text',
                            'type'                  => 'typography',
                            'title'                 => __('Heading Post Title', 'helper'),
                            'desc'                  => __('Also used for post title in each post and post detail.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#leftcontent h1.post-title', '#primary.detail-page h1.post-title', '.section-title'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '35px',
                                    'font-weight'       => '700',
                                    'color'             => '#2f2f2f',
                                    'letter-spacing'    => '-1px',
                                    'line-height'       => '40px', 
                            )
                        ),

                        array(
                            'id'                    => 'post_meta_font',
                            'type'                  => 'typography',
                            'title'                 => __('Post Meta & Breadcrumb', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('.entry-meta', '.breadcrumb'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '12px',
                                    'font-weight'       => '400',
                                    'color'             => '#c0c0c0',
                                    'letter-spacing'    => '0'
                            )
                        ),

                        array(
                            'id'                    => 'each_post_title_text',
                            'type'                  => 'typography',
                            'title'                 => __('Single Post Widget Title', 'helper'),
                            'desc'                  => __('Section title on single post page. Eg: Related Posts, Comments etc.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('article.featured-post h3.post-title'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '18px',
                                    'font-weight'       => '700',
                                    'color'             => '#666',
                            )
                        ),

                        array(
                            'id'                    => 'each_post_meta_text',
                            'type'                  => 'typography',
                            'title'                 => __('Post Meta (on each post)', 'helper'),
                            'desc'                  => __('Also used for post meta in each post.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#popular-post .entry-meta', '.related-article-widget .entry-meta'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '12px',
                                    'font-weight'       => '400',
                                    'text-transform'    => 'none',
                                    'color'             => '#aaa',
                            )
                        ),

                        array(
                            'id'                    => 'each_post_date',
                            'type'                  => 'typography',
                            'title'                 => __('Post Date', 'helper'),
                            'desc'                  => __('Also used for post date on each post.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => false,
                            'output'                => array('.blog-date'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '48px',
                                    'font-weight'       => '600',
                                    'color'             => '#b9b9b9',
                                    'text-transform'    => 'uppercase',
                            )
                        ), 

                        array(
                            'id'                    => 'each_post_month_year',
                            'type'                  => 'typography',
                            'desc'                  => __('Also used for post month and year on each post.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => false,
                            'output'                => array('.blog-date span'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '18px',
                                    'font-weight'       => '600',
                                    'color'             => '#b9b9b9',
                                    'text-transform'    => 'uppercase',
                            )
                        ),

                        array(
                            'id'                    => 'section-typography-sidebar',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Sidebar', 'helper'),
                            'desc'                  => __('Sidebar typography settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'sidebar_content_title_widget',
                            'type'                  => 'typography',
                            'title'                 => __('Sidebar and Content Widget Title', 'helper'),
                            'desc'                  => __('Also used for widget title in sidebar and content.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#rightcontent h4.widget-title', '#popular-post h4.widget-title', '.related-article-widget h4.widget-title', '.comments-widget h4.widget-title', '.column.widget.popular-post .widget-title'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '18px',
                                    'font-weight'       => '700',
                                    'color'             => '#2f2f2f',
                                    'line-height'       => '24px',
                                    'text-transform'    => 'none'
                            )
                        ),

                        array(
                            'id'                    => 'sidebar_text_widget',
                            'type'                  => 'typography',
                            'title'                 => __('Sidebar Widget Text', 'helper'),
                            'desc'                  => __('Also used for widget text in sidebar.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#rightcontent'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '400',
                                    'color'             => '#555',
                            )
                        ),

                        array(
                            'id'                    => 'section-typography-footer',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Footer', 'helper'),
                            'desc'                  => __('Footer typography settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'footer_title_widget',
                            'type'                  => 'typography',
                            'title'                 => __('Footer Widget Title', 'helper'),
                            'desc'                  => __('Also used for widget title in footer.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#footer-widgets .widget-title'),
                            'default'               => array(
                                    'font-family'       => 'Roboto',
                                    'font-size'         => '20px',
                                    'font-weight'       => '700',
                                    'color'             => '#ffffff',
                            )
                        ),

                        array(
                            'id'                    => 'footer_text_widget',
                            'type'                  => 'typography',
                            'title'                 => __('Footer Widget Text', 'helper'),
                            'desc'                  => __('Also used for widget text in footer.', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#footer-widgets'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '400',
                                    'color'             => '#eee',
                            )
                        ),

                        array(
                            'id'                    => 'footer_text',
                            'type'                  => 'typography',
                            'title'                 => __('Footer Text', 'helper'),
                            'desc'                  => __('Also used for text in footer (copyright, etc).', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-align'            => false,
                            'text-transform'        => true,
                            'letter-spacing'        => true,
                            'output'                => array('#footer-bottom'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '400',
                                    'color'             => '#999999',
                            )
                        ),

                        array(
                            'id'                    => 'section-typography-form',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Form', 'helper'),
                            'desc'                  => __('Form typography settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'form_field_font',
                            'type'                  => 'typography',
                            'title'                 => __('Form Field Text', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-transform'        => true,
                            'output'                => array('form input[type="text"]', 'form input[type="password"]', 'form input[type="email"]', 'select', 'form textarea', '.input textarea', 'form input[type="url"]'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '400',
                                    'color'             => '#666',
                            )
                        ),

                        array(
                            'id'                    => 'form_label_font',
                            'type'                  => 'typography',
                            'title'                 => __('Form Label Text', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-transform'        => true,
                            'output'                => array('form label', '.bbp-login-form label'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '400',
                                    'color'             => '#666',
                                    'text-transform'    => 'uppercase',
                            )
                        ),

                        array(
                            'id'                    => 'form_button_font',
                            'type'                  => 'typography',
                            'title'                 => __('Form Button Text', 'helper'),
                            'google'                => true,
                            'subsets'               => true,
                            'preview'               => true,
                            'line-height'           => false,
                            'text-transform'        => true,
                            'output'                => array('form .button.submit-button', 'form button[type="submit"]', 'form input[type="submit"]', '.search-widget .button.searchbutton', 'form .button.large'),
                            'default'               => array(
                                    'font-family'       => 'Open Sans',
                                    'font-size'         => '15px',
                                    'font-weight'       => '700',
                                    'color'             => '#ffffff',
                            )
                        ),

                    )
                );
                
                // Color Settings
                $this->sections[] = array(
                    'icon'    => 'el el-brush',
                    'title'   => __('Colors', 'helper'),
                    'fields'  => array(
                        array(
                            'id'                    => 'section-color-main',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Main', 'helper'),
                            'desc'                  => __('Main color settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'main_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Main Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#leftcontent a'),
                            'default'               => array(
                                                        'regular'  => '#0277bd',
                                                        'hover'    => '#d84315',
                            )
                        ),

                        array(
                            'id'                    => 'breadcrumb_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Breadcrumb Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('.breadcrumb ul li a', '.breadcrumb a'),
                            'default'               => array(
                                                        'regular'  => '#666',
                                                        'hover'    => '#c6671a',
                            )
                        ),

                        array(
                            'id'                    => 'post_meta_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Post Meta Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('.post-detail .entry-meta a', '.post-list .entry-meta a', '#popular-post .entry-meta a', '.related-article-widget .entry-meta a'),
                            'default'               => array(
                                                        'regular'  => '#aaa',
                                                        'hover'    => '#c6671a',
                            )
                        ),

                        array(
                            'id'                    => 'category_detail_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Category Name Link Color (Post Detail)', 'helper'),
                            'active'                => false,
                            'output'                => array('#leftcontent .entry-header span.category a'),
                            'default'               => array(
                                                        'regular'  => '#c6671a',
                                                        'hover'    => '#c6671a',
                            )
                        ),

                        array(
                            'id'                    => 'page_post_title_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Post & Page Title Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('h3.post-title a'),
                            'default'               => array(
                                                        'regular'  => '#2f2f2f',
                                                        'hover'    => '#c6671a',
                            )
                        ),

                        array(
                            'id'                    => 'share_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Share Article Button Color', 'helper'),
                            'active'                => false,
                            'output'                => array('.social.share-widget ul li a i'),
                            'default'               => array(
                                                        'regular'  => '#aaa',
                                                        'hover'    => '#c6671a',
                            )
                        ),

                        array(
                            'id'                    => 'readmore_print_button_color',
                            'type'                  => 'background',
                            'title'                 => __('Read More & Print Button Color', 'helper'),
                            'output'                => array('article a.button'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#ffffff',
                                                    )
                        ),

                        array(
                            'id'                    => 'readmore_print_button_border',
                            'type'                  => 'border',
                            'title'                 => __('Read More & Print Button Border', 'helper'),
                            'output'                => array('article a.button'),
                            'all'                   => true,
                            'default'               => array(
                                'border-color'      => '#269dce',
                                'border-style'      => 'solid',
                                'border-width'      => '1px', 
                            )
                        ),

                        array(
                            'id'                    => 'readmore_print_button_color_hover',
                            'type'                  => 'background',
                            'title'                 => __('Read More & Print Button Color Hover', 'helper'),
                            'output'                => array('article a.button:hover'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#269dce',
                                                    )
                        ),

                        array(
                            'id'                    => 'readmore_print_button_link_color',
                            'type'                  => 'color',
                            'title'                 => __('Read More & Print Link Color Hover', 'helper'),
                            'active'                => false,
                            'output'                => array('article a.button:hover'),
                            'default'               => '#ffffff',
                            'validate'              => 'color'
                        ),

                        array(
                            'id'                    => 'auhtor_bio_background',
                            'type'                  => 'background',
                            'title'                 => __('Author Bio Background', 'helper'),
                            'output'                => array('.widget.about-author'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#f9f9f9',
                                                    )
                        ),

                        array(
                            'id'                    => 'sidebar_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Sidebar Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#rightcontent a'),
                            'default'               => array(
                                                        'regular'  => '#666',
                                                        'hover'    => '#c6671a',
                            )
                        ),

                        array(
                            'id'                    => 'footer_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Main Footer Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#footer-widgets a', '#footer-widgets ul li a', '#footer-widgets #colophone a'),
                            'default'               => array(
                                                        'regular'  => '#bbd5de',
                                                        'hover'    => '#faf0bb',
                            )
                        ),


                        array(
                            'id'                    => 'footer_copyright_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Footer Copyright Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#footer-bottom a'),
                            'default'               => array(
                                                        'regular'  => '#555',
                                                        'hover'    => '#ff8f00',
                            )
                        ),
                        array(
                            'id'                    => 'pagination_border',
                            'type'                  => 'border',
                            'title'                 => __('Pagination Border Color', 'helper'),
                            'output'                => array('.pagination a', '.pagination span.current'),
                            'all'                   => true,
                            'default'               => array(
                                'border-color'      => '#dddddd',
                                'border-style'      => 'solid',
                                'border-width'      => '1px', 
                            )
                        ),

                        array(
                            'id'                    => 'pagination_hover_border',
                            'type'                  => 'border',
                            'title'                 => __('Pagination Hover Border Color', 'helper'),
                            'output'                => array('.pagination a:hover', '.pagination span.current'),
                            'all'                   => true,
                            'default'               => array(
                                'border-color'      => '#c6671a',
                                'border-style'      => 'solid',
                                'border-width'      => '1px', 
                            )
                        ),

                        array(
                            'id'                        => 'right_sidebar_background',
                            'type'                      => 'background',
                            'title'                     => __('Sidebar Background Color', 'helper'),
                            'output'                    => array('#rightcontent'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#fcfcfc',
                                                        )
                        ),

                        array(
                            'id'                    => 'back_to_top_bg',
                            'type'                  => 'background',
                            'title'                 => __('Back to Top Button Background Color', 'helper'),
                            'output'                => array('#scroll-top'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#eeeeee',
                                                    )
                        ),

                        array(
                            'id'                    => 'back_to_top_icon_color',
                            'type'                  => 'color',
                            'title'                 => __('Back to Top Button Icon Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#scroll-top'),
                            'default'               => '#999999',
                            'validate'              => 'color'
                        ),

                        array(
                            'id'                    => 'section-color-header',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Header', 'helper'),
                            'desc'                  => __('Header color settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'main_menu_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Main Menu Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('nav.site-navigation ul li a'),
                            'default'               => array(
                                                        'regular'  => '#212121',
                                                        'hover'    => '#dd2c00',
                            )
                        ),

                        array(
                            'id'                    => 'nav_menu_background_color',
                            'type'                  => 'background',
                            'title'                 => __('Main Menu Background Color', 'helper'),
                            'output'                => array('#masthead .menu-header'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#ffffff',
                                                    )
                        ),

                        array(
                            'id'                    => 'nav_menu_active_state_bg',
                            'type'                  => 'background',
                            'title'                 => __('Main Menu Active State Backgroun Color', 'helper'),
                            'output'                => array('.site-navigation ul.main-menu > li.current-menu-item > a', '.site-navigation ul.main-menu > li.current-menu-parent > a', '.site-navigation ul.main-menu > li.current-menu-ancestor > a', '.site-navigation ul.main-menu > li:hover > a'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(   
                                                        'background-color'      => '#eeeeee', 
                                                    )
                        ),

                        array(
                            'id'                    => 'nav_menu_active_state_text_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Main Menu Active State Text Color', 'helper'),
                            'output'                => array('.site-navigation ul.main-menu > li.current-menu-item > a', '.site-navigation ul.main-menu > li.current-menu-parent > a', '.site-navigation ul.main-menu > li.current-menu-ancestor > a', '.site-navigation ul.main-menu > li:hover > a'),
                            'active'                => false,
                            'default'               => array(
                                                        'regular'  => '#555555',
                                                        'hover'    => '#555555',
                            )
                        ),

                        array(
                            'id'                    => 'dropdown_menu_background_color',
                            'type'                  => 'background',
                            'title'                 => __('Main Menu Dropdown Background Color', 'helper'),
                            'output'                => array('nav.site-navigation ul li ul.sub-menu', '.mobile-menu .slicknav_menu .slicknav_nav'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#ffffff',
                                                    )
                        ),

                        array(
                            'id'                    => 'dropdown_menu_background_hover_color',
                            'type'                  => 'background',
                            'title'                 => __('Main Menu Dropdown Hover Background Color', 'helper'),
                            'output'                => array('nav.site-navigation ul.sub-menu li a:hover'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#f0f0f0',
                                                    )
                        ),

                        array(
                            'id'                    => 'dropdown_menu_border',
                            'type'                  => 'border',
                            'title'                 => __('Main Menu Dropdown Border', 'helper'),
                            'output'                => array('.primary-navigation ul.main-menu ul.sub-menu li a'),
                            'all'                   => false,
                            'left'                  => false,
                            'top'                   => false,
                            'right'                 => false,
                            'bottom'                => true,
                            'default'               => array(
                                'border-color'      => '#eeeeee',
                                'border-style'      => 'solid',
                                'border-width'      => '1px', 
                            )
                        ),

                        array(
                            'id'                    => 'main_menu_dropdown_link_color',
                            'type'                  => 'link_color',
                            'title'                 => __('Main Menu Dropdown  Link Color', 'helper'),
                            'active'                => false,
                            'output'                => array('.primary-navigation ul.main-menu ul.sub-menu li a'),
                            'default'               => array(
                                                        'regular'  => '#555555',
                                                        'hover'    => '#f44336',
                            )
                        ),
                        array(
                            'id'                        => 'section-livesearch-appearance',
                            'type'                      => 'info',
                            'icon'                      => 'el el-info-sign',
                            'title'                     => __('Live Search Appearance', 'helper'),
                            'desc'                      => __('Live Search appearance settings.', 'helper'),
                        ),

                        array(
                            'id'                        => 'live_search_form_text_bg',
                            'type'                      => 'background',
                            'title'                     => __('Live Search Form Text Field Background Color', 'helper'),
                            'output'                    => array('#warrior-advanced-search .input-holder input.input'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#ffffff',
                                                        )
                        ),

                        array(
                            'id'                        => 'live_search_form_button_bg',
                            'type'                      => 'background',
                            'title'                     => __('Live Search Button Background Color', 'helper'),
                            'output'                    => array('#warrior-advanced-search button.searchbutton'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#269dce',
                                                        )
                        ),

                        array(
                            'id'                        => 'live_search_form_button_hover_bg',
                            'type'                      => 'background',
                            'title'                     => __('Live Search Button Hover Color', 'helper'),
                            'output'                    => array('#warrior-advanced-search button.searchbutton:hover'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#2791BD',
                                                        )
                        ),

                        array(
                            'id'                        => 'live_search_suggestion_bg',
                            'type'                      => 'background',
                            'title'                     => __('Live Search Suggestion Background Color', 'helper'),
                            'output'                    => array('.livesearch'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#ffffff',
                                                        )
                        ),

                        array(
                            'id'                        => 'live_search_suggestion_border',
                            'type'                      => 'border',
                            'title'                     => __('Live Search Suggestion List Border', 'helper'),
                            'output'                    => array('.livesearch ul li a'),
                            'all'                       => false,
                            'bottom'                    => true,
                            'top'                       => false,
                            'right'                     => false,
                            'left'                      => false,
                            'default'                   => array(
                                'border-color'          => '#eee',
                                'border-style'          => 'solid',
                                'border-width'          => '1px', 
                            )
                        ),

                        array(
                            'id'                        => 'live_search_suggestion_list_hover',
                            'type'                      => 'background',
                            'title'                     => __('Live Search Suggestion List Hover', 'helper'),
                            'output'                    => array('.livesearch ul li a:hover'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#f9f9f9',
                                                        )
                        ),

                        array(
                            'id'                        => 'section-form-color',
                            'type'                      => 'info',
                            'icon'                      => 'el el-info-sign',
                            'title'                     => __('Form Colors', 'helper'),
                            'desc'                      => __('Form color settings.', 'helper'),
                        ),

                        array(
                            'id'                        => 'form_text_bg',
                            'type'                      => 'background',
                            'title'                     => __('Form Text Field & Textarea Color', 'helper'),
                            'output'                    => array('form .input input[type="text"]', 'form .input input[type="password"]', 'form .input input[type="email"]', '.input textarea', 'form .input input[type="url"]'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#ffffff',
                                                        )
                        ),

                        array(
                            'id'                        => 'form_field_border',
                            'type'                      => 'border',
                            'title'                     => __('Form Text Field & Textarea Border Color', 'helper'),
                            'desc'                      => __('Form text field & textarea border color.', 'helper'),
                            'output'                    => array('form .input input[type="text"]', 'form .input input[type="password"]', 'form .input input[type="email"]', '.input textarea', 'form .input input[type="url"]'),
                            'all'                       => true,
                            'default'                   => array(
                                'border-color'          => '#b9b9b9',
                                'border-style'          => 'solid',
                                'border-width'          => '1px', 
                            )
                        ),

                        array(
                            'id'                        => 'form_button_bg',
                            'type'                      => 'background',
                            'title'                     => __('Form Button Background Color', 'helper'),
                            'output'                    => array('form .button.submit-button', 'form button[type="submit"]', 'form input[type="submit"]', 'form .button.large'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#4caf50',
                                                        )
                        ),

                        array(
                            'id'                        => 'form_button_hover_bg',
                            'type'                      => 'background',
                            'title'                     => __('Form Button Hover Background Color', 'helper'),
                            'output'                    => array('form .button.submit-button:hover', 'form button[type="submit"]:hover', 'form input[type="submit"]:hover', 'form .button.large:hover'),
                            'preview'                   => false,
                            'preview_media'             => false,
                            'background-repeat'         => false,
                            'background-attachment'     => false,
                            'background-position'       => false,
                            'background-image'          => false,
                            'background-gradient'       => false,
                            'background-clip'           => false,
                            'background-origin'         => false,
                            'background-size'           => false,
                            'default'                   => array(
                                                            'background-color'      => '#43a047',
                                                        )
                        ),

                        array(
                            'id'                    => 'section-forums-color',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Forum', 'helper'),
                            'desc'                  => __('Forum color settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'forum_header_footer_list',
                            'type'                  => 'background',
                            'title'                 => __('Forum Header & Footer Lists', 'helper'),
                            'output'                => array('#bbpress-forums li.bbp-header', '#bbpress-forums li.bbp-footer'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#fff',
                                                    )
                        ),

                        array(
                            'id'                    => 'forum_header_footer_list_border_top',
                            'type'                  => 'border',
                            'title'                 => __('Forum Header & Footer List Top Border', 'helper'),
                            'output'                => array('#bbpress-forums li.bbp-header', '#bbpress-forums li.bbp-footer'),
                            'top'                   => true,
                            'left'                  => false,
                            'bottom'                => false,
                            'right'                 => false,
                            'all'                   => false,
                            'default'               => array(
                                'border-color'          => '#dcdcdc',
                                'border-style'          => 'solid',
                                'border-width'          => '1px',
                            )
                        ),

                        array(
                            'id'                    => 'forum_header_footer_list_border_bottom',
                            'type'                  => 'border',
                            'title'                 => __('Forum Header & Footer List Bottom Border', 'helper'),
                            'output'                => array('#bbpress-forums li.bbp-header', '#bbpress-forums li.bbp-footer'),
                            'top'                   => false,
                            'left'                  => false,
                            'bottom'                => true,
                            'right'                 => false,
                            'all'                   => false,
                            'default'               => array(
                                'border-color'          => '#dcdcdc',
                                'border-style'          => 'solid',
                                'border-width'          => '4px',
                            )
                        ),

                        array(
                            'id'                    => 'forum_content_list_border',
                            'type'                  => 'border',
                            'title'                 => __('Forum List Border', 'helper'),
                            'output'                => array('#bbpress-forums li.bbp-body ul.forum', '#bbpress-forums li.bbp-body ul.topic'),
                            'top'                   => false,
                            'left'                  => false,
                            'bottom'                => true,
                            'right'                 => false,
                            'all'                   => false,
                            'default'               => array(
                                'border-color'          => '#eeeeee',
                                'border-style'          => 'solid',
                                'border-width'          => '1px',
                            )
                        ),

                        array(
                            'id'                    => 'forum_icon_color',
                            'type'                  => 'color',
                            'title'                 => __('Forum Icon Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#bbpress-forums li.bbp-body ul.forum:before'),
                            'default'               => '#ccc',
                            'validate'              => 'color'
                        ),

                        array(
                            'id'                    => 'forum_icon_color_hover',
                            'type'                  => 'color',
                            'title'                 => __('Forum Icon Color Hover', 'helper'),
                            'active'                => false,
                            'output'                => array('#bbpress-forums li.bbp-body ul.forum:hover:before'),
                            'default'               => '#c6671a',
                            'validate'              => 'color'
                        ),

                        array(
                            'id'                    => 'topic_icon_color',
                            'type'                  => 'color',
                            'title'                 => __('Topic Icon Color', 'helper'),
                            'active'                => false,
                            'output'                => array('#bbpress-forums li.bbp-topic-title:before'),
                            'default'               => '#ccc',
                            'validate'              => 'color'
                        ),

                        array(
                            'id'                    => 'topic_icon_color_hover',
                            'type'                  => 'color',
                            'title'                 => __('Topic Icon Color Hover', 'helper'),
                            'active'                => false,
                            'output'                => array('#bbpress-forums li.bbp-topic-title:hover:before'),
                            'default'               => '#333',
                            'validate'              => 'color'
                        ),

                        array(
                            'id'                    => 'forum_info_box_background',
                            'type'                  => 'background',
                            'title'                 => __('Forum Info Box Background', 'helper'),
                            'output'                => array('div.bbp-template-notice.info'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#e1f5fe',
                                                    )
                        ),

                        array(
                            'id'                    => 'forum_info_box_border',
                            'type'                  => 'border',
                            'title'                 => __('Forum Info Box Border', 'helper'),
                            'output'                => array('div.bbp-template-notice.info'),
                            'top'                   => false,
                            'left'                  => true,
                            'bottom'                => false,
                            'right'                 => false,
                            'all'                   => false,
                            'default'               => array(
                                'border-color'          => '#81d4fa',
                                'border-style'          => 'solid',
                                'border-width'          => '5px',
                            )
                        ),

                        array(
                            'id'                    => 'forum_notice_box_background',
                            'type'                  => 'background',
                            'title'                 => __('Forum Notice Box Background', 'helper'),
                            'output'                => array('div.bbp-template-notice'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#fffde7',
                                                    )
                        ),

                        array(
                            'id'                    => 'forum_notice_box_border',
                            'type'                  => 'border',
                            'title'                 => __('Forum Notice Box Border', 'helper'),
                            'output'                => array('div.bbp-template-notice'),
                            'top'                   => false,
                            'left'                  => true,
                            'bottom'                => false,
                            'right'                 => false,
                            'all'                   => false,
                            'default'               => array(
                                'border-color'          => '#fff9c4',
                                'border-style'          => 'solid',
                                'border-width'          => '5px',
                            )
                        ),

                        array(
                            'id'                    => 'forum_error_box_background',
                            'type'                  => 'background',
                            'title'                 => __('Forum Error Notice Box Background', 'helper'),
                            'output'                => array('div.bbp-template-notice.error', 'div.bbp-template-notice.warning'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#ffebe8',
                                                    )
                        ),

                        array(
                            'id'                    => 'forum_error_box_border',
                            'type'                  => 'border',
                            'title'                 => __('Forum Error Notice Box Border', 'helper'),
                            'output'                => array('div.bbp-template-notice.error', 'div.bbp-template-notice.warning'),
                            'top'                   => false,
                            'left'                  => true,
                            'bottom'                => false,
                            'right'                 => false,
                            'all'                   => false,
                            'default'               => array(
                                'border-color'          => '#c00',
                                'border-style'          => 'solid',
                                'border-width'          => '5px',
                            )
                        ),

                        array(
                            'id'                    => 'section-appearance-footer',
                            'type'                  => 'info',
                            'icon'                  => 'el el-info-sign',
                            'title'                 => __('Footer', 'helper'),
                            'desc'                  => __('Footer settings.', 'helper'),
                        ),

                        array(
                            'id'                    => 'footer_social_border',
                            'type'                  => 'border',
                            'title'                 => __('Footer Social Icons Border', 'helper'),
                            'output'                => array('#footer-socials'),
                            'all'                   => false,
                            'bottom'                   => false,
                            'right'                   => false,
                            'left'                   => false,
                            'default'               => array(
                                'border-color'      => '#555',
                                'border-style'      => 'solid',
                                'border-width'      => '1px', 
                            )
                        ),

                        array(
                            'id'                    => 'appearance_footer_widgets_background_color',
                            'type'                  => 'background',
                            'title'                 => __('Footer Widgets Background Color', 'helper'),
                            'output'                => array('#footer-widgets'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#263548',
                                                    )
                        ),

                        array(
                            'id'                    => 'appearance_footer_social_background_color',
                            'type'                  => 'background',
                            'title'                 => __('Footer Social Background Color', 'helper'),
                            'output'                => array('#footer-socials'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                                        'background-color'      => '#fcbf49',
                                                    )
                        ),

                        array(
                            'id'                        => 'featured_post_link_color',
                            'type'                      => 'link_color',
                            'title'                     => __('Featured Post Link Color', 'helper'),
                            'active'                    => false,
                            'output'                    => array('#footer-socials .social-links a'),
                            'default'                   => array(
                                                            'regular'  => '#ffffff',
                                                            'hover'    => '#faf0bb',
                            )
                        ),

                        array(
                            'id'                    => 'appearance_get_social_footer_widgets_border',
                            'type'                  => 'border',
                            'title'                 => __('Get Social Footer Widgets Border', 'helper'),
                            'output'                => array('.widget.social-widget'),
                            'all'                   => false,
                            'bottom'                => false,
                            'top'                   => true,
                            'right'                 => false,
                            'left'                  => false,
                            'default'               => array(
                                'border-color'          => '#1d2b30',
                                'border-style'          => 'solid',
                                'border-width'          => '1px', 
                            )
                        ),

                        array(
                            'id'                    => 'appearance_footer_background_color',
                            'type'                  => 'background',
                            'title'                 => __('Footer Background Color', 'helper'),
                            'output'                => array('#footer-bottom'),
                            'preview'               => false,
                            'preview_media'         => false,
                            'background-repeat'     => false,
                            'background-attachment' => false,
                            'background-position'   => false,
                            'background-image'      => false,
                            'background-gradient'   => false,
                            'background-clip'       => false,
                            'background-origin'     => false,
                            'background-size'       => false,
                            'default'               => array(
                                'background-color'      => '#fff',
                            )
                        ),
                        
                    )
                );

                // Live Chat Code Settings
                $this->sections[] = array(
                    'icon' => 'el el-comment-alt',
                    'title' => __('Live Chat', 'helper'),
                    'fields' => array(
                        array(
                            'id'    => 'info_live_chat',
                            'type'  => 'info',
                            'title' => __('Choosing Live Chat Service', 'helper'),
                            'style' => 'warning',
                            'desc'  => __('There are a lot of live chat service providers but we highly recommend using <a href="http://bit.ly/1sF1z2z" target="_blank">Zopim</a> live chat because it is easy to use and they offer basic plan that is available for free.', 'helper')
                        ),

                        array(
                        'id'                => 'general_live_chat_code',
                        'type'              => 'ace_editor',
                        'title'             => __('Live Chat Code', 'helper'),
                        'mode'              => 'javascript',
                        'theme'             => 'monokai',
                        'desc'              => __('Paste your live chat code, you can register to chat services such as <a href="http://bit.ly/1sF1z2z" target="_blank">Zopim</a>.', 'helper'),
                        'default'           => ''
                        ),

                        array(
                            'id'                    => 'general_live_chat_code_location',
                            'type'                  => 'select',
                            'title'                 => __('Live Chat Code Location', 'helper'),
                            'desc'                  => __('Where do you want to put the live chat code?', 'helper'),
                            'placeholder'           => __('Select location', 'helper'),
                            'options'               => array(
                                                        '1' => 'Before &lt;/head&gt;',
                                                        '2' => 'Before &lt;/body&gt;',
                                                    ),
                            'default'               => '2',
                        ),
                    )
                );

                // Social Networks
                $this->sections[] = array(
                    'icon' => 'el el-user',
                    'title' => __('Social Networks', 'helper'),
                    'fields' => array(
                        array(
                            'id'                          => 'url_facebook',
                            'type'                        => 'text', 
                            'title'                       => __('Facebook Profile', 'helper'),
                            'desc'                        => __('Your Facebook profile page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_twitter',
                            'type'                        => 'text', 
                            'title'                       => __('Twitter Profile', 'helper'),
                            'desc'                        => __('Your Twitter profile page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_gplus',
                            'type'                        => 'text', 
                            'title'                       => __('Google+ Profile', 'helper'),
                            'desc'                        => __('Your Google+ profile page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_instagram',
                            'type'                        => 'text', 
                            'title'                       => __('Instagram Profile', 'helper'),
                            'desc'                        => __('Your Instagram page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_pinterest',
                            'type'                        => 'text', 
                            'title'                       => __('Pinterest Profile', 'helper'),
                            'desc'                        => __('Your Pinterest page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_youtube',
                            'type'                        => 'text', 
                            'title'                       => __('Youtube Profile', 'helper'),
                            'desc'                        => __('Your Youtube page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_vimeo',
                            'type'                        => 'text', 
                            'title'                       => __('Vimeo Profile', 'helper'),
                            'desc'                        => __('Your Vimeo page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_github',
                            'type'                        => 'text', 
                            'title'                       => __('Github Profile', 'helper'),
                            'desc'                        => __('Your Github profile page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),

                        array(
                            'id'                          => 'url_linkedin',
                            'type'                        => 'text', 
                            'title'                       => __('LinkedIn Profile', 'helper'),
                            'desc'                        => __('Your LinkedIn profile page.', 'helper'),
                            'placeholder'                 => 'http://',
                            'default'                     => '#'
                        ),
                    )
                );

            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'helper_option',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'helper' ),
                    'page_title'           => __( 'Theme Options', 'helper' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'       => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority'   => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Enable ajax save
                    'ajax_save'            => true,
                    // Show the time the page took to load, etc
                    'update_notice'        => true,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => 61,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => get_template_directory_uri() .'/images/warrior-icon.png',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => 'warriorpanel',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url' => 'https://www.facebook.com/themewarrior',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el el-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url' => 'http://twitter.com/themewarrior',
                    'title' => 'Follow us on Twitter',
                    'icon' => 'el el-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url' => 'http://themeforest.net/user/ThemeWarriors/portfolio',
                    'title' => 'See our portfolio',
                    'icon' => 'el el-shopping-cart'
                );

                // Panel Intro text -> before the form
                $this->args['intro_text'] = __('<p>If you like this theme, please consider giving it a 5 star rating on ThemeForest. <a href="http://themeforest.net/downloads" target="_blank">Rate now</a>.</p>', 'helper');

                // Add content after the form.
                // $this->args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'helper' );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_sample_config();
    } else {
        echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
