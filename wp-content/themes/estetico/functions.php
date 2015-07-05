<?php

class Registry {

  static $values = array();

  static function set($key, $value) {
    self::$values[$key] = $value;
  }

  static function get($key) {
    return isset(self::$values[$key]) ? self::$values[$key] : null;
  }
}

if ( ! isset( $content_width ) )
  $content_width = 650;

function wpml_ls_filter($languages) {
  global $sitepress;
  if($_SERVER["QUERY_STRING"]){
    if(strpos(basename($_SERVER['REQUEST_URI']), $_SERVER["QUERY_STRING"]) !== false){
      foreach($languages as $lang_code => $language){
        $languages[$lang_code]['url'] = $languages[$lang_code]['url']. '?'.$_SERVER["QUERY_STRING"];
      }
    }
  }
  return $languages;
}

if( ! function_exists( 'estetico_surpress_wpml_query_before' ) ) {
  function estetico_surpress_wpml_query_before() {
    // Surpress WPML search for local copies for the properties
    if(function_exists('icl_object_id')) {

      $option = estetico_get_setting('handle_missing_wpml_property_translations');

      if($option == 'always_show_original') {

        global $sitepress;
        // save current language
        $current_lang = $sitepress->get_current_language();
        //get the default language
        $default_lang = $sitepress->get_default_language();
        //fetch posts in default language
        $sitepress->switch_lang($default_lang);

        return $current_lang;
      }
    }
  }
}

if( ! function_exists( 'estetico_get_agents_page') ) {
  function estetico_get_agents_page() {
    
    $posts = query_posts(array(
      'post_type'     => 'page',
      'meta_key'      => '_wp_page_template',
      'mete_value'    => 'agents.php',
      'meta_compare'  => '!='
    ));

    return isset($posts[0]) ? $posts[0] : null;
  }
}

if( ! function_exists( 'estetico_surpress_wpml_query_after') ) {
  function estetico_surpress_wpml_query_after($current_lang) {
    if(function_exists('icl_object_id')) {
      
      $option = estetico_get_setting('handle_missing_wpml_property_translations');

      if($option == 'always_show_original') {
        global $sitepress;
        wp_reset_query();
        $sitepress->switch_lang($current_lang); 
      }
    }
  }
}

if( ! function_exists( 'estetico_get_all_cities' ) ) {

  function estetico_get_all_cities() {
    $args = array(
      'post_type' => 'properties',
      'post_status' => 'publish',
      'posts_per_page' => 10000000
    );
    $properties = new WP_query( $args );
    $cities = array();
    foreach($properties->posts as $post) {
      $meta = get_post_meta($post->ID);
      if(!empty($meta[USE_PREFIX . 'city'][0])) {
        $cities[] = __( $meta[USE_PREFIX . 'city'][0], THEME_NAME );
      }
    }

    $cities = array_unique($cities);
    sort($cities);
    return $cities;
  }
}

/**
 * Gets property status options,
 * defined as custom metaboxes for each property.
 * Returns an array with the values of the options.
 */
if( ! function_exists( 'estetico_get_property_status' ) ) {

    function estetico_get_property_status() {
        $boxes = estetico_get_custom_meta_boxes();
        $properties = $boxes['properties'];
        $options = array();
        
        foreach($properties as $property_field) { 
            if($property_field['id'] == USE_PREFIX . 'property_status') { 
                $options = $property_field; 
                break; 
            }
        }
        
        return $options['callback_args']['options'];
    }
}

/**
 * Gets video url,
 * defined for each property.
 * Saves the thumbnail image of the video as a value of a hidden field.
 */
if( ! function_exists( 'estetico_get_video_url' ) ) {

    function estetico_get_video_url($box_id, $prop_id) {
            $url = '';
            
            if(!empty($_POST[USE_PREFIX.'video'])) {
                $url = $_POST[USE_PREFIX.'video'];
            }
            
            $thumb_default = video_image($url);
                
            update_post_meta($prop_id, USE_PREFIX . 'video_thumb_url', $thumb_default);
    }
}

/***********************************************/
/* Get a Youtube or Vimeo video's Thumbnail from a URL
/* http://darcyclarke.me/development/get-image-for-youtube-or-vimeo-videos-from-url/
/* 
/* Copyright 2011, Darcy Clarke
/* Do what you want license
/***********************************************/
function video_image($url){
    $image_url = parse_url($url);
    
    if($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com'){
        $array = explode("&", $image_url['query']);
        return "http://img.youtube.com/vi/".substr($array[0], 2)."/default.jpg";
    } else if($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com'){
        $urlParts = explode("/", parse_url($url, PHP_URL_PATH));
        $videoId = (int)$urlParts[count($urlParts)-1];
        $hash = unserialize(file_get_contents("https://vimeo.com/api/v2/video/".$videoId.".php"));
        return $hash[0]["thumbnail_small"];
    }
}

