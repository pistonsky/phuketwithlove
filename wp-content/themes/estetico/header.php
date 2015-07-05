<?php
global $template_name;

$header_configuration = estetico_get_setting('header_configuration');
$is_homepage = estetico_is_homepage();
$show_slider = estetico_show_slider();

if( ! $show_slider ) {
	$dir = wp_upload_dir();
	$inner_pages_header = estetico_get_setting('inner_pages_header');
	$header = wp_get_image_editor($inner_pages_header);
	if( ! is_wp_error($header)) {
		$header->crop(0, 0, 940, 220);
		$header->save($dir['path'] . DIRECTORY_SEPARATOR . 'inner_pages_header_cropped.jpg');
		$inner_pages_header = $dir['url'] . '/inner_pages_header_cropped.jpg';
	}
}

$show_quick_search = estetico_get_setting('show_quick_search') == 'yes' ? true : false;

$main_properties_page 	= estetico_get_properties_page_id();
$properties_page_url 	= estetico_get_properties_page_url_wpml($main_properties_page);

$sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile');

?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<script>
	// Set time a global site url
	var _site_url = '<?php echo site_url() ?>';
	var _template_url = '<?php echo bloginfo( 'template_url' ) ?>';
	var _properties_url = '<?php echo $properties_page_url ?>';

	var locale = [];
	locale['View details'] = "<?php _e('View details', THEME_NAME) ?>";
	</script>
	
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=584940574930121";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="properties-map-fullscreen">
	<div class="controls"><a href="#" class="close"></a></div>
	<div class="put-it-here"></div>
</div>

<header id="header" class="<?php if(!function_exists('putRevSlider')):?>no-rev-slider<?php endif ?>">
	<div class="background"></div>
	<div class="container">

		<div class="float-left">
			<a href="<?php echo site_url() ?>" class="logo">
				<?php
				$logo = estetico_get_setting('logo');
				?>
				<?php if(!empty($logo)) : ?>
					<img src="<?php echo $logo ?>" alt="">
				<?php else : ?>
					<h1><?php echo get_bloginfo('name') ?></h1>
				<?php endif ?>
			</a>

			<h2><?php echo get_bloginfo('description') ?></h2>
		</div>
		
		<nav>
			<div class="desktop">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</div>
			<div class="mobile">
				<div class="styled-select"></div>
			</div>
		</nav>
		
		<div class="clear"></div>
		
		<?php if( $show_slider || ! empty( $inner_pages_header ) ) : ?>
		<div class="slider">
			<?php if( $show_slider ) : ?>
				<?php if(function_exists('putRevSlider')) : ?>
					<?php putRevSlider( estetico_get_setting('revslider_id') ) ?>
					<div class="controls">
						<div class="arrow prev"></div>
						<div class="arrow next"></div>
					</div>
				<?php endif ?>

			<?php else: ?>
			<div class="slides">
				<img src="<?php echo $inner_pages_header ?>" alt="">
			</div>
			<?php endif ?>
		</div>
		<?php endif ?>

		<?php if( $show_slider && $show_quick_search ) : ?>
			<?php estetico_load_component('quick_search') ?>
		<?php endif ?>

	</div>
	<!-- /.container -->

</header>
<!-- /#header -->

<section id="main" data-role="main">

	<div class="container sidebar-position-<?php echo $sidebar_position_mobile ?>">

    <?php if ( function_exists( 'breadcrumb_trail' ) && !is_front_page() && estetico_get_setting('show_breadcrumbs') == 'yes' && $template_name != 'home') breadcrumb_trail(array('show_browse' => false, 'separator' => '&raquo;')); ?>
