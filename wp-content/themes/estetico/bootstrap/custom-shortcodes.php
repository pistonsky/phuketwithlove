<?php

if( ! function_exists( 'estetico_card_func' ) ) {
  function estetico_card_func( $atts ) {

    extract( shortcode_atts( array( 
      'content_pull' => null,
      'title' => '',
      'content_text' => '',
      'read_more_button_txt' => '',
      'read_more_button_link' => '',
      'page_id' => null,
      'image' => null
    ), $atts));

    $output = "";

    if( $content_pull == "current_pages" && $page_id != null ) {
     try {
        $page = new Page($page_id);
        $title = !empty($title) ? $title : $page->getTitle();
        $content_text = !empty($content_text) ? $content_text : $page->getExcerpt();
        $read_more_button_link = $page->getLink();
        $read_more_button_txt = __( 'Read more', THEME_NAME );

        if($image == null) {
          $image = $page->getImage(array(150, 150));
        } else {
          $image = wp_get_attachment_image($image, array(150, 150), true);
        }
      } catch(Exception $ex) {
        $image = wp_get_attachment_image($image, array(150, 150), true);
      }
    } else {
      if(wp_attachment_is_image($image)) {
        $image = @wp_get_attachment_image($image, array(150, 150), true);
      } else {
        $image = "";
      }
    }

    $output .= '
    <div class="card">
        <div class="photo">
          ' . ( $image != null ? $image : '' ) . '
        </div>
        <div class="data">
          <h6>' . $title . '</h6>
          <div class="descr">' . $content_text . '</div>' .
          (!empty($read_more_button_txt) ? ( '<a href="' . $read_more_button_link . '" class="button th-brown">' . $read_more_button_txt . '</a>' ) : '' ) .
        '</div>
      </div>';

    return $output;
  }
}

if( ! function_exists( 'estetico_vision_func' ) ) {
  function estetico_vision_func( $atts ) {
    extract( shortcode_atts( array(
        "title" => "",
        "content_text" => "",
        "icon" => "",
        "custom_icon" => null,
        "content_pull" => null,
        "page_id" => null
      )
    , $atts));

    if($content_pull == "current_pages" && $page_id != null) {
      try {
        $page = new Page($page_id);
        $title = !empty($title) ? $title : $page->getTitle();
        $content_text = !empty($content_text) ? $content_text : $page->getExcerpt();
      } catch(Exception $ex) {

      }
    }

    if($custom_icon) {
      $custom_icon = wp_get_attachment_image($custom_icon, array(70, 70), true);
      $icon = 'custom-icon';
    }

    $output = '';

      $output .= '<div class="vision">';
        $output .= '<b class="icon rounded ' . $icon . '">' . $custom_icon . '</b>';
        $output .= '<h6>' . $title . '</h6>';
        $output .= '<p>' . $content_text . '</p>';
      $output .= '</div>';

    return $output;
  }
}

if( ! function_exists( 'estetico_properties_map_func' ) ) {
  function estetico_properties_map_func( $atts ) {
    estetico_load_component('map', shortcode_atts(array(
      'title' => __( 'Browse by map', THEME_NAME )
    ), $atts));
  }
}

if( ! function_exists( 'estetico_properties_carousel_func' ) ) {
  function estetico_properties_carousel_func( $atts ) {
    estetico_load_component('carousel', shortcode_atts(array(
      'title' => __( 'Great offers for this month', THEME_NAME ),
      'auto_slide' =>  !empty($atts['auto_slide']) && $atts['auto_slide'] == 'yes',
      'infinite_loop' =>  !empty($atts['infinite_loop']) && $atts['infinite_loop'] == 'yes'
    ), $atts));
  }
}

if( ! function_exists( 'estetico_separator_func' ) ) {
  function estetico_separator_func() {
    estetico_load_component('separator');
  }
}

if( ! function_exists( 'estetico_separator_title_func' ) ) {
  function estetico_separator_title_func( $atts ) {

    estetico_load_component('separator-title', shortcode_atts(array(
      'title' => null
    ), $atts));
  }
}