require_once "bootstrap/init.php";

function estetico_get_pet_options() {
  $pet_options = array();

  $pets_allowed_list_setting = trim( estetico_get_setting('pets_allowed_list') );

  if($pets_allowed_list_setting != "") {
    $pets_allowed_list_raw = preg_split('/\n/', $pets_allowed_list_setting);
    foreach($pets_allowed_list_raw as $pet) {
      $key = strtolower($pet);
      $key = preg_replace('/[^a-z]/', '', $key);
      $pet_options[$key] = $pet;
    } 

  } else {
    $pet_options = array('-', '----');
  }

  return $pet_options;
}

if( ! function_exists( 'estetico_filter_the_content_properties') ) {
  function estetico_filter_the_content_properties( $content ) {

    global $post, $sitepress, $sidebar_name;

    $main_properties_page = estetico_get_setting('main_properties_page');

    if( isset( $main_properties_page[0] ) ) {

      $page_id = (int)$main_properties_page[0];
      $wpml_page_id = $post->ID;

      if(function_exists('icl_object_id')) {
        $default_language = $sitepress->get_default_language();
        $wpml_page_id = icl_object_id($post->ID, 'page', false, $default_language);
      }

      if($page_id == $wpml_page_id) {

        $sidebar_name = 'properties';

        $properties_compontent_content = "";

        ob_start();
        estetico_load_component('properties', array(
          'post' => $post
        ));
        $properties_compontent_content = ob_get_contents();
        ob_end_clean();

        $content = $content . $properties_compontent_content;
      }
    }

    return $content;
  }
}

if( ! function_exists( 'estetico_notices' ) ) {
  function estetico_notices() {
    $main_properties_page = estetico_get_setting('main_properties_page');
    if(empty($main_properties_page)) {
    ?>
    <div class="error">
      <p><?php echo __('You must setup a Properties page. You can do this from Appearance -> Theme options -> Properties settngs.', THEME_NAME) ?></p>
    </div>
    <?php
    }
  }
}

/**
 * Checks font options to see if a Google font is selected.
 * If so, options_typography_enqueue_google_font is called to enqueue the font.
 * Ensures that each Google font is only enqueued once.
 */
if ( !function_exists( 'options_typography_google_fonts' ) ) {
  function options_typography_google_fonts() {
    $all_google_fonts = array_keys( options_typography_get_google_fonts() );
    // Define all the options that possibly have a unique Google font
    $google_mixed = estetico_get_setting('google_mixed');
    // Get the font face for each option and put it in an array
    $selected_fonts = array(
      $google_mixed['face'] );
    // Remove any duplicates in the list
    $selected_fonts = array_unique($selected_fonts);
    // Check each of the unique fonts against the defined Google fonts
    // If it is a Google font, go ahead and call the function to enqueue it
    foreach ( $selected_fonts as $font ) {
      if ( in_array( $font, $all_google_fonts ) ) {
        options_typography_enqueue_google_font($font);
      }
    }
  }
}
add_action( 'wp_enqueue_scripts', 'options_typography_google_fonts' );

/**
 * Enqueues the Google $font that is passed
 */
function options_typography_enqueue_google_font($font) {
  $font = explode(',', $font);
  $font = $font[0];

  $font = str_replace(" ", "+", $font);
  wp_enqueue_style( "options_typography_$font", "http://fonts.googleapis.com/css?family=$font:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext,cyrillic", false, null, 'all' );
}

/*
 * Outputs the selected option panel styles inline into the <head>
 */
function options_typography_styles() {

    $google_mixed = estetico_get_setting('google_mixed');

    $output = '';

    $output .= '<style>html, body {font-family: ' . $google_mixed['face'] . '; color: ' . $google_mixed['color'] . '; font-size: ' . $google_mixed['size'] . '}</style>';

    echo $output;

}
add_action('wp_head', 'options_typography_styles');

/*
 * Returns a typography option in a format that can be outputted as inline CSS
 */
function options_typography_font_styles($option, $selectors) {
    $output = $selectors . ' {';
    $output .= ' color:' . $option['color'] .'; ';
    $output .= 'font-family:' . $option['face'] . '; ';
    $output .= 'font-weight:' . $option['style'] . '; ';
    $output .= 'font-size:' . $option['size'] . '; ';
    $output .= '}';
    $output .= "\n";
    return $output;
}

/*
 * Theme specific functions
 */

if( ! function_exists( 'estetico_get_properties_page_id' ) ) {

  function estetico_get_properties_page_id() {
    
    $main_properties_page = estetico_get_setting('main_properties_page');

    if(is_array($main_properties_page) && ! empty($main_properties_page)) {
      
      return $main_properties_page[0];
    }

    return false;
  }
}

