<?php

global $agent_mail_sent_success;

$schemaorg_enabled = estetico_get_setting( 'schemaorg_enabled' );

$property = new Property();
$property->setData($post);
$meta = $property->getMeta();

$property->increaseViewCount();

ob_start();
the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_NAME ) );
$content = ob_get_contents();
ob_end_clean();

$gallery_content = "";
$gallery_content_regex = '/<extract>(.*?)<\/extract>/s';
preg_match($gallery_content_regex, $content, $matches);

if(isset($matches[1])) {
	$gallery_content = $matches[1];
	$content = preg_replace($gallery_content_regex, '', $content, 1);
}

$show_map = false;

$lat = $property->getLatitude();
$lng = $property->getLongitude();
if( ! empty($lat) && ! empty($lng) ) {
	$show_map = true;
}

$properties_page_url = estetico_get_properties_page_url();

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
	
	<?php if ( is_single() ) : ?>

		<?php if( $schemaorg_enabled ) : ?>
			<?php echo $property->getSchemaOrgMetaTags() ?>
		<?php endif ?>

		<?php if($agent_mail_sent_success !== null): ?>
			<?php if($agent_mail_sent_success):?>
				<div class="message success box"><?php echo __('Message to our agent was sent successfuly.', THEME_NAME) ?></div>
			<?php else: ?>
				<div class="message error box"><?php echo __('There was an error while trying to connect with our agent. Please try again later.', THEME_NAME ) ?></div>
			<?php endif ?>
		<?php endif ?>

		<h2 class="page-title"><?php echo $property->getTitle(); ?></h2>
		<?php else : ?>
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo $property->getTitle(); ?></a>
		</h1>
		<?php endif; // is_single() ?>

		<div class="property-details">
            <?php $property_status = $property->getPropertyStatus();
                  if($property_status != '') : ?>
                <div class="offer">
                    <div class="ribbon ribbon-<?php echo $property_status ?>"></div>
                </div>
            <?php endif ?>
			<div class="prices-wrapper">
				<?php $for_sale_rent = $property->getForSaleRent(); if($for_sale_rent == 'sale' || $for_sale_rent == 'both') : ?>
				<div class="price"><?php echo __('Price for sale', THEME_NAME) ?>: <?php echo $property->getPrice(true) ?></div>
				<?php endif ?>
				<?php if( ($for_sale_rent == 'rent' || $for_sale_rent == 'both') && $property->getRentMonthPrice(false) > 0) : ?>
				<div class="price <?php echo $for_sale_rent ?> month"><?php echo __('Price for rent', THEME_NAME) ?>: <?php echo $property->getRentMonthPrice(true) ?></div>
				<?php endif ?>
				<?php if( ($for_sale_rent == 'rent' || $for_sale_rent == 'both') && $property->getRentWeekPrice(false) > 0) : ?>
				<div class="price <?php echo $for_sale_rent ?> week"><?php echo __('Price for rent', THEME_NAME) ?>: <?php echo $property->getRentWeekPrice(true) ?></div>
				<?php endif ?>
			</div>

			<?php echo $gallery_content ?>
			<script>
			var property_details_gallery_delay = 0;
			try {
				property_details_gallery_delay = <?php echo abs( (int)estetico_get_setting('property_details_gallery_delay') ); ?>
			} catch(e) {}
			</script>
		</div>

		<div class="social-buttons clearfix">

				<div class="button">
					 <a href="http://www.pinterest.com/pin/create/button/
				        ?url=<?php echo $property->getLink() ?>
				        &media=<?php echo $property->getImage(array(147, 147)) ?>
				        &description="
				        data-pin-do="buttonPin"
				        data-pin-config="above">
				        <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" />
				    </a>

				    <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
				    <script type="text/javascript">
				(function(d){
				    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
				    p.type = 'text/javascript';
				    p.async = true;
				    p.src = '//assets.pinterest.com/js/pinit.js';
				    f.parentNode.insertBefore(p, f);
				}(document));
				</script>

				</div>

				<div class="button">
					<div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
				</div>

				<div class="button twitter">
					<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>

				<div class="button">
					<!-- Place this tag where you want the +1 button to render. -->
					<div class="g-plusone"></div>

					<!-- Place this tag after the last +1 button tag. -->
					<script type="text/javascript">
					  (function() {
					    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					    po.src = 'https://apis.google.com/js/platform.js';
					    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>
				</div>
			</div>
	</header><!-- .entry-header -->

	<div class="train-railing separator">
		<div class="railing"></div>
		<h3 class="train"><?php echo __('Description', THEME_NAME) ?></h3>
		<div class="railing"></div>
	</div>

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php echo $content ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', THEME_NAME ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( comments_open() && ! is_single() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', THEME_NAME ) . '</span>', __( 'One comment so far', THEME_NAME ), __( 'View all % comments', THEME_NAME ) ); ?>
			</div><!-- .comments-link -->
		<?php endif; // comments_open() ?>

	</footer><!-- .entry-meta -->
</article><!-- #post -->