if( ! function_exists( 'estetico_testimonials_shortcode' ) ) {
  function estetico_testimonials_shortcode( $atts ) {

    extract( shortcode_atts( array(
        "list_style" => "carousel"
      )
    , $atts));

    if($list_style == "carousel") {
      estetico_load_component('testimonials');
    } else {
      estetico_load_component('testimonials-list');
    }    
  }
}

/* Deprecated component. Left here for backward compatibility. */
if( ! function_exists( 'estetico_testimonials_list_func' ) ) {
  function estetico_testimonials_list_func() {
    estetico_load_component('testimonials-list');
  }
}

if( ! function_exists( 'estetico_faq_func' ) ) {
  function estetico_faq_func($atts) {
    estetico_load_component('faq');
  }
}

if( ! function_exists( 'estetico_message_func' ) ) {
  function estetico_message_func( $atts ) {
    extract(shortcode_atts(array(
      'text' => '',
      'type' => 'info',
      'style' => 'box'
    ), $atts));

    return '<div class="message ' . $type . ' ' . $style . '">' . $text . '</div>';
  }
}

if( ! function_exists( 'estetico_gallery_func' ) ) {
  function estetico_gallery_func($atts) {
    extract(shortcode_atts(array(
      'ids' => ''
    ), $atts));

    $images = array();

   if(!empty($ids)) {
    $images = explode(',', $ids);
   }

   ob_start();
   estetico_load_component('gallery', array(
      'images' => $images
    ));
   $content = ob_get_contents();
   ob_end_clean();

   return $content;
  }
}

if( ! function_exists( 'estetico_properties_shortcode' ) ) {
  function estetico_properties_shortcode($atts) {
    extract(shortcode_atts(array(
    ), $atts));

    if($atts['list_style'] == 'map') {
      $atts['all'] = true;
    }
    
    if($atts['list_style'] == 'carousel') {
        extract(shortcode_atts(array(
            'auto_slide' =>  !empty($atts['auto_slide']) && $atts['auto_slide'] == 'yes',
            'infinite_loop' =>  !empty($atts['infinite_loop']) && $atts['infinite_loop'] == 'yes'
        ), $atts));
    }

    $properties           = PropertiesManager::getFiltered($atts);
    $found_posts          = PropertiesManager::getLastQueryFoundItemsCount();
    $properties_per_page  = (int)estetico_get_setting( 'properties_per_page' );
    $start_page           = isset($_GET['start_page']) ? (int) $_GET['start_page'] : 1;

    extract($atts);

    $content = '<div class="properties-shortcode">';

    ob_start();

    require COMPONENTS_PATH . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "properties.php";

    $content .= ob_get_contents();

    ob_end_clean();

    $content .= '<div class="clearfix"></div></div>';

    return $content;
  }
}

add_shortcode('card', 'estetico_card_func');
add_shortcode('vision', 'estetico_vision_func');
add_shortcode('properties_map', 'estetico_properties_map_func');
add_shortcode('properties_carousel', 'estetico_properties_carousel_func');
add_shortcode('properties', 'estetico_properties_shortcode');
add_shortcode('separator', 'estetico_separator_func');
add_shortcode('separator_title', 'estetico_separator_title_func');
add_shortcode('testimonials', 'estetico_testimonials_shortcode');
add_shortcode('testimonials_list', 'estetico_testimonials_list_func');
add_shortcode('message', 'estetico_message_func');
add_shortcode('faq', 'estetico_faq_func');
add_shortcode('gallery', 'estetico_gallery_func');

$pages = get_posts(array(
  'post_type' => 'page'
));