if( ! function_exists( 'estetico_get_properties_page_url' ) ) {

  function estetico_get_properties_page_url($id = null) {
    if($id == null) {
      $id = estetico_get_properties_page_id();
    }

    // No such page what so ever
    if($id == null) {
      return false;
    }

    return get_permalink($id);
  }
}

if( ! function_exists( 'estetico_get_properties_page_url_wpml' ) ) {

  function estetico_get_properties_page_url_wpml($id = null) {
    global $post, $sitepress;

    if($id == null) {
      $id = estetico_get_properties_page_id();
    }

    if(function_exists('icl_object_id') && $post != null) {
      $default_language = $sitepress->get_default_language();
      $wpml_page_id = icl_object_id($post->ID, 'page', false, $default_language);

      if($wpml_page_id == $id) {
        $id = $post->ID;
      }
    }

    // No such page what so ever
    if($id == null) {
      return false;
    }

    return get_permalink($id);
  }
}


if( ! function_exists( 'estetico_get_agents_page_id' ) ) {

  function estetico_get_agents_page_id() {
    
    $main_agents_page = estetico_get_setting('main_agents_page');

    if(is_array($main_agents_page) && ! empty($main_agents_page)) {
      
      return $main_agents_page[0];
    }

    return false;
  }
}

if( ! function_exists( 'estetico_get_agents_page_url' ) ) {

  function estetico_get_agents_page_url($id = null) {
    if($id == null) {
      $id = estetico_get_agents_page_id();
    }

    // No such page what so ever
    if($id == null) {
      return false;
    }

    return get_permalink($id);
  }
}

if( ! function_exists( 'estetico_get_agents_page_url_wpml' ) ) {

  function estetico_get_agents_page_url_wpml($id = null) {
    global $post, $sitepress;

    if($id == null) {
      $id = estetico_get_agents_page_id();
    }

    if(function_exists('icl_object_id') && $post != null) {
      $default_language = $sitepress->get_default_language();
      $wpml_page_id = icl_object_id($post->ID, 'page', false, $default_language);

      if($wpml_page_id == $id) {
        $id = $post->ID;
      }
    }

    // No such page what so ever
    if($id == null) {
      return false;
    }

    return get_permalink($id);
  }
}



if( ! function_exists( 'estetico_get_quick_search_item' ) ) {

  function estetico_get_quick_search_item($id) {
    $output = '';
    $title = __( estetico_get_of_option_name( 'quick_search_items', $id ), THEME_NAME );

    switch($id) {

      case 'beds':
        $output = '<div class="styled-select">';
          $output .= '<select name="' . $id . '">';
          $output .= '<option value="">' . __('Beds', THEME_NAME) . '</option>';
          for($i = 1; $i <= 20; $i++) {
            $output .= '<option value="' . $i . '">' . $i . '+</option>';
          }
          $output .= '</select>';
        $output .= '</div>';
      break;

      case 'pets_allowed':
        $output = '<div class="styled-select">';
          $output .= '<select name="' . $id . '">';
          $output .= '<option value="">' . __('Pets allowed', THEME_NAME) . '</option>';
          $output .= '<option value="yes">' . __('Yes', THEME_NAME) . '</option>';
          $output .= '<option value="no">' . __('No', THEME_NAME) . '</option>';
          $output .= '</select>';
        $output .= '</div>';
      break;

      case 'for_sale_rent':
        $output = '<div class="styled-select">';
          $output .= '<select name="' . $id . '">';
          $output .= '<option value="">' . __('For sale or rent', THEME_NAME) . '</option>';
          $output .= '<option value="sale">' . __('Sale', THEME_NAME) . '</option>';
          $output .= '<option value="rent">' . __('Rent', THEME_NAME) . '</option>';
          $output .= '<option value="both">' . __('Both', THEME_NAME) . '</option>';
          $output .= '</select>';
        $output .= '</div>';
      break;

      case 'feature':

        $features = PropertiesManager::getAllFeatures();

        $output = '<div class="styled-select">
        <select name="' . $id . '">
          <option value="">' . $title . '</option>';

          foreach($features as $feature) {
            $output .= '<option value="' . esc_attr( $feature->slug ) . '">' . esc_html( $feature->name ) . '</option>';
          }

          $output .= '
        </select>
        </div>';
      break;

      case 'city':

        $cities = estetico_get_all_cities();

        $output = '<div class="styled-select">
        <select name="' . $id . '">
          <option value="">' . $title . '</option>';

          foreach($cities as $city) {
            $output .= '<option value="' . esc_attr( $city ) . '">' . esc_html( $city ) . '</option>';
          }

          $output .= '
        </select>
        </div>';
      break;

      case 'min_price':
      case 'max_price':
        $output = '<input type="text" name="' . $id . '" placeholder="' . esc_attr( $title ) . '" class="input text">';
      break;

      case 'bedrooms':
      case 'bathrooms':
        $output = '<div class="styled-select">
        <select name="' . $id . '">
          <option value="">' . $title . '</option>
          <option value="1">1+</option>
          <option value="2">2+</option>
          <option value="3">3+</option>
          <option value="4">4+</option>
          <option value="5">5+</option>
          <option value="6">6+</option>
        </select>
        </div>';
      break;

      case 'type':
        $output .= '<div class="styled-select">
        <select name="' . $id . '">
          <option value="">' . $title . '</option>';

          $propMgr = new PropertiesManager();
          $property_types = $propMgr->getPropertyTypes();
          foreach( $property_types as $type ) :
            $output .= '<option value="' . $type->slug . '">' . $type->name . '</option>';
          endforeach;

          $output .= '</select></div>';
      break;
    }
    return $output;
  }
}

