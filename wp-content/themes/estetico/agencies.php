<?php

/* 
 * Template name: Agencies
 */

$main_properties_page 	= estetico_get_properties_page_id();
$properties_page_url 	= estetico_get_properties_page_url_wpml($main_properties_page);
$main_agents_page		= estetico_get_agents_page_id();
$agents_page_url 		= estetico_get_agents_page_url_wpml($main_agents_page);

get_header(); ?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<div class="main-content">

<?php if ( have_posts() ) : ?>
	<div class="custom-template-page-content">
	<?php /* The loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php 
		the_content();
		?>
	<?php endwhile; ?>
	</div>
<?php endif; ?>

<?php

$agenciesMgr = new AgenciesManager();
$agencies = $agenciesMgr->getAll();

if( ! empty( $agencies) ) : ?>

<div class="component-cards">
	<ul class="items list">
		<?php foreach($agencies as $agency) : ?>

		<?php
		$address = $agency->getAddress();
		$latlng = estetico_get_latlng_by_address($address);

		?>

		<li class="card">
			<div class="data">
				<h6><?php echo $agency->getName() ?></h6>
				<div class="contact-info">
					<?php if( $agency->hasPhone() ) : ?>
					<strong><?php echo __( 'phone', THEME_NAME ) ?>:</strong>
					<?php echo $agency->getPhone() ?>
					<?php endif ?>
					
					<?php if( $agency->hasPhone() && $agency->hasEmail() ) : ?>
					<span class="sep">|</span>
					<?php endif ?>

					<?php if( $agency->hasEmail() ) : ?>
					<strong>e-mail:</strong>
					<a href="mailto:<?php echo $agency->getEmail() ?>"><?php echo $agency->getEmail() ?></a>
					<?php endif ?>

					<?php if( $agency->hasWebsite() && $agency->hasEmail() ) : ?>
					<span class="sep">|</span>
					<?php endif ?>

					<?php if( $agency->hasWebsite() ) : ?>
					<strong>website:</strong>
					<a href="<?php echo $agency->getWebsite() ?>"><?php echo $agency->getWebsite() ?></a>
					<?php endif ?>

				</div>
				<div class="descr">
					<?php echo $agency->getDescription() ?>
				</div>
				<?php if($latlng !== false) : ?>
				<div id="agency-map-id-<?php echo $agency->getId() ?>" class="agency-map-placeholder">

				</div>

				<script>
				try {
				(function() {
			        var mapOptions = {
			          center: { lat: <?php echo $latlng->lat ?>, lng: <?php echo $latlng->lng ?>},
			          zoom: 15
			        };
			        var myLatlng = new google.maps.LatLng(<?php echo $latlng->lat ?>,<?php echo $latlng->lng ?>);
			        var map = new google.maps.Map(document.getElementById('agency-map-id-<?php echo $agency->getId() ?>'),
			            mapOptions);

			        var marker = new google.maps.Marker({
					    position: myLatlng,
					    map: map
					});
			     })();
			  } catch(e) {
			  	console.log(e.message);
			  }
				</script>
				<?php endif ?>
				<div class="">
					<a href="<?php echo add_query_arg( 'by_agency', $agency->getId(), $agents_page_url) ?>"><?php _e('View all agents', THEME_NAME) ?></a>
				</div>
			</div>
		</li>
		<?php endforeach ?>
	</ul>
</div>

<?php else : ?>
	<p><?php echo __('There are no agencies.', THEME_NAME) ?></p>
<?php endif ?>

</div>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>