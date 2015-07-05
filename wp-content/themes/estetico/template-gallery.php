<?php 
/**
Template name: Gallery
**/
?>
<?php

$meta 		= get_post_meta($post->ID);
$content 	= $post->post_content;

$galleries_per_row = isset($meta[USE_PREFIX . 'galleries_per_row']) ? (int)$meta[USE_PREFIX . 'galleries_per_row'][0] : 2;
$galleries_to_display = isset($meta[USE_PREFIX . 'galleries_to_display']) ? unserialize( $meta[USE_PREFIX . 'galleries_to_display'][0] ) : array();;

function get_galleries($ids = array()) {

	$galleries = wp_cache_get('galleries', THEME_NAME);

	if( ! $galleries ) {

		$options = array(
			'post_type' 		=> 'property_gallery',
			'post_status' 		=> 'publish',
			'post__in' 			=> $ids,
			'posts_per_page' 	=> PHP_INT_MAX
		);

		$galleries = get_posts($options);

		foreach($galleries as &$gallery) {

			$_content = $gallery->post_content;
			$gallery_images_ids = array();
			$featured_image = null;
			preg_match('/\[gallery ids="(.*?)"\]/', $_content, $matches);

			$gallery->images = array();
			$gallery->featured_image = null;

			if(!empty($matches[1])) {

				$gallery_images_ids = explode(',', $matches[1]);
				$featured_image = wp_get_attachment_image_src($gallery_images_ids[0], 'properties-gallery-normal');

				$_gallery = array();
				foreach($gallery_images_ids as $id) {
					$image = wp_get_attachment_image_src($id, 'properties-gallery-normal');
					$_gallery[] = $image;
				}

				$gallery->images = $_gallery;
				$gallery->featured_image = $featured_image;
			} 
		}

		// Save galleries if there is a permanent cache installed 
		wp_cache_set('galleries', $galleries, THEME_NAME);
	}

	return $galleries;
}

$galleries = get_galleries($galleries_to_display);

?>

<?php 

get_header();

?>

<div class="entry-content">

<?php

ob_start();

?>

<div class="grid grid-fluid-<?php echo $galleries_per_row ?>">

	<?php $galleries_count = count($galleries); $counter = 0; foreach ($galleries as $gallery) : ?>

		<?php if(!$gallery->featured_image) continue; ?>

		<?php if($counter % $galleries_per_row == 0) : ?>
		<div class="row">
		<?php endif ?>
	
		<div class="col alpha">
			<div class="offer">
				<a class="hover-image" href="#" title="<?php echo $gallery->post_title ?>">
					<span class="roll zoom" style="opacity: 0;"></span>
					<img src="<?php echo $gallery->featured_image[0] ?>" class="imgborder" alt="<?php echo $gallery->post_title ?>">
				</a>
				<span class="box">
					<?php echo $gallery->post_title ?>
				</span>
			</div>

			<?php 
			foreach($gallery->images as $image ) {
				echo '<a href="' . $image[0] . '" rel="prettyPhoto[pp_' . $gallery->ID . ']"></a>';
			}
			?>
		</div>

		<?php $counter++ ?>

		<?php if($counter % $galleries_per_row == 0 || $galleries_count == $counter) : ?>
		</div>
		<?php endif ?>

	<?php endforeach ?>

</div>

<script type="text/javascript" charset="utf-8">
(function($) {
  $(document).ready(function(){
    $("a[rel^='prettyPhoto']").prettyPhoto();
    $('.hover-image').on('click', function() {
    	$(this).parents('.col').find("a[rel^='prettyPhoto']").eq(0).trigger('click');
    	return false;
    });
  });
})(jQuery);
</script>

</div>

<?php 
$gallery_content = ob_get_contents();
ob_end_clean();

// Is there a shortcode to place the galleries
if(strpos($content, '[galleries]') !== false) {
	$content = str_replace('[galleries]', $gallery_content, $content);
} else {
	$content = $content . $gallery_content;
}

echo $content;
?>

<?php get_footer() ?>