if( ! function_exists( 'estetico_get_default_agent_id' ) ) {

  function estetico_get_default_agent_id() {

    $agent = AgentsManager::getDefaultAgent();

    if( ! $agent ) {
      return false;
    }

    return $agent->ID;
  }
}

if( ! function_exists( 'estetico_pagination_nav' ) ) {
  function estetico_pagination_nav($query = null) {
    global $wp_query;

    $query = $query == null ? $wp_query : $query;

    $big = 999999999; // need an unlikely integer

    $links = paginate_links( array(
      'base'  => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format'  => '?paged=%#%',
      'current' => max( 1, get_query_var('paged') ),
      'total'   => $query->max_num_pages,
      'type'  => 'array'
    ));

    if( empty($links) ) {
      return false;
    }
    ?>

    <div class="paging">
          <ul>
          <?php

          foreach($links as $link) : ?>
          <li>
            <?php echo $link ?>
          </li>
          <?php endforeach ?>
          </ul>

        </div>
      <?php
  }
}

if( ! function_exists( 'estetico_paging_nav' ) ) :
/**
 * Displays navigation to next/previous set of posts when applicable.
 *
 */
function estetico_paging_nav() {
  global $post;

  // Don't print empty markup if there's nowhere to navigate.
  $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
  $next     = get_adjacent_post( false, '', false );

  if ( ! $next && ! $previous )
    return;
  ?>
  <nav class="navigation post-navigation" role="navigation">
    <h5 class="screen-reader-text"><?php _e( 'Post navigation', THEME_NAME); ?></h5>
    <div class="nav-links">

      <?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', THEME_NAME ) ); ?>
      <?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', THEME_NAME ) ); ?>

    </div><!-- .nav-links -->
  </nav><!-- .navigation -->
  <?php
}
endif;

if( ! function_exists('estetico_walker_nav_menu_start_el_helper')) {
  function estetico_walker_nav_menu_start_el_helper($items) {

    if( ! is_array($items) || empty($items) ) {
      return $items;
    }

  	foreach( $items as &$item ) {

  		foreach( $items as $item_ ) {

  			if( $item_->menu_item_parent == $item->ID ) {

  				$item->classes[] = 'has-children';
  				break;
  			}
  		}
  	}

  	return $items;
  }
}

if( ! function_exists('estetico_get_post_type') ) {
  function estetico_get_post_type() {

    global $post;

    $post_type = null;

    if( isset( $_GET['post_type'] ) ) {

      $post_type = $_GET['post_type'];
    } else if( isset( $_GET['post'] ) ) {

      $post = get_post( $_GET['post'] );
      $post_type = $post->post_type;
    } else if( isset( $post ) )  {

      $post_type = $post->post_type;
    }

    return $post_type;
  }
}

if( ! function_exists( 'estetico_action_admin_menu' ) ) {
  function estetico_action_admin_menu() {

    $meta_boxes = estetico_get_custom_meta_boxes();

    $post_type = estetico_get_post_type();

    if( isset( $meta_boxes[ $post_type ] ) ) {

      foreach( $meta_boxes[ $post_type ] as $box ) {

        add_meta_box( $box['id'], $box['title'], 'estetico_show_meta_box', $post_type, isset( $box['context'] ) ? $box['context'] : 'advanced', isset( $box['priority'] ) ? $box['priority'] : 'default', isset( $box['callback_args'] ) ? $box['callback_args'] : null  );
      }
    }

    add_submenu_page( "edit.php?post_type=properties", __( 'Agencies', THEME_NAME ), __( 'Agencies', THEME_NAME ), 'manage_options', 'edit.php?post_type=agencies' );
    add_submenu_page( "edit.php?post_type=properties", __( 'Agents', THEME_NAME ), __( 'Agents', THEME_NAME ), 'manage_options', 'edit.php?post_type=agents' );
    add_submenu_page( "edit.php?post_type=properties", __( 'Testimonials', THEME_NAME ), __( 'Testimonials', THEME_NAME ), 'manage_options', 'edit.php?post_type=testimonials' );
    add_submenu_page( "edit.php?post_type=properties", __( 'FAQ', THEME_NAME ), __( 'FAQ', THEME_NAME), 'manage_options', 'edit.php?post_type=faq' );

  }
}