$pages_value = array( __( 'Select an existing page', THEME_NAME ) => '_' );
foreach( $pages as $page) {
  $pages_value[$page->post_title] = $page->ID;
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'FAQ', THEME_NAME ),
    "base" => 'faq',
    'category' => __('Estetico'),
    'params' => array(
      array(
        "type"        => "",
        "param_name"       => "",
        "description" => __("Adds a FAQ section that can be defined afterwards from Dashboard > Properties > FAQ.", THEME_NAME)
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name"      => __( 'Testimonials', THEME_NAME ),
    "base"      => 'testimonials',
    'category'  => __('Estetico'),
    'params'    => array(
      array(
        "type"        => "dropdown",
        "param_name"  => "list_style",
        "value"       => array( __('List', THEME_NAME) => 'list', __('Carousel', THEME_NAME) => 'carousel' ),
        "heading"     => __('Style', THEME_NAME)
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'Separator', THEME_NAME ),
    "base" => 'separator',
    'category' => __('Estetico'),
    'params' => array(
      array(
        "type"        => "",
        "param_name"       => "",
        "description" => __("Adds a horizontal separator with a graphical symbol in the middle.", THEME_NAME)
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'Separator with title', THEME_NAME ),
    "base" => 'separator_title',
    'category' => __('Estetico'),
    'params' => array(
      array(
        "type" => 'textfield',
        'param_name' => 'title',
        'heading' => __( 'Title', THEME_NAME )
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'Properties map', THEME_NAME ),
    "base" => 'properties_map',
    'category' => __('Estetico'),
    'params' => array(
      array(
        "type" => 'textfield',
        'param_name' => 'title',
        'heading' => __( 'Map title', THEME_NAME )
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'Properties carousel', THEME_NAME ),
    "base" => 'properties_carousel',
    'category' => __('Estetico'),
    'params' => array(
      array(
        "type" => 'textarea',
        'param_name' => 'title',
        'heading' => __('Carousel title', THEME_NAME)
      ),
       array(
        "type"        => 'checkbox',
        'param_name'  => 'auto_slide',
        'heading'     => __('Auto slide', THEME_NAME),
        'value'       => array( __('Yes') => 'yes'),
        'description' => __('Automatically slide properties in the carousel.', THEME_NAME)
      ),
      array(
        "type"        => 'checkbox',
        'param_name'  => 'infinite_loop',
        'heading'     => __('Infinite slide', THEME_NAME),
        'value'       => array( __('Yes') => 'yes'),
        'description' => __('Infinitely slide properties in the carousel.', THEME_NAME)
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'Message', THEME_NAME ),
    "base" => 'message',
    'category' => __('Estetico', THEME_NAME),
    'params' => array(
      array(
        "type" => 'textarea',
        'param_name' => 'text',
        'heading' => __('Message', THEME_NAME)
      ),
      array( 
        "type" => "dropdown",
        "heading" => __( "Type", THEME_NAME),
        "param_name" => "type",
        "class" => "type",
        "value" => array( __( 'Info', THEME_NAME) => 'info', __( 'Error', THEME_NAME ) => 'error', __( 'Success', THEME_NAME ) => 'success', __( 'Warning', THEME_NAME ) => 'warning' ),
        "description" => __( 'Choose what kind of visual feedback the massage should have.', THEME_NAME )
      ),
      array( 
        "type" => "dropdown",
        "heading" => __( "Style" ),
        "param_name" => "style",
        "class" => "style",
        "value" => array( __( 'Box', THEME_NAME ) => 'box', __( 'Dashed', THEME_NAME ) => 'simple' ),
        "description" => __( 'Choose the style of the message.', THEME_NAME )
      )
    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map( array(
    "name" => __( "Vision", THEME_NAME ),
    "base" => "vision",
    "class" => "",
    "category" => __('Estetico'),
    'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/extend.js', get_template_directory_uri().'/vc_extend/vision.js'),
    'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/extend.css'),
    "params" => array(

      array(
           "type" => "dropdown",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Content pull", THEME_NAME ),
           "param_name" => "content_pull",
           "value" => array('Select a content pull type' => '_', 'Current pages' => 'current_pages', 'Manual type-in' => 'manual_type_in'),
           "description" => __("What data to use. Caution! If you pick \"Current Pages\" and then a specific page and use any of the fields below they will overwrite the page ones.", THEME_NAME)
        ),

      array(
           "type" => "dropdown",
           "holder" => "div",
           "class" => "",
           "heading" => __("Page", THEME_NAME),
           "param_name" => "page_id",
           "value" => $pages_value,
           "description" => __("Choose from your existing pages.", THEME_NAME)
        ),

      array( 
        "type" => "dropdown",
        "heading" => __( "Icon" ),
        "param_name" => "icon",
        "class" => "icon",
        "value" => array( __( 'No icon', THEME_NAME ) => '_', __( 'House', THEME_NAME ) => 'icon-house', __( 'Location', THEME_NAME ) => 'icon-location' ),
        "description" => __( 'Choose a vision icon' )
        ),

      array(
        "type" => "attach_image",
        "heading" => "Custom icon",
        "param_name" => "custom_icon",
        "value" => "",
        "description" => __( 'If you cannot find a suitable icon you can upload your own. This will overwrite preset icon. Icon will be resized to 70 x 70.', THEME_NAME )
        ),

      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => __( 'Title', THEME_NAME ),
        "param_name" => 'title',
        "value" => ""
      ),

       array(
        "type" => "textarea",
        "holder" => "div",
        "class" => "",
        "heading" => __( 'Content', THEME_NAME ),
        "param_name" => 'content_text',
        "value" => ""
      )

    )
  ));
}

if( function_exists( 'vc_map' ) ) {
  vc_map( array(
     "name" => __( "Card", THEME_NAME ),
     "base" => "card",
     "class" => "",
     "category" => __('Estetico'),
     'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/extend.js', get_template_directory_uri().'/vc_extend/card.js'),
     'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/extend.css'),
     "params" => array(
        
        array(
           "type" => "dropdown",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Content pull", THEME_NAME ),
           "param_name" => "content_pull",
           "value" => array( __( 'Select a content pull type', THEME_NAME ) => '_', __( 'Current pages', THEME_NAME ) => 'current_pages', __( 'Manual type-in', THEME_NAME ) => 'manual_type_in'),
           "description" => __( "Location of data", THEME_NAME )
        ),

        array(
           "type" => "dropdown",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Page", THEME_NAME ),
           "param_name" => "page_id",
           "value" => $pages_value,
           "description" => __( "Choose from your existing pages.", THEME_NAME )
        ),
        
        array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Title", THEME_NAME ),
           "param_name" => "title",
           "value" => "",
           "description" => __( "Page title", THEME_NAME ),
        ),
        array(
           "type" => "textarea_html",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Content", THEME_NAME ),
           "param_name" => "content_text",
           "value" => "",
           "description" => __( "Page content", THEME_NAME ),
        ),
         array(
           "type" => "attach_image",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Image", THEME_NAME ),
           "param_name" => "image",
           "value" => "",
           "description" => __( "Page content", THEME_NAME ),
        ),
         array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Read more button", THEME_NAME ),
           "param_name" => "read_more_button_txt",
           "value" => "",
           "description" => __( "Page content", THEME_NAME ),
        ),
          array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => __( "Read more button link", THEME_NAME ),
           "param_name" => "read_more_button_link",
           "value" => "",
           "description" => __( "Page content", THEME_NAME ),
        )
     )
  ));
}