<ul class="prop-extended-info">
	<?php if($property->getBedrooms() ):?>
	<li>
		<span class="label"><?php echo __( 'Bedrooms', THEME_NAME ) ?>:</span>
		<span class="value"><a href="<?php echo $properties_page_url ?>?bedrooms=<?php echo $property->getBedrooms() ?>"><?php echo $property->getBedrooms() ?></a></span>
	</li>
	<?php endif ?>
	<?php if($property->getBathrooms() ) : ?>
	<li>
		<span class="label"><?php echo __( 'Bathrooms', THEME_NAME ) ?>:</span>
		<span class="value"><a href="<?php echo $properties_page_url ?>?bathrooms=<?php echo $property->getBathrooms() ?>"><?php echo $property->getBathrooms() ?></a></span>
	</li>
	<?php endif ?>
	<?php if($property->getBeds() ) : ?>
	<li>
		<span class="label"><?php echo __( 'Beds', THEME_NAME ) ?>:</span>
		<span class="value"><a href="<?php echo $properties_page_url ?>?beds=<?php echo $property->getBeds() ?>"><?php echo $property->getBeds() ?></a></span>
	</li>
	<?php endif ?>
	<?php if($property->getPetsAllowed()) : ?>
	<li>
		<span class="label"><?php echo __( 'Pets allowed', THEME_NAME ) ?>:</span>
		<span class="value"><a href="<?php echo $properties_page_url ?>?pets_allowed=yes"><?php echo __('Yes', THEME_NAME) ?></a>
		<?php
		$pet_types = $property->getPetTypes();
		if($pet_types != null) : ?>
		 (<?php echo $pet_types ?>)
		<?php endif ?></span>
	</li>
	<?php endif ?>
	<?php $features = $property->getFeatures(); foreach( $features as $feature ) : ?>
	<li>
		<span class="label"><?php echo $feature->name ?>:</span>
		<span class="value"><a href="<?php echo $properties_page_url ?>?feature=<?php echo $feature->slug ?>" title="<?php echo esc_attr( sprintf( __( 'Show more properties with: %s', THEME_NAME), $feature->name) ) ?>"><?php echo __('Yes', THEME_NAME) ?></a></span>
	</li>
	<?php endforeach ?>
</ul>

	<?php if($show_map) : ?>
	<div class="train-railing separator">
		<div class="railing"></div>
		<h3 class="train"><?php _e('Location', THEME_NAME) ?></h3>
		<div class="railing"></div>
	</div>

	<div id="property-maps">
		<ul>
			<li><a href="#map"><?php _e('Google Map', THEME_NAME) ?></a></li>
			<?php if($property->getStreetViewPov() != 'ERROR') : ?>
				<li><a href="#street-view"><?php _e('Google Street View', THEME_NAME) ?></a></li>
			<?php endif ?>
		</ul>

		<div class="box" id="map">
			
			<?php estetico_load_component('common/properties_view_map_pack', array('properties_map_index' => 1, 'options' => array('show_legend' => false))) ?>

		</div>
		<?php if($property->getStreetViewPov() != 'ERROR') : ?>
		<div class="box" id="street-view">
			<div class="mobile-gutter">
				<div id="properties-street-view" data-pov="<?php echo htmlentities( $property->getStreetViewPov() ) ?>"></div>
			</div>
		</div>
		<?php endif ?>
	</div>

	<?php
	if(estetico_get_setting('show_other_people_also_viewed') == 'yes') : ?>
	<div class="property-viewed property-other-people-also-viewed">
		<h4><?php _e('Other people also viewed', THEME_NAME) ?></h4>
	<?php

	echo PropertiesManager::getOtherPeopleAlsoViewedHTML($post);

	?>
	</div>
	<?php endif ?>
	
	<?php
	if(estetico_get_setting('show_recently_viewed') == 'yes') :
	$options = array('exclude' => $property->getId());
	if(PropertiesManager::hasRecentlyViewed($options)) : ?>
	<div class="property-viewed property-recently-viewed">
		<h4><?php _e('Recently viewed', THEME_NAME) ?></h4>
		<?php
		echo PropertiesManager::getRecentlyViewedHTML($options);
		?>
	</div>
	<?php endif ?>
<?php endif ?>

<script>
	try {
		var propertiesMap = null;
		var propertiesList = [
			{
				lat : '<?php echo $property->getLatitude() ?>',
				lng : '<?php echo $property->getLongitude() ?>',
				address : '<?php echo esc_html( $property->getAddress() ) ?>',
				url : '<?php echo $property->getLink() ?>',
				photo : '<?php echo $property->getImage(array(100, 100)) ?>',
				rent : <?php echo $property->getForSaleRent() == 'rent' ? 'true' : 'false' ?>,
				title : '<?php echo esc_html( $property->getTitle() ) ?>'
			}
		];

		google.maps.event.addDomListener(window, 'load', function() {

			propertiesMap = new PropertiesMap('properties-map-1', {
				zoom : 10,
				fitBounds : false
			});
			propertiesMap.add(propertiesList);
			propertiesMap.localInitialize();
		});

	} catch (e) {
		console.log(e);
	}

	jQuery(function() {
		var rv = new Estetico.RecentlyViewed();
		rv.add(<?php echo $property->getId() ?>);
	});
</script>
<?php endif ?>

<div class="entry-meta">
	<?php edit_post_link( __( 'Edit', THEME_NAME ), '<span class="edit-link">', '</span>' ); ?>
</div><!-- .entry-meta -->