if( ! function_exists( 'estetico_action_save_post' ) ) {
  function estetico_action_save_post( $post_id ) {

    $post_type = estetico_get_post_type();
    $meta_boxes = estetico_get_custom_meta_boxes();

    if( ! isset( $meta_boxes[ $post_type ] ) ) {

      return true;
    }

    foreach( $meta_boxes[ $post_type ] as $box ) {

      $box_id = $box['id'];

      if( isset( $_POST[ $box_id ] ) ) {

        $value = $_POST[ $box_id ];

        if( isset( $box['before_save'] ) && function_exists( $box['before_save'] ) ) {

          call_user_func( $box['before_save'], $box_id, $post_id );
        }

        $readonly = isset($box['readonly']) ? $box['readonly'] : false;

        if(false === $readonly) {
          update_post_meta( $post_id, $box_id, $value );
        }
      }
    }
  }
}

if( ! function_exists( 'estetico_init_meta_box_value' ) ) {
  function estetico_init_meta_box_value($type, $key, $value ) {

    $options = array( 'post_type' => $type, 'posts_per_page' => PHP_INT_MAX );
    $posts = get_posts( $options );

    foreach( $posts as $post ) {
      $meta = get_post_meta( $post->ID );

      if(!isset($meta[$key])) {
        update_post_meta( $post->ID, $key, $value );
      }
    }
  }
}

if( ! function_exists( 'estetico_show_meta_box' ) ) {
  function estetico_show_meta_box( $post, $args ) {

    $post_meta = get_post_meta( $post->ID );

    $value = isset( $post_meta[ $args['id'] ] ) ? $post_meta[ $args['id'] ][0] : null;

    if( isset( $args['args'] ) ) {

      $c_args = $args['args'];

      // Find all posts with that post_type
      if( isset( $c_args['post_type'] ) ) {

        $options = array( 'post_type' => $c_args['post_type'], 'posts_per_page' => PHP_INT_MAX );

        $posts = get_posts( $options );

        $select_options = array();
        $select_options['_'] = isset( $c_args['default_option'] ) ? __( $c_args['default_option'], THEME_NAME) : "-";
        foreach( $posts as $_post ) {
          $select_options[$_post->ID] = $_post->post_title;
        }

        if($value == null) {
          if(isset($c_args['selected'])) {
            $value = $c_args['selected'];
          }
        }

        $select = new SelectFormControl(array(
          'options' => $select_options,
          'value' => $value,
          'name' => $args['id'],
          'multiple' => isset($c_args['multiple']) ? $c_args['multiple'] : false
        ));

        $output = $select;

      } else if( isset( $c_args['type'] ) ) {

        if($c_args['type'] == 'hidden') {

          $input = new InputFormControl(array(
            'type' => 'text',
            'name' => $args['id'],
            'id' => $args['id'] . '_hidden',
            'value' => $value,
            'class' => 'hide-postbox'
          ));

          $output = $input;
        } else if( $c_args['type'] == 'checkbox' ) {

          $input = new InputFormControl(array(
            'type' => 'hidden',
            'name' => $args['id'],
            'id' => $args['id'] . '_hidden',
            'value' => 'off'
          ));

          $output = $input;

          $input = new InputFormControl(array(
            'type' => 'checkbox',
            'name' => $args['id'],
            'checked' => $value == 'on',
            'value' => 'on'
          ));

          $output .= $input;
        } else if ( $c_args['type'] == 'select' ) {

          foreach( $c_args['options'] as $option_key => $option_value ) {
            $options[$option_key] = $option_value;
          }

          $select = new SelectFormControl(array(
            'name' => $args['id'],
            'options' => $options,
            'value' => $value
          ));

          $output = $select;
        } else if( $c_args['type'] == 'street_view_pov') {
          $output = '<script src="https://maps.googleapis.com/maps/api/js?key=&sensor=true"></script>';
          $output .= '<div class="google-street-view-pov-adjust"></div>';
          $output .= new InputFormControl(array(
            'name' => $args['id'],
            'type' => 'hidden',
            'value' => $value
          ));
        }
      } else {

        $options = array(
          'name' => $args['id'],
          'value' => $value
        );

        if( isset( $c_args['use_geofinder'] ) && $c_args['use_geofinder'] ) {

          $options['append'] = new InputFormControl(array(
            'value' => __('Find coordinates', THEME_NAME),
            'class' => 'find-coordinates',
            'type' => 'button'
          ));
        }

        if(isset($c_args['autocomplete']) && $c_args['autocomplete'] == true) {
          $options['class'] = 'suggest-input';
        }

        $input = new InputFormControl($options);

        $output = $input;

        if(!empty($c_args['desc'])) {
          $output .= '<div class="description">' . $c_args['desc'] . '</div>';
        }
      }
    } else {

      $options = array(
        'name' => $args['id'],
        'value' => $value
      );

      if( isset( $args['use_geofinder'] ) && $args['use_geofinder'] ) {

        $options['append'] = new InputFormControl(array(
          'value' => __('Find coordinates', THEME_NAME),
          'class' => 'find-coordinates',
          'type' => 'button'
        ));
      }

      $input = new InputFormControl($options);

      $output = $input;
    }

    if( isset( $output ) ) {
      echo $output;
    }
  }
}

