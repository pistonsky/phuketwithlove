<?php

global $agent_mail_sent_success;
$agent_mail_sent_success = null;

// No session started yet
if(!isset($_SESSION)) {

	session_start();
	$session_id = session_id();

	Registry::set('session_id', $session_id);
}

// Handle Agent contact form
if( isset( $_POST['form_action'])) {

	// Very simple anti-spam bot check. City is not a visible field.
	if( ! empty($_POST['city']) ) {
		die('Spam check failed.');
	}

	$agent = new Agent($_POST['agent_id']);

	$agent_mail_sent_success = $agent->sendMessage($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['message']);
}

global $sidebar_name;
$sidebar_name = 'property_details';

?>

<?php get_header(); ?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

	<div class="main-content">

		<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content-properties', get_post_format() ); ?>

		<?php endwhile; ?>

	</div>

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer(); ?>