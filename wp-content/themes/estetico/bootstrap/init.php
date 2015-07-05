<?php

define( 'THEME_NAME', 'estetico' );
define( 'USE_PREFIX', THEME_NAME . '_');

load_theme_textdomain( THEME_NAME, get_template_directory() . '/languages' );

define( 'BOOTSTRAP_PATH', dirname( __FILE__ ) );
define( 'COMPONENTS_PATH', realpath( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR ));

require_once "inc/classes/property.class.php";
require_once "inc/classes/propertiesmanager.class.php";

require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . "smof" . DIRECTORY_SEPARATOR . "index.php";
require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "helpers.php";
require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . 'custom-post-types.php';
require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . 'custom-widgets.php';
require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . 'custom-shortcodes.php';
require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . 'custom-metaboxes.php';
require_once BOOTSTRAP_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'class-tgm-plugin-activation.php';

require_once "inc/classes/page.class.php";
require_once "inc/classes/agent.class.php";
require_once "inc/classes/agentsmanager.class.php";
require_once "inc/classes/agency.class.php";
require_once "inc/classes/agenciesmanager.class.php";
require_once "inc/classes/formcontrol.class.php";

add_action( 'manage_properties_posts_custom_column', 'estetico_manage_properties_posts_custom_column', 10, 2 );
add_filter( 'manage_properties_posts_columns', 'estetico_manage_properties_columns' );

add_action( 'init', 'init_theme' );
add_action( 'admin_head', 'estetico_custom_admin_css' );

// Load some additional javascript in administration only when Theme options page is active
if( (isset($_GET['page']) && $_GET['page'] == 'optionsframework') || $_SERVER['SCRIPT_NAME'] == '/wp-admin/post.php' || (isset($_GET['post_type']) && $_GET['post_type'] == 'properties')) {
	add_action( 'admin_footer', 'estetico_custom_admin_js' );
}

add_action( 'admin_menu', 'estetico_action_admin_menu' );
add_action( 'save_post', 'estetico_action_save_post' );

add_action( 'tgmpa_register', 'estetico_register_required_plugins' );
add_action( 'admin_notices', 'estetico_notices' );
add_filter( 'the_content', 'estetico_filter_the_content_properties');

add_filter('icl_ls_languages', 'wpml_ls_filter');

?>