if( ! function_exists( 'estetico_unique_custom_field' ) ) {
  function estetico_unique_custom_field( $key ) {

    // Get all posts with that type
    $posts = get_posts( array( 'post_type' => estetico_get_post_type() ) );

    foreach( $posts as $post ) {

      update_post_meta( $post->ID, $key, 'off' );
    }
  }
}

if( ! function_exists( 'estetico_manage_properties_columns') ) {
  function estetico_manage_properties_columns( $defaults ) {

    $defaults[USE_PREFIX . 'price']     = __( 'Price', THEME_NAME );
    $defaults[USE_PREFIX . 'bedrooms']  = __( 'Bedrooms', THEME_NAME );
    $defaults[USE_PREFIX . 'featured']  = __( 'Featured', THEME_NAME );

    return $defaults;
  }
}

if( ! function_exists( 'estetico_manage_properties_posts_custom_column' ) ) {
  function estetico_manage_properties_posts_custom_column( $column, $post_id ) {

    if( $column == USE_PREFIX . 'featured' ) {

      $meta = get_post_meta( $post_id, $column, true);

      if( $meta == 'off' || empty( $meta ) ) {
        echo '<a href="#" class="set-featured" data-set-featured="on" data-post-id="' . $post_id . '">No</a>';
      } else {
        echo '<a href="#" class="set-featured" data-set-featured="off" data-post-id="' . $post_id . '">Yes</a>';
      }
    } else {
      echo get_post_meta( $post_id, $column, true );
    }
  }
}

if( ! function_exists( 'estetico_custom_admin_js' ) ) {
  function estetico_custom_admin_js() {

      $main_js_url = get_bloginfo('template_directory') . '/assets/core/js/wp-admin/main.js';
      $jquery_ui_js_url = get_bloginfo('template_directory') . '/assets/modules/jquery-ui/js/jquery-ui-1.10.4.custom.min.js';

      echo '<script type="text/javascript" defer src="'. $jquery_ui_js_url . '"></script>';
      echo '<script type="text/javascript" defer src="'. $main_js_url . '"></script>';
      echo '<script>var _site_url = \'' . site_url() . '\';</script>';
      echo '<script>var _site_name_prefix = \'' . USE_PREFIX . '\';</script>';
      echo '<script>var _template_url = \'' . get_bloginfo('template_directory') . '\';</script>';
  }
}

if( ! function_exists( 'estetico_custom_admin_css' ) ) {
  function estetico_custom_admin_css() {

      $url = get_bloginfo('template_directory') . '/assets/core/css/wp-admin/style.css';
      $query_ui_css_url = get_bloginfo('template_directory') . '/assets/modules/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css';

      echo '<link rel="stylesheet" href="' . $url . '">';
      echo '<link rel="stylesheet" href="' . $query_ui_css_url . '">';
  }
}

if( ! function_exists( 'estetico_add_body_class' ) ) {
  function estetico_add_body_class($classes) {
    global $template_name;

    if( is_front_page() || $template_name == 'home') {
      $classes[] = 'home';
    } else {
      $classes[] = 'inner';
    }

    if( estetico_show_slider() ) {
      $classes[] = 'show-rev-slider';
    } else {
      $classes[] = 'show-flat-header';
    }

    try {
  	  if(class_exists('RevSlider')) {
  		  $revSlider = new RevSlider();
  		  $revSlider->initByAlias(estetico_get_setting('revslider_id'));
  	  }
    } catch(Exception $ex) {
      $classes[] = "missing-rev-slider";
    }

    if( false == estetico_show_slider() && '' == estetico_get_setting('inner_pages_header') ) {
      $classes[] = 'empty-header';
    }

    $sidebar_position = estetico_get_setting( 'sidebar_position' );

    $classes[] = 'sidebar-position-' . $sidebar_position;

    return $classes;
  }
}

if( ! function_exists( 'estetico_is_homepage') ) {
  function estetico_is_homepage() {
    global $template_name;

    return is_front_page() || (isset($template_name) && $template_name == 'home');
  }
}

