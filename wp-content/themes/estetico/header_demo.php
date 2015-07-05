<?php
global $template_name;
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
	</script>
	
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<!-- DEMO Helper -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/core/css/theme-styles-customize.css">
<?php
$estetico_theme_customize_state = '';
$estetico_colour_skin = empty( $_COOKIE['estetico_colour_skin'] ) ? 'yellow' : $_COOKIE['estetico_colour_skin'];
if(isset($_COOKIE['estetico_theme_customize_state']) && $_COOKIE['estetico_theme_customize_state'] == 'close') {
        $estetico_theme_customize_state = 'close';
}
?>
<div id="theme-styles-customize" class="<?php echo $estetico_theme_customize_state ?>">
        <h4>Styles customization</h4>
        <div class="block">
                <h5><?php  echo __('Colour skin') ?></h5>
                <div class="input">
                        <select name="style" class="colour-skin">
                                <option value="yellow"<?php echo $estetico_colour_skin == 'yellow' ? ' selected="selected"' : '' ?>>Yellow</option>
                                <option value="green"<?php echo $estetico_colour_skin == 'green' ? ' selected="selected"' : '' ?>>Green</option>
                                <option value="orange"<?php echo $estetico_colour_skin == 'orange' ? ' selected="selected"' : '' ?>>Orange</option>
                                <option value="ocean"<?php echo $estetico_colour_skin == 'ocean' ? ' selected="selected"' : '' ?>>Ocean</option>
                                <option value="forest"<?php echo $estetico_colour_skin == 'forest' ? ' selected="selected"' : '' ?>>Forest</option>
                        </select>
                </div>
        </div>
        <a href="#" class="collapse-handle"></a>
</div>
<script>
(function($) {
	$(function() {
		$('#theme-styles-customize .collapse-handle').click(function(e) {

		        $('#theme-styles-customize').animate({
		                'margin-left' : $('#theme-styles-customize').hasClass('close')?0:-200
		        }, function() {
		                $('#theme-styles-customize').toggleClass('close');
		                Cookies.set('estetico_theme_customize_state', $('#theme-styles-customize').hasClass('close')?'close':'open')
		        });

		        e.preventDefault();        
		        return false;
		});

		$('#theme-styles-customize .colour-skin').change(function() {
		        $('head').append('<link rel="stylesheet" href="' + _site_url + '/wp-content/themes/estetico/assets/skins/' + $(this).val() + '/css/skin.css">');

		        Cookies.set('estetico_colour_skin', $(this).val());
		});
	});
})(jQuery);
</script>
<!-- DEMO Helper ends-->

<header id="header">

	<div class="container">

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

		<nav>
			<div class="desktop">
				<?php add_filter('wp_nav_menu_objects', 'estetico_walker_nav_menu_start_el_helper', 10, 2) ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</div>
			<div class="mobile">
				<div class="styled-select"></div>
			</div>
		</nav>

		<div class="slider">
			<?php if( is_front_page() || (isset($template_name) && $template_name == 'home') ) : ?>
				<?php if(function_exists('putRevSlider')) : ?>
					<?php putRevSlider( "homeslider" ) ?>
					<div class="controls">
						<div class="arrow prev"></div>
						<div class="arrow next"></div>
					</div>
				<?php endif ?>

			<?php else: ?>
			<div class="slides">
				<?php
				$dir = wp_upload_dir();
				$inner_pages_header = estetico_get_setting('inner_pages_header');
				$header = wp_get_image_editor($inner_pages_header);
				if(!is_wp_error($header)) {
					$header->crop(0, 0, 940, 220);
					$header->save($dir['path'] . DIRECTORY_SEPARATOR . 'inner_pages_header_cropped.jpg');
					$inner_pages_header = $dir['url'] . '/inner_pages_header_cropped.jpg';
				}
				?>
				<img src="<?php echo $inner_pages_header ?>" alt="">
			</div>
			<?php endif ?>
		</div>

		<?php if( is_front_page() || (isset($template_name) && $template_name == 'home') ) : ?>
			<?php estetico_load_component('quick_search') ?>
		<?php endif ?>

	</div>
	<!-- /.container -->

</header>
<!-- /#header -->

<section id="main" data-role="main">

	<div class="container">

    <?php if ( function_exists( 'breadcrumb_trail' ) && !is_front_page() && estetico_get_setting('show_breadcrumbs') && $template_name != 'home') breadcrumb_trail(array('show_browse' => false, 'separator' => '&raquo;')); ?>
