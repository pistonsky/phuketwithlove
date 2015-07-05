<?php

$options = array(
	'post_type' 		=> 'testimonials',
	'status'			=> 'publish',
	'posts_per_page' 	=> PHP_INT_MAX,
	'suppress_filters'	=> false
);
$testimonials = get_posts( $options );

?>

<div class="testimonials-internal">
	<ul>
		<?php foreach( $testimonials as $testimonial ) : ?>
		<li class="box general">
			<blockquote>
				<?php echo $testimonial->post_content ?>
				<cite><?php echo $testimonial->post_title ?></cite>
			</blockquote>
		</li>
	<?php endforeach ?>
	</ul>
</div>