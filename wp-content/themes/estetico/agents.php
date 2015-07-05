<?php

/* 
 * Template name: Agents
 */

$main_properties_page 	= estetico_get_properties_page_id();
$properties_page_url 	= estetico_get_properties_page_url_wpml($main_properties_page);

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

$agentsMgr = new AgentsManager();
if(isset($_GET['by_agency'])) {
	$agents = $agentsMgr->getAllByAgency($_GET['by_agency']);
} else {
	$agents = $agentsMgr->getAll();
}

if( ! empty( $agents) ) : ?>

<div class="component-cards">
	<ul class="items list">
		<?php foreach( $agents as $agent ) : ?>
		<li class="card">
			<div class="photo">
				<img src="<?php echo $agent->getImage('post-thumbnail') ?>" alt="">
			</div>
			<div class="data">
				<h6><?php echo $agent->getName() ?></h6>
				<div class="contact-info">
					<?php if( $agent->hasPhone() ) : ?>
					<strong><?php echo __( 'phone', THEME_NAME ) ?>:</strong>
					<?php echo $agent->getPhone() ?>
					<?php endif ?>
					
					<?php if( $agent->hasPhone() && $agent->hasEmail() ) : ?>
					<span class="sep">|</span>
					<?php endif ?>

					<?php if( $agent->hasEmail() ) : ?>
					<strong>e-mail:</strong>
					<a href="mailto:<?php echo $agent->getEmail() ?>"><?php echo $agent->getEmail() ?></a>
					<?php endif ?>

					<?php if( $agent->hasWebsite() && $agent->hasEmail() ) : ?>
					<span class="sep">|</span>
					<?php endif ?>

					<?php if( $agent->hasWebsite() ) : ?>
					<strong>website:</strong>
					<a href="<?php echo $agent->getWebsite() ?>"><?php echo $agent->getWebsite() ?></a>
					<?php endif ?>

				</div>
				<div class="contact-media">
					<?php if( $agent->hasFacebook() ) : ?>
					<a href="<?php echo estetico_social_media_link( $agent->getFacebook(), 'facebook' ) ?>" class="media facebook"></a>
					<?php endif ?>

					<?php if( $agent->hasGooglePlus() ) : ?>
					<a href="<?php echo estetico_social_media_link( $agent->getGooglePlus(), 'google_plus' ) ?>" class="media gplus"></a>
					<?php endif ?>

					<?php if( $agent->hasTwitter() ) : ?>
					<a href="<?php echo estetico_social_media_link( $agent->getTwitter(), 'twitter' ) ?>" class="media twitter" rel="external"></a>
					<?php endif ?>
				</div>
				<div class="descr">
					<?php echo $agent->getDescription() ?>
				</div>
				<?php $languages = $agent->getLanguages(); if( ! empty( $languages ) ) : ?>
					<div class="languages"><strong><?php echo __( 'Languages', THEME_NAME ) ?>:</strong> <?php echo $languages ?>
				<?php endif ?>
				<div class="">
					<a href="<?php echo add_query_arg( 'agent', $agent->getId(), $properties_page_url) ?>"><?php _e('View properties listed by this agent', THEME_NAME) ?></a>
				</div>
			</div>
		</li>
	<?php endforeach ?>
	</ul>
</div>

<?php else : ?>
	<p><?php echo __('There are no agents.', THEME_NAME) ?></p>
<?php endif ?>

</div>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>