if( ! function_exists( 'estetico_show_slider') ) {
  function estetico_show_slider() {

	  if( ! class_exists('RevSlider')) {
		  return false;
	  }

    $is_homepage = estetico_is_homepage();
    $header_configuration = estetico_get_setting('header_configuration');

    $bool = ( $is_homepage && ( $header_configuration == 'home_slider_inner_flat' || $header_configuration == 'home_slider_inner_slider' ) )
          || ( ! $is_homepage && ( $header_configuration == 'home_flat_inner_slider' || $header_configuration == 'home_slider_inner_slider') );

    return $bool;
  }
}

if( ! function_exists( 'estetico_register_required_plugins' ) ) {
  function estetico_register_required_plugins() {
    $plugins = array(
      array(
        'name'  => 'Revolution Slider',
        'slug'  => 'revslider',
        'source' => get_template_directory() . '/bootstrap/lib/plugins/revslider.zip',
        'required' => true
      ),
      array(
        'name'  => 'Visual Composer',
        'slug'  => 'js_composer',
        'source' => get_template_directory() . '/bootstrap/lib/plugins/js_composer.zip',
        'required' => true
      ),
      array(
        'name' => 'Envanto Wordpress Toolkit',
        'slug' => 'envato-wordpress-toolkit',
        'source' => get_template_directory() . '/bootstrap/lib/plugins/envato-wordpress-toolkit.zip',
        'required' => false
      )
    );

    $config = array(
      'is_automatic' => true
    );

    tgmpa( $plugins, $config );
  }
}

if( ! function_exists('estetico_after_setup_theme') ) {
  function estetico_after_setup_theme() {
    load_theme_textdomain( THEME_NAME, get_template_directory() . '/languages' );
  }
}
add_action('after_setup_theme', 'estetico_after_setup_theme');

if( ! function_exists( 'estetico_wp_enqueue_scripts' ) ) {
  function estetico_wp_enqueue_scripts() {

    #wp_enqueue_style( 'google-font-open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext,cyrillic' );
    wp_enqueue_style( 'framework',  get_template_directory_uri() . '/assets/core/css/framework.css' );
    wp_enqueue_style( 'bxslider', get_template_directory_uri() . '/assets/modules/bxslider/jquery.bxslider.css' );
    wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/core/css/main.css' );
    wp_enqueue_style( 'style', get_stylesheet_uri() );

    $estetico_colour_skin = estetico_get_setting('colour_skin');

    if( ! empty( $_COOKIE['estetico_colour_skin'] ) ) {
      $estetico_colour_skin = $_COOKIE['estetico_colour_skin'];
    }

    #wp_enqueue_style( 'skin-custom', get_template_directory_uri() . '/assets/skins/skin.php');
    #wp_enqueue_style( 'skin', get_template_directory_uri() . '/assets/skins/' . $estetico_colour_skin . '/css/skin.css' );

    if(estetico_get_setting('enable_color_overwrite') == 'yes') {
      wp_enqueue_style( 'skin-overwrite', get_template_directory_uri() . '/assets/skins/skin.php?overwrite=true');
    } else {
      wp_enqueue_style( 'skin-custom', get_template_directory_uri() . '/assets/skins/skin.php');
    }

    wp_enqueue_style( 'prettyPhoto', get_template_directory_uri() . '/assets/modules/prettyphoto/css/prettyPhoto.css' );
    wp_enqueue_style( 'hovereffect', get_template_directory_uri() . '/assets/modules/hovereffect/styles.css' );
    wp_enqueue_style( 'exposure', get_template_directory_uri() . '/assets/modules/exposure/exposure.css' );
    wp_enqueue_style( 'jquery-ui', get_template_directory_uri() . '/assets/modules/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css' );
    wp_enqueue_style( 'lightbox', get_template_directory_uri() . '/assets/modules/lightbox/css/lightbox.css' );

    // Load in the head
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/core/js/libs/modernizr-2.6.2.min.js' );
    $google_maps_api_key = estetico_get_setting('google_maps_api_key');
    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?libraries=places&key=' . $google_maps_api_key . '&sensor=true' ); // Google Maps API must be loaded at the head

    // Load in the footer
    wp_enqueue_script( 'prettyPhoto', get_template_directory_uri() . '/assets/modules/prettyphoto/js/jquery.prettyPhoto.js', array(), null, true);
    wp_enqueue_script( 'hovereffect', get_template_directory_uri() . '/assets/modules/hovereffect/custom.js', array(), null, true );
    wp_enqueue_script( 'exposure', get_template_directory_uri() . '/assets/modules/exposure/jquery.exposure.min.js', array(), null, true );
    wp_enqueue_script( 'jquery-ui', get_template_directory_uri() . '/assets/modules/jquery-ui/js/jquery-ui-1.10.4.custom.min.js', array(), null, true );
    wp_enqueue_script( 'lightbox', get_template_directory_uri() . '/assets/modules/lightbox/js/lightbox.min.js', array(), null, true );
    wp_enqueue_script( 'properties-map', get_template_directory_uri() . '/assets/core/js/properties-map.js', array(), null, true );
    wp_enqueue_script( 'properties-filter', get_template_directory_uri() . '/assets/core/js/properties-filter.js', array(), null, true );

    wp_enqueue_script('bxslider', get_template_directory_uri() . '/assets/modules/bxslider/jquery.bxslider.min.js', array(), null, true );
    wp_enqueue_script('main', get_template_directory_uri() . '/assets/core/js/main.js', array(), null, true );
    
    if ( is_singular() ) {
      wp_enqueue_script( 'comment-reply' );
    }
  }
}

