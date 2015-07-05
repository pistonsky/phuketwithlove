<?php

/**
 * Template name: Contacts
 */

$recaptcha_enabled 		= estetico_get_setting( 'recaptcha_enabled' ) == 'yes' ? true : false;
$recaptcha_public_key	= estetico_get_setting( 'recaptcha_public_key' );
$recaptcha_private_key 	= estetico_get_setting( 'recaptcha_private_key' );
$recaptcha_theme		= estetico_get_setting( 'recaptcha_theme' );

if($recaptcha_enabled) {
	require_once BOOTSTRAP_PATH . "/inc/vendor/recaptchalib.php";
}

$success = null;

if( isset($_POST['submit'])) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$message = $_POST['message'];

	$error = false;
	$success = false;

	if(empty($first_name) || empty($last_name) || empty($phone) || empty($email) || empty($message)) {
		$error = true;
	}

	if($recaptcha_enabled) {
		$resp = recaptcha_check_answer($recaptcha_private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
		if($resp->is_valid == false) {
			$error = true;
		}
	}

	if( ! $error ) {

		$ip = estetico_get_client_ip();

		$to = estetico_get_setting('contacts_recipient');

		if( empty( $to ) ) {
			$to = get_option('admin_email');
		}

		$subject = estetico_get_setting('contacts_subject');
		$content = estetico_get_setting('contacts_message_format');

		$content = str_replace('%first_name%', $first_name, $content);
		$content = str_replace('%last_name%', $last_name, $content);
		$content = str_replace('%email%', $email, $content);
		$content = str_replace('%phone%', $phone, $content);
		$content = str_replace('%message%', $message, $content);
		$content = str_replace('%ip%', $ip, $content);

		$content = str_replace(array("\n", "\r\n"), '<br>', $content);

		$headers = "Content-Type: text/html; charset=utf-8\r\n";
		$headers .= "From: <" . $email . ">\r\n";
		$headers .= "Reply-To: <" . $email . ">";

		if( mail( $to, $subject, $content, $headers ) ) {

			$success = true;
		}

	}
}

?>

<?php get_header() ?>

<?php $sidebar_position_mobile = estetico_get_setting('sidebar_position_mobile'); ?>

<?php if($sidebar_position_mobile == 'before') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<div class="main-content">

	<?php while ( have_posts() ) : the_post(); ?>

		<h2 class="page-title"><?php the_title() ?></h2>

		<?php if( $success ) : ?>
		<div class="success box"><?php $contacts_success = estetico_get_setting( 'contacts_success' ); echo !empty($contacts_success) ? $contacts_success : __( 'Your message was sent successfully.', THEME_NAME)  ?></div>
		<?php elseif($success === false): ?>
		<div class="error box"><?php echo __( 'Something went wrong. Please try again.', THEME_NAME ) ?></div>
		<?php endif ?>

		<?php the_content() ?>

		<form method="post" action="">

		<div class="grid grid-fluid-2">

			<div class="row">
		
				<div class="col field alpha">
					<input name="first_name" type="text" placeholder="<?php echo __('First name', THEME_NAME) ?>" value="<?php echo !empty($first_name) ? $first_name : '' ?>">
				</div>
				
				<div class="col field omega">
					<input name="last_name" type="text" placeholder="<?php echo __('Last name', THEME_NAME) ?>" value="<?php echo !empty($last_name) ? $last_name : '' ?>">
				</div>

			</div>

		</div>

		<div class="grid grid-fluid-2">

			<div class="row">

				<div class="col field alpha">
					<input name="phone" type="text" placeholder="<?php echo __('Phone', THEME_NAME) ?>" value="<?php echo !empty($phone) ? $phone : '' ?>">
				</div>

				<div class="col field omega">
					<input name="email" type="text" placeholder="<?php echo __('E-mail', THEME_NAME) ?>" value="<?php echo !empty($email) ? $email : '' ?>">
				</div>

			</div>

		</div>

		<div class="field">
			<textarea name="message" placeholder="<?php echo __('Message', THEME_NAME) ?>..."><?php echo !empty($message) ? $message : '' ?></textarea>
		</div>

		<?php if($recaptcha_enabled) : ?>
			<div class="recaptcha-contacts-form">
				<script type="text/javascript">
				 var RecaptchaOptions = {
				    theme : '<?php echo $recaptcha_theme ?>'
				 };
				</script>
				<?php echo recaptcha_get_html($recaptcha_public_key) ?>
			</div>
		<?php endif ?>

		<div class="center">
			<input type="submit" name="submit" value="<?php echo __('Send', THEME_NAME) ?>" class="button th-brown w-230">
		</div>

		</form>

		<div class="train-railing separator">
			<div class="railing"></div>
			<h3 class="train"><?php echo __( 'Contact info', THEME_NAME ) ?></h3>
			<div class="railing"></div>
		</div>

		<div class="contact-info grid grid-fluid-3">
			<div class="col alpha type phone">
				<?php echo estetico_get_setting('contacts_phone_1') ?><br>
				<?php echo estetico_get_setting('contacts_phone_2') ?>
			</div>
			<div class="col type email">
				<a href="mailto:<?php echo estetico_get_setting('contacts_email_1') ?>"><?php echo estetico_get_setting('contacts_email_1') ?></a>
				<a href="mailto:<?php echo estetico_get_setting('contacts_email_2') ?>"><?php echo estetico_get_setting('contacts_email_2') ?></a>
			</div>
			<?php
			$address = estetico_get_setting('contacts_address');
			?>
			<?php if(!empty($address)) : ?>
			<div class="col omega type address">
				<?php
				echo $address;

				$latlng = estetico_get_latlng_by_address( $address, true );
				?>
			</div>
			<?php endif ?>
		</div>
		
		<?php if(isset($latlng) && $latlng !== false) : ?>
		<div class="train-railing separator">
			<div class="railing"></div>
			<h3 class="train"><?php echo __( 'Office location', THEME_NAME ) ?></h3>
			<div class="railing"></div>
		</div>

		<div class="box map">
			<div id="contacts-map" class="use-box-sizing-content-box" data-lat="<?php echo $latlng->lat ?>" data-lng="<?php echo $latlng->lng ?>"></div>
		</div>
	<?php endif ?>

	<?php endwhile ?>
</div>

<?php if($sidebar_position_mobile == 'after') : ?>
	<?php get_sidebar(); ?>
<?php endif ?>

<?php get_footer() ?>