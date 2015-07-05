<?php if( ! empty( $meta[USE_PREFIX . 'agent'][0] ) ) : ?>

	<?php
	$agent = null;
	$agents = @unserialize( $meta[USE_PREFIX . 'agent'][0] );

	// If unserializiton fails this means the old format is used with only one agent. Create an array to use the new multiple agents functionallity.
	if($agents === false) {
		$agents = array( $meta[USE_PREFIX . 'agent'][0] );
	}

	// No agent is selected
	if( empty($agents) || ( count($agents) == 1 && $agents[0] == '_') ) {
		return;
	}

	$property_id = Property::getCurrent()->getId();

	foreach($agents as $agent_id) :
		try {
			$agent = new Agent( $agent_id );
		} catch(Exception $ex) {
		}

		if( $agent != null ) : ?>

		<div class="box general">

			<div class="agent card">

				<div class="photo">
					<?php echo get_the_post_thumbnail( $agent->getId(), array( 70, 70 ) ) ?>
				</div>
				
				<span class="call-to-action"><?php echo __( 'Contact the agent', THEME_NAME ) ?></span>
				<h5 class="name"><?php echo $agent->getName() ?></h5>

				<div class="contact-info">
					<?php if( $agent->hasPhone() ) : ?>
					<strong>phone:</strong> <?php echo $agent->getPhone() ?><br>
					<?php endif ?>
					
					<?php if( $agent->hasEmail() ) : ?>
					<strong>e-mail:</strong> <a href="mailto:<?php echo $agent->getEmail() ?>"><?php echo $agent->getEmail() ?></a>
					<?php endif ?>
				</div>

				<div class="descr"><?php echo $agent->getDescription() ?></div>

			</div>

			<?php if( $agent->isDirectContactEnabled() ) : ?>
			<form method="post" action="" class="">
				
				<div class="field">
					<input type="text" name="name" class="input text" required="required" placeholder="<?php echo __( 'Your name', THEME_NAME ) ?>">
				</div>

				<div class="field">
					<input type="text" name="email" class="input text" required="required" placeholder="<?php echo __( 'Your e-mail', THEME_NAME ) ?>">
				</div>

				<div class="field">
					<input type="text" name="phone" class="input text" required="required" placeholder="<?php echo __( 'Your phone', THEME_NAME ) ?>">
				</div>

				<div class="field">
					<textarea name="message" class="input text" required="required" placeholder="<?php echo __( 'Your message', THEME_NAME ) ?>"></textarea>
				</div>
				
				<div class="field">
					<input type="submit" class="button full th-brown" value="<?php echo __( 'Send', THEME_NAME ) ?>" name="">
				</div>

				<input type="hidden" name="agent_id" value="<?php echo $agent->getId() ?>">
				<input type="hidden" name="property_id" value="<?php echo $property_id ?>">
				<input type="hidden" name="city" value="">
				<input type="hidden" name="form_action" value="agent_contact">
			</form>
			<?php endif ?>

		</div>
		<?php endif ?>
	
	<?php endforeach ?>

<?php endif ?>