function add_shortcodes_post_init() {
  $features = PropertiesManager::getAllFeatures();
  $features_array = array();

  foreach($features as $feature) {
    $features_array[$feature->name] = $feature->slug;
  }

  $types = PropertiesManager::getAllTypes();
  $types_array = array();

  foreach($types as $type) {
    $types_array[$type->name] = $type->slug;
  }

  $cities = array('-' => '');
  $_cities = estetico_get_all_cities();
  foreach( $_cities as $_city ) {
    $cities[$_city] = $_city;
  }

  unset($_cities, $_city);

if( function_exists( 'vc_map' ) ) {
  vc_map(array(
    "name" => __( 'Properties', THEME_NAME ),
    "base" => 'properties',
    'category' => __('Estetico'),
    'params' => array(
      array(
        "type"        => 'dropdown',
        'param_name'  => 'list_style',
        'heading'     => __('Style', THEME_NAME),
        "value"       => array(__('Grid', THEME_NAME) => 'grid', __('List', THEME_NAME) => 'list', __('Carousel', THEME_NAME) => 'carousel', __('Map', THEME_NAME) => 'map', __('List + Map', THEME_NAME) => 'list_map', __('Grid + Map', THEME_NAME) => 'grid_map'),
        "description" => __('Choose the style your properties will be visualized to the visitors', THEME_NAME)
      ),
      array(
        "type"        => 'dropdown',
        'param_name'  => 'sort_by',
        'heading'     => __('Sort by', THEME_NAME),
        "value"       => array(__('None', THEME_NAME) => 'none', __('Price (low to high)', THEME_NAME) => 'price_low_to_high', __('Price (high to low)', THEME_NAME) => 'price_high_to_low', __('View count (low to high)', THEME_NAME) => 'view_count_low_to_high', __('View count (high to low)', THEME_NAME) => 'view_count_high_to_low', __('Date (low to high)', THEME_NAME) => 'date_low_to_high', __('Date (high to low)', THEME_NAME) => 'date_low_to_high'),
        "description" => __('Choose how to sort properties', THEME_NAME)
      ),
      array(
        "type"        => 'textfield',
        'param_name'  => 'min_price',
        'heading'     => __('Price minimum', THEME_NAME)
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'max_price',
        'heading' => __('Price maximum', THEME_NAME)
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'bedrooms_min',
        'heading' => __('Bedrooms minimum', THEME_NAME)
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'bedrooms_max',
        'heading' => __('Bedrooms maximum', THEME_NAME)
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'bathrooms_min',
        'heading' => __('Bathrooms minimum', THEME_NAME)
      ),
      array(
        "type"        => 'textfield',
        'param_name'  => 'bathrooms_max',
        'heading'     => __('Bathrooms maximum', THEME_NAME)
      ),
      array(
        "type"        => 'textfield',
        'param_name'  => 'sq_feet',
        'heading'     => __('Sq. feet', THEME_NAME),
        'description' => __('Will display properties will area equal or larger than this value', THEME_NAME)
      ),
      array(
        "type"        => 'checkbox',
        'param_name'  => 'pets_allowed',
        'heading'     => __('Pets allowed', THEME_NAME),
        'value'       => array( __('Yes') => 'yes'),
        'description' => __('Will show properties that have either pets allowed or not.', THEME_NAME)
      ),
      array(
        "type"        => 'checkbox',
        'param_name'  => 'type',
        'heading'     => __('Type', THEME_NAME),
        'value'       => $types_array
      ),
      array(
        "type"        => 'checkbox',
        'param_name'  => 'feature',
        'heading'     => __('Feature', THEME_NAME),
        'value'       => $features_array
      ),
      array(
        "type"        => 'dropdown',
        'param_name'  => 'city',
        'heading'     => __('City', THEME_NAME),
        'value'       => $cities
      ),
      array(
        "type"        => 'dropdown',
        'param_name'  => 'for_sale_rent',
        'heading'     => __('For sale or rent', THEME_NAME),
        'value'       => array('-' => '', __('Sale', THEME_NAME) => 'sale', __('Rent', THEME_NAME) => 'rent'),
        'description' => __( 'If you don\'t choose an option both will be shown', THEME_NAME)
      ),
      array(
        "type"        => 'dropdown',
        'param_name'  => 'property_status',
        'heading'     => __('Property status', THEME_NAME),
        'value'       => array('' => '', __('Sold', THEME_NAME) => 'sold', __('Rented', THEME_NAME) => 'rented', __('Let agreed', THEME_NAME) => 'let_agreed', __('Sale agreed', THEME_NAME) => 'sale_agreed')
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'lat',
        'heading' => __('Latitude', THEME_NAME)
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'lng',
        'heading' => __('Longitude', THEME_NAME)
      ),
      array(
        "type" => 'textfield',
        'param_name' => 'distance',
        'heading' => __('Distance', THEME_NAME)
      ),
      array(
        "type"        => 'checkbox',
        'param_name'  => 'auto_slide',
        'heading'     => __('Auto slide', THEME_NAME),
        'value'       => array( __('Yes') => 'yes'),
        'description' => __('Automatically slide properties in the carousel. It applies only for carousel style.', THEME_NAME)
      ),
      array(
        "type"        => 'checkbox',
        'param_name'  => 'infinite_loop',
        'heading'     => __('Infinite slide', THEME_NAME),
        'value'       => array( __('Yes') => 'yes'),
        'description' => __('Infinitely slide properties in the carousel. It applies only for carousel style.', THEME_NAME)
      )
    )
  ));
}
}

?>