add_action('wp_enqueue_scripts', 'estetico_wp_enqueue_scripts');

if( ! function_exists( 'init_theme' ) ) {
  function init_theme() {

    require_once "bootstrap/inc/vendor/breadcrumb-trail/breadcrumb-trail.php";

    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'widgets' );
    add_theme_support( 'automatic-feed-links' );

    register_taxonomy( 'properties-features', 'properties', array( 'label' => __( 'Unique features', THEME_NAME ), 'show_in_nav_menus' => false, 'rewrite' => false ) ) ;
    register_taxonomy( 'properties-types', 'properties', array( 'label' => __( 'Property types', THEME_NAME ), 'show_in_nav_menus' => true, 'rewrite' => false ) );

    register_nav_menu( 'primary', __( 'Navigation Menu', THEME_NAME ) );
    register_nav_menu( 'footer_1', __( 'Footer Menu 1', THEME_NAME ) );
    register_nav_menu( 'footer_2', __( 'Footer Menu 2', THEME_NAME ) );

    register_sidebar( array(
      'name' => __('Default sidebar', THEME_NAME),
      'id' => 'default_sidebar',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar( array(
      'name' => __( 'Properties sidebar', THEME_NAME ),
      'id' => 'properties_sidebar',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar( array(
      'name' => __('Property details sidebar', THEME_NAME),
      'id' => 'property_details_sidebar',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar( array(
      'name' => __('Blog sidebar', THEME_NAME),
      'id' => 'blog_sidebar',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    add_image_size( 'properties-gallery-thumbnail', estetico_get_setting( 'image_properties_gallery_thumbnail_width' ), estetico_get_setting( 'image_properties_gallery_thumbnail_height' ), true );
    add_image_size( 'properties-featured-thumbnail', estetico_get_setting( 'image_properties_featured_thumbnail_width' ), estetico_get_setting( 'image_properties_featured_thumbnail_height' ), true );
    add_image_size( 'properties-carousel-thumbnail', estetico_get_setting( 'image_properties_carousel_thumbnail_width' ), estetico_get_setting( 'image_properties_carousel_thumbnail_height' ), true );
    add_image_size( 'properties-gallery-normal', estetico_get_setting( 'image_properties_gallery_normal_width' ), estetico_get_setting( 'image_properties_gallery_normal_height' ), true );
    add_image_size( 'properties-listing', estetico_get_setting( 'image_properties_listing_width' ), estetico_get_setting( 'image_properties_listing_height' ), true );

    add_filter('body_class', 'estetico_add_body_class');

    add_filter('wp_nav_menu_objects', 'estetico_walker_nav_menu_start_el_helper', 10, 2);

    // Basic qTranslate support
    if(function_exists('qtrans_convertURL')) {
      add_filter('post_type_link', 'qtrans_convertURL');
    }

    add_shortcodes_post_init();

    require_once 'bootstrap/after_init/release-1_3.php';
    require_once 'bootstrap/after_init/release-1_3_3.php';
    
    add_filter('wp_nav_menu_items','estetico_apply_relative_menu_item', 10, 2);
  }
}

/*
Plugin Name: Relative Menu Item
Description: Create custom menu items relative to the root url of the current wordpress installation
Plugin URI: http://bitbucket.org/perchten/wordpress-relative-menu-item-plugin
Version: 0.1
License: GPL
Author: Perch Ten Design
Author URI: perchten.co.uk
*/
function estetico_apply_relative_menu_item($items, $args){
    global $MYTEST;
    $items = preg_replace("#http://%ROOT%#", get_bloginfo("url"), $items);
    $MYTEST[] = $items;
    return $items;
}

// Do not show Visual Composer alert about license
function estetico_vc_set_as_theme() {
  vc_set_as_theme($disable_updater = true);
}

add_action( 'vc_before_init', 'estetico_vc_set_as_theme' );


?>
