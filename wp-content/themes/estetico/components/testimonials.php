<?php

$options = array(
	'post_type' 		=> 'testimonials',
	'status'			=> 'publish',
	'posts_per_page' 	=> PHP_INT_MAX,
	'suppress_filters'	=> false
);
$testimonials = get_posts( $options );

?>

<?php if( ! empty( $testimonials ) ) : ?>

<div class="testimonials m-t-40">
	<ul>
		<?php foreach( $testimonials as $testimonial ) : ?>
		<li>
			<div class="testimonial"><?php echo $testimonial->post_content ?></div>
			<span class="author"><?php echo esc_html( $testimonial->post_title ) ?></span>
		</li>
		<?php endforeach ?>
	</ul>
	<div class="controls">
		<div class="arrow prev"></div>
		<div class="arrow next"></div>
	</div>
</div>

<?